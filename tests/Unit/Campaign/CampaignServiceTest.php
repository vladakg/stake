<?php

namespace Tests\Unit\Campaign;

use App\Entities\Campaign\Campaign;
use App\Entities\Campaign\Repository\CampaignRepositoryInterface;
use App\Entities\Campaign\Services\CampaignService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class CampaignServiceTest extends TestCase
{
    public function test_get_paginated_campaigns_calls_repository_method()
    {
        $mockRepository = Mockery::mock(CampaignRepositoryInterface::class);
        $paginator = Mockery::mock(LengthAwarePaginator::class);

        $mockRepository->shouldReceive('paginated')
            ->once()
            ->with('id', 'asc', 10, null)
            ->andReturn($paginator);

        $service = new CampaignService($mockRepository);
        $result = $service->getPaginatedCampaigns();

        $this->assertSame($paginator, $result);
    }

    public function test_find_or_fail_delegates_to_repository()
    {
        $mockRepository = Mockery::mock(CampaignRepositoryInterface::class);
        $campaign = Mockery::mock(Campaign::class);

        $mockRepository->shouldReceive('findOrFail')
            ->once()
            ->with(123)
            ->andReturn($campaign);

        $service = new CampaignService($mockRepository);
        $result = $service->findOrFail(123);

        $this->assertSame($campaign, $result);
    }

    public function test_find_or_fail_throws_exception_when_not_found()
    {
        $mockRepository = Mockery::mock(CampaignRepositoryInterface::class);

        $mockRepository->shouldReceive('findOrFail')
            ->once()
            ->with(999)
            ->andThrow(ModelNotFoundException::class);

        $service = new CampaignService($mockRepository);

        $this->expectException(ModelNotFoundException::class);

        $service->findOrFail(999);
    }

    public function test_update_after_investment_increments_investor_count_if_first_time()
    {
        $mockRepository = Mockery::mock(CampaignRepositoryInterface::class);
        $service = new CampaignService($mockRepository);

        $campaign = Mockery::mock(Campaign::class)->makePartial();
        $campaign->current_amount = 1000;
        $campaign->number_of_investors = 5;

        $campaign->shouldReceive('calculatePercentageRaised')
            ->once()
            ->andReturn(60.0);

        $campaign->shouldReceive('save')
            ->once();

        $service->updateAfterInvestment($campaign, 500, false);

        $this->assertEquals(1500, $campaign->current_amount);
        $this->assertEquals(6, $campaign->number_of_investors);
        $this->assertEquals(60.0, $campaign->percentage_raised);
    }

    public function test_update_after_investment_does_not_increment_investor_count_if_already_invested()
    {
        $mockRepository = Mockery::mock(CampaignRepositoryInterface::class);
        $service = new CampaignService($mockRepository);

        $campaign = Mockery::mock(Campaign::class)->makePartial();
        $campaign->current_amount = 1000;
        $campaign->number_of_investors = 5;

        $campaign->shouldReceive('calculatePercentageRaised')
            ->once()
            ->andReturn(70.0);

        $campaign->shouldReceive('save')
            ->once();

        $service->updateAfterInvestment($campaign, 500, true);

        $this->assertEquals(1500, $campaign->current_amount);
        $this->assertEquals(5, $campaign->number_of_investors);
        $this->assertEquals(70.0, $campaign->percentage_raised);
    }
}
