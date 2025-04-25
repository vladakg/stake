<?php

namespace Database\Seeders;

use App\Entities\Campaign\Campaign;
use App\Entities\Investment\Investment;
use Illuminate\Database\Seeder;

class InvestmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->resetFirstCampaign();
        Investment::factory(3)->create();
        $this->updateFirstCampaignStats();
    }

    private function resetFirstCampaign(): void
    {
        Campaign::first()->update([
            'target_amount'         => 10000000,
            'percentage_raised'     => 0,
            'number_of_investors'   => 0,
            'current_amount'        => 0
        ]);
    }

    private function updateFirstCampaignStats(): void
    {
        $campaign = Campaign::first();

        $currentAmount = $campaign->investments()->sum('amount');

        $uniqueInvestors = $campaign->investments()->distinct('guest_identifier')->count('guest_identifier');

        $percentageRaised = $campaign->target_amount > 0
            ? round(($currentAmount / $campaign->target_amount) * 100, 2)
            : 0;

        $campaign->update([
            'current_amount'        => $currentAmount,
            'number_of_investors'   => $uniqueInvestors,
            'percentage_raised'     => $percentageRaised,
        ]);
    }
}
