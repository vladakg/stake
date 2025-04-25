<?php

namespace Database\Factories\Entities\Campaign;

use App\Entities\Campaign\Campaign;
use App\Entities\Property\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Campaign>
 */
class CampaignFactory extends Factory
{
    protected $model = Campaign::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $investmentMultiple = $this->faker->randomFloat(2, 1, 10);
        $multiplier = $this->faker->numberBetween(100, 1000);

        return [
            'name'                  => $this->faker->company,
            'image_url'             => $this->faker->imageUrl(640, 480, 'business', true),
            'percentage_raised'     => 0,
            'target_amount'         => round($investmentMultiple * $multiplier, 2),
            'currency'              => 'AED',
            'city_area'             => $this->faker->city,
            'country'               => 'UAE',
            'number_of_investors'   => 0,
            'investment_multiple'   => $investmentMultiple,
            'current_amount'        => 0
        ];
    }
}
