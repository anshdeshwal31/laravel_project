<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAssistantController extends Controller
{
    /** GET /ai-assistant — render the search page */
    public function index()
    {
        return view('ai-assistant');
    }

    /** POST /ai-assistant/search — proxy the query to the RAG microservice */
    public function search(Request $request)
    {
        $request->validate([
            'query' => ['required', 'string', 'min:3', 'max:500'],
            'top_k' => ['sometimes', 'integer', 'min:1', 'max:20'],
        ]);

        $ragUrl = rtrim(config('services.rag.url', 'http://localhost:3001'), '/');

        try {
            $response = Http::timeout(30)->post("{$ragUrl}/search", [
                'query' => $request->input('query'),
                'top_k' => $request->input('top_k', 6),
            ]);

            if ($response->failed()) {
                Log::warning('[AI Assistant] RAG service error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return response()->json([
                    'error' => 'The AI assistant is temporarily unavailable. Please try again later.',
                ], 503);
            }

            return response()->json($response->json());
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('[AI Assistant] Cannot reach RAG service', ['message' => $e->getMessage()]);

            return response()->json([
                'error' => 'Could not connect to the AI assistant service. Make sure the RAG service is running on port 3001.',
            ], 503);
        }
    }
}
