<?php

namespace Database\Factories\Entities\Investment;

use App\Entities\Campaign\Campaign;
use App\Entities\Investment\Investment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class InvestmentFactory extends Factory
{
    protected $model = Investment::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $campaign = Campaign::first();

        if (!$campaign instanceof Campaign) {
            return [];
        }

        $targetAmount = $campaign->target_amount;
        $investmentMultiple = $campaign->investment_multiple;
        $amount = $this->faker->randomElement([1, 2, 3]) * $investmentMultiple;

        $totalInvestments = Investment::where('campaign_id', $campaign->id)->sum('amount');

        if ($totalInvestments + $amount > $targetAmount) {
            return [];
        }

        return [
            'campaign_id'       => $campaign->id,
            'amount'            => $amount,
            'guest_identifier'  => $this->faker->email
        ];
    }
}
