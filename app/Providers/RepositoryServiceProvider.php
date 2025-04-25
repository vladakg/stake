<?php

namespace App\Providers;

use App\Entities\Campaign\Repository\CampaignRepository;
use App\Entities\Campaign\Repository\CampaignRepositoryInterface;
use App\Entities\Investment\Repository\InvestmentRepository;
use App\Entities\Investment\Repository\InvestmentRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CampaignRepositoryInterface::class, CampaignRepository::class);
        $this->app->singleton(InvestmentRepositoryInterface::class, InvestmentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
