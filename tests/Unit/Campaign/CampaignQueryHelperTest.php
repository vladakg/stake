<?php

namespace Tests\Unit\Campaign;

use App\Entities\Campaign\Campaign;
use Mockery;
use Tests\TestCase;

class CampaignQueryHelperTest extends TestCase
{
    public function test_calculate_percentage_raised()
    {
        $campaign = $this->createCampaign(500, 1000);

        $this->assertEquals(50.0, $campaign->calculatePercentageRaised());
    }

    public function test_get_remaining_investment_amount()
    {
        $campaign = $this->createCampaign(300, 1000);

        $this->assertEquals(700.0, $campaign->getRemainingInvestmentAmount());
    }

    public function test_validate_investment_amount_returns_true_when_divisible()
    {
        $campaign = $this->createCampaign(0, 0, 300);

        $this->assertTrue($campaign->validateInvestmentAmount(600));
        $this->assertFalse($campaign->validateInvestmentAmount(250));
    }

    public function test_has_user_invested_returns_true_if_exists()
    {
        $mockHasMany = Mockery::mock(\Illuminate\Database\Eloquent\Relations\HasMany::class);
        $mockHasMany->shouldReceive('where')
            ->with('guest_identifier', 'test-id')
            ->andReturnSelf();
        $mockHasMany->shouldReceive('exists')->andReturn(true);

        $campaign = Mockery::mock(Campaign::class)->makePartial();
        $campaign->shouldReceive('investments')->andReturn($mockHasMany);

        $this->assertTrue($campaign->hasUserInvested('test-id'));
    }

    public function test_has_user_invested_returns_false_if_not_exists()
    {
        $mockHasMany = Mockery::mock(\Illuminate\Database\Eloquent\Relations\HasMany::class);
        $mockHasMany->shouldReceive('where')
            ->with('guest_identifier', 'test-id')
            ->andReturnSelf();
        $mockHasMany->shouldReceive('exists')->andReturn(false);

        $campaign = Mockery::mock(Campaign::class)->makePartial();
        $campaign->shouldReceive('investments')->andReturn($mockHasMany);

        // Test if the user has not invested yet
        $this->assertFalse($campaign->hasUserInvested('test-id'));
    }

    private function createCampaign($current_amount = 0, $target_amount = 0, $investment_multiple = 0)
    {
        $campaign = new Campaign();
        $campaign->current_amount = $current_amount;
        $campaign->target_amount = $target_amount;
        $campaign->investment_multiple = $investment_multiple;

        return $campaign;
    }
}
