<?php

namespace Database\Seeders;

use App\Entities\Campaign\Campaign;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 25; $i++) {
            Campaign::factory()->create([
                'property_id' => $i,
            ]);
        }
    }
}
