<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendIndexEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Maximum number of attempts before the job is marked as failed */
    public int $tries = 3;

    /** Seconds to wait before retrying on failure (exponential: 10s, 30s, 90s) */
    public array $backoff = [10, 30, 90];

    /** Timeout for the HTTP call to the RAG service */
    public int $timeout = 30;

    public function __construct(
        private readonly string $modelType,
        private readonly string $event,
        private readonly int    $modelId,
        private readonly array  $data = []
    ) {}

    public function handle(): void
    {
        $ragUrl = rtrim(config('services.rag.url', 'http://localhost:3001'), '/');

        $payload = [
            'model_type' => $this->modelType,
            'event'      => $this->event,
            'id'         => $this->modelId,
            'data'       => $this->data,
        ];

        try {
            $response = Http::timeout($this->timeout)
                ->post("{$ragUrl}/index/event", $payload);

            if ($response->failed()) {
                Log::warning('[RAG] Index event failed', [
                    'status'  => $response->status(),
                    'body'    => $response->body(),
                    'payload' => $payload,
                ]);

                // Re-throw so the queue retries the job
                throw new \RuntimeException(
                    "RAG /index/event returned HTTP {$response->status()}: {$response->body()}"
                );
            }

            Log::info('[RAG] Index event sent', [
                'model_type' => $this->modelType,
                'event'      => $this->event,
                'model_id'   => $this->modelId,
                'response'   => $response->json(),
            ]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('[RAG] Connection error — RAG service may be down', [
                'message' => $e->getMessage(),
                'payload' => $payload,
            ]);
            throw $e; // Let the queue retry
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('[RAG] SendIndexEventJob permanently failed', [
            'model_type' => $this->modelType,
            'event'      => $this->event,
            'model_id'   => $this->modelId,
            'error'      => $exception->getMessage(),
        ]);
    }
}
