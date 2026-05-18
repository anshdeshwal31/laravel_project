<?php

namespace App\Jobs;

use App\Models\FundingRequest;
use App\Models\FundingRequestReview;
use App\Services\PitchDeckReviewer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AnalyzePitchDeckJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120; // 2 minutes

    public function __construct(
        public FundingRequest $fundingRequest
    ) {}

    public function handle(PitchDeckReviewer $reviewer): void
    {
        $review = FundingRequestReview::firstOrCreate(
            ['funding_request_id' => $this->fundingRequest->id]
        );

        $review->update(['status' => 'processing', 'error_message' => null]);

        try {
            $result = $reviewer->evaluate($this->fundingRequest);

            $review->update([
                'status' => 'completed',
                'overall_score' => $result['overall_score'] ?? null,
                'summary' => isset($result['summary']) ? mb_convert_encoding($result['summary'], 'UTF-8', 'UTF-8') : null,
                'strengths' => isset($result['strengths']) ? array_map(fn($item) => mb_convert_encoding($item, 'UTF-8', 'UTF-8'), (array)$result['strengths']) : [],
                'weaknesses' => isset($result['weaknesses']) ? array_map(fn($item) => mb_convert_encoding($item, 'UTF-8', 'UTF-8'), (array)$result['weaknesses']) : [],
                'risks' => isset($result['risks']) ? array_map(fn($item) => mb_convert_encoding($item, 'UTF-8', 'UTF-8'), (array)$result['risks']) : [],
                'verdict' => isset($result['verdict']) ? mb_convert_encoding($result['verdict'], 'UTF-8', 'UTF-8') : null,
                'raw_analysis' => isset($result['raw_analysis']) ? mb_convert_encoding($result['raw_analysis'], 'UTF-8', 'UTF-8') : null,
            ]);

            Log::info("Pitch deck analysis completed for request {$this->fundingRequest->id}");
        } catch (\Throwable $e) {
            Log::error("Pitch deck analysis failed for request {$this->fundingRequest->id}: " . $e->getMessage());
            $review->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            
            // Re-throw so the queue knows it failed, though we might not want to retry
            // if it's an API key error or persistent parsing error. Let's just mark failed.
        }
    }
}
