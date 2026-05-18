<?php

namespace App\Observers;

use App\Jobs\SendIndexEventJob;

/**
 * Observes FundingOpportunity, InvestorProfile, and StartupProfile model events.
 * On each create/update/delete, dispatches a queued job to notify the RAG microservice.
 *
 * Register in App\Providers\AppServiceProvider::boot().
 */
class IndexEventObserver
{
    // ── FundingOpportunity ──────────────────────────────────────────────────

    public function fundingOpportunityCreated(\App\Models\FundingOpportunity $model): void
    {
        $this->dispatch('funding_opportunity', 'created', $model->id, $this->opportunityPayload($model));
    }

    public function fundingOpportunityUpdated(\App\Models\FundingOpportunity $model): void
    {
        $this->dispatch('funding_opportunity', 'updated', $model->id, $this->opportunityPayload($model));
    }

    public function fundingOpportunityDeleted(\App\Models\FundingOpportunity $model): void
    {
        $this->dispatch('funding_opportunity', 'deleted', $model->id);
    }

    // ── InvestorProfile ─────────────────────────────────────────────────────

    public function investorProfileCreated(\App\Models\InvestorProfile $model): void
    {
        $this->dispatch('investor_profile', 'created', $model->id, $this->investorPayload($model));
    }

    public function investorProfileUpdated(\App\Models\InvestorProfile $model): void
    {
        $this->dispatch('investor_profile', 'updated', $model->id, $this->investorPayload($model));
    }

    public function investorProfileDeleted(\App\Models\InvestorProfile $model): void
    {
        $this->dispatch('investor_profile', 'deleted', $model->id);
    }

    // ── StartupProfile ──────────────────────────────────────────────────────

    public function startupProfileCreated(\App\Models\StartupProfile $model): void
    {
        $this->dispatch('startup_profile', 'created', $model->id, $this->startupPayload($model));
    }

    public function startupProfileUpdated(\App\Models\StartupProfile $model): void
    {
        $this->dispatch('startup_profile', 'updated', $model->id, $this->startupPayload($model));
    }

    public function startupProfileDeleted(\App\Models\StartupProfile $model): void
    {
        $this->dispatch('startup_profile', 'deleted', $model->id);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function dispatch(string $modelType, string $event, int $id, array $data = []): void
    {
        SendIndexEventJob::dispatch($modelType, $event, $id, $data);
    }

    private function opportunityPayload(\App\Models\FundingOpportunity $m): array
    {
        return [
            'title'       => $m->title,
            'description' => $m->description,
            'industry'    => $m->industry,
            'stage'       => $m->stage,
            'min_amount'  => (float) $m->min_amount,
            'max_amount'  => (float) $m->max_amount,
            'location'    => $m->location,
            'is_active'   => $m->is_active,
        ];
    }

    private function investorPayload(\App\Models\InvestorProfile $m): array
    {
        return [
            'investor_type'         => $m->investor_type,
            'investment_min'        => (float) $m->investment_min,
            'investment_max'        => (float) $m->investment_max,
            'preferred_industries'  => $m->preferred_industries ?? [],
            'location_preference'   => $m->location_preference,
        ];
    }

    private function startupPayload(\App\Models\StartupProfile $m): array
    {
        return [
            'startup_name'        => $m->startup_name,
            'domain'              => $m->domain,
            'industry'            => $m->industry,
            'stage'               => $m->stage,
            'funding_requirement' => (float) $m->funding_requirement,
            'location'            => $m->location,
            'pitch_description'   => $m->pitch_description,
        ];
    }
}
