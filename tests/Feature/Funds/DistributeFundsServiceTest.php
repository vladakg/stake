<?php

namespace Tests\Feature\Funds;

use App\Entities\Campaign\Campaign;
use App\Entities\Investment\Investment;
use App\Entities\Property\Property;
use App\Services\DistributeFundsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DistributeFundsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $campaign;
    protected $investment;
    protected DistributeFundsService $distributeFundsService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->property = Property::factory()->create();
        $this->campaign = Campaign::factory()->create([
            'property_id' => $this->property->first()->id
        ]);

        $this->distributeFundsService = new DistributeFundsService();
    }

    public function test_distribute_funds_to_investors()
    {
        $amount = 1000;

        $this->investment = Investment::factory()->create([
            'amount' => $amount,
            'campaign_id' => $this->campaign->id,
        ]);

        $result = $this->distributeFundsService->distribute($this->campaign->property_id, $amount);

        $this->assertEquals(1, $result['count']);
        $this->assertEquals($amount, $result['total_distributed']);
        $this->assertEquals($amount, $result['distributed_amount_per_investor']);
    }

    public function test_no_investors_in_campaign()
    {
        $property = Property::factory()->create(['id' => 50]);
        $campaignWithoutInvestors = Campaign::factory()->create([
            'property_id' => $property->id,
        ]);

        $amount = 1000;

        $result = $this->distributeFundsService->distribute($campaignWithoutInvestors->property_id, $amount);

        $this->assertEquals(0, $result['count']);
        $this->assertEquals(0, $result['total_distributed']);
        $this->assertEquals(0, $result['distributed_amount_per_investor']);
    }

    public function test_distribute_funds_to_multiple_investors()
    {
        $this->investment = Investment::factory()->create([
            'amount' => 500,
            'campaign_id' => $this->campaign->id,
        ]);

        $secondInvestment = Investment::factory()->create([
            'amount' => 500,
            'campaign_id' => $this->campaign->id,
        ]);

        $amount = 1000;

        $result = $this->distributeFundsService->distribute($this->campaign->property_id, $amount);

        $this->assertEquals(2, $result['count']);
        $this->assertEquals(1000, $result['total_distributed']);
        $this->assertEquals(500, $result['distributed_amount_per_investor']);
    }

    public function test_distribute_funds_to_multiple_investors_with_different_amounts()
    {
        $firstInvestment = Investment::factory()->create([
            'amount' => 200,
            'campaign_id' => $this->campaign->id,
        ]);

        $secondInvestment = Investment::factory()->create([
            'amount' => 300,
            'campaign_id' => $this->campaign->id,
        ]);

        $thirdInvestment = Investment::factory()->create([
            'amount' => 500,
            'campaign_id' => $this->campaign->id,
        ]);

        $totalAmountToDistribute = 1000;

        $result = $this->distributeFundsService->distribute($this->campaign->property_id, $totalAmountToDistribute);

        $totalInvested = $firstInvestment->amount + $secondInvestment->amount + $thirdInvestment->amount;
        $investorCount = 3;
        $distributedAmountPerInvestor = $totalAmountToDistribute / $investorCount;

        $this->assertEquals(3, $result['count']);
        $this->assertEquals($totalAmountToDistribute, $result['total_distributed']);
        $this->assertEqualsWithDelta($distributedAmountPerInvestor, $result['distributed_amount_per_investor'], 0.01);
    }
}
