<?php

namespace App\Providers;

use App\Models\FundingOpportunity;
use App\Models\InvestorProfile;
use App\Models\StartupProfile;
use App\Observers\IndexEventObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * Registers model observers so every create/update/delete event
     * is forwarded (via a queued job) to the RAG microservice.
     */
    public function boot(): void
    {
        $observer = new IndexEventObserver();

        FundingOpportunity::created(fn ($m) => $observer->fundingOpportunityCreated($m));
        FundingOpportunity::updated(fn ($m) => $observer->fundingOpportunityUpdated($m));
        FundingOpportunity::deleted(fn ($m) => $observer->fundingOpportunityDeleted($m));

        InvestorProfile::created(fn ($m) => $observer->investorProfileCreated($m));
        InvestorProfile::updated(fn ($m) => $observer->investorProfileUpdated($m));
        InvestorProfile::deleted(fn ($m) => $observer->investorProfileDeleted($m));

        StartupProfile::created(fn ($m) => $observer->startupProfileCreated($m));
        StartupProfile::updated(fn ($m) => $observer->startupProfileUpdated($m));
        StartupProfile::deleted(fn ($m) => $observer->startupProfileDeleted($m));
    }
}
