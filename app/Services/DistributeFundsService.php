<?php

namespace App\Services;

use App\Entities\Campaign\Campaign;
use App\Jobs\ProcessDistributionFundsJob;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class DistributeFundsService
{
    public function distribute(int $propertyId, float $amount): array
    {
        $campaign = Campaign::where('property_id', $propertyId)->first();

        if (!$campaign instanceof Campaign) {
            throw new ModelNotFoundException("Campaign not found for property ID: {$propertyId}");
        }

        $investments = $campaign->investments()
            ->select('guest_identifier', DB::raw('SUM(amount) as guest_total_invested'))
            ->groupBy('guest_identifier')
            ->get();

        if ($investments->isEmpty()) {
            return [
                'count'                             => 0,
                'total_distributed'                 => 0,
                'distributed_amount_per_investor'   => 0,
            ];
        }

        $total_invested = $investments->sum('guest_total_invested');
        $total_investors = $investments->count();

        $distributed = 0.0;

        $investments->chunk(100)->each(function ($chunk) use ($amount, $total_invested, $total_investors, &$distributed, &$remainder,  &$indexCounter) {
            foreach ($chunk as $investor) {
                $indexCounter++;

                $share_percentage = $investor->guest_total_invested / $total_invested;
                $share = round($share_percentage * $amount, 2);
                $remainder = round($amount - $distributed, 2);

                if ($indexCounter === $total_investors) {
                    $share = $remainder;
                } elseif ($share > $remainder) {
                    $share = $remainder;
                }

                $distributed += $share;

                ProcessDistributionFundsJob::dispatch(
                    $investor->guest_identifier,
                    $share
                );
            }
        });

        return [
            'count'                             => $total_investors,
            'total_distributed'                 => $distributed,
            'distributed_amount_per_investor'   => round($distributed / $total_investors, 2),
        ];
    }
}
