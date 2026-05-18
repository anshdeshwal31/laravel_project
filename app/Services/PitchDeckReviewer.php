<?php

namespace App\Services;

use App\Models\FundingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class PitchDeckReviewer
{
    private Parser $pdfParser;

    public function __construct()
    {
        $this->pdfParser = new Parser();
    }

    public function evaluate(FundingRequest $fundingRequest): array
    {
        $startupProfile = $fundingRequest->startup->startupProfile;
        $pdfText = $this->extractPdfText($startupProfile->document_paths);

        $prompt = $this->buildPrompt($startupProfile, $fundingRequest, $pdfText);

        return $this->callGroqLlm($prompt);
    }

    private function extractPdfText(?array $documentPaths): string
    {
        if (empty($documentPaths)) {
            return "No pitch deck or documents provided.";
        }

        $text = "";
        foreach ($documentPaths as $path) {
            // Assuming documents are stored in the 'public' disk
            if (Storage::disk('public')->exists($path)) {
                $extension = pathinfo($path, PATHINFO_EXTENSION);
                if (strtolower($extension) === 'pdf') {
                    try {
                        $fullPath = Storage::disk('public')->path($path);
                        $pdf = $this->pdfParser->parseFile($fullPath);
                        $text .= "--- Document: {$path} ---\n";
                        
                        // Extract text and convert any malformed binary characters to valid UTF-8
                        $extracted = $pdf->getText();
                        $extracted = mb_convert_encoding($extracted, 'UTF-8', 'UTF-8');
                        
                        $text .= $extracted . "\n\n";
                    } catch (\Exception $e) {
                        Log::warning("Failed to parse PDF: {$path}", ['error' => $e->getMessage()]);
                        $text .= "[Failed to parse {$path}]\n";
                    }
                }
            }
        }

        return $text ?: "No readable PDF documents found.";
    }

    private function buildPrompt($startupProfile, FundingRequest $fundingRequest, string $pdfText): string
    {
        $startupName = mb_convert_encoding($startupProfile->startup_name ?? '', 'UTF-8', 'UTF-8');
        $industry = mb_convert_encoding($startupProfile->industry ?? '', 'UTF-8', 'UTF-8');
        $stage = mb_convert_encoding($startupProfile->stage ?? '', 'UTF-8', 'UTF-8');
        $pitchDescription = mb_convert_encoding($startupProfile->pitch_description ?? '', 'UTF-8', 'UTF-8');
        $message = mb_convert_encoding($fundingRequest->message ?? '', 'UTF-8', 'UTF-8');

        return <<<PROMPT
You are an expert venture capitalist and startup evaluator. 
Analyze the following startup profile, funding request, and pitch deck contents.

Startup Details:
- Name: {$startupName}
- Industry: {$industry}
- Stage: {$stage}
- Pitch Description: {$pitchDescription}

Funding Request:
- Requested Amount: \${$fundingRequest->requested_amount}
- Message: {$message}

Parsed Pitch Deck Contents:
{$pdfText}

Based on this information, provide a comprehensive investment review in JSON format.
Your response MUST be valid JSON matching this exact structure:
{
  "overall_score": 85, // integer 0-100 indicating investment feasibility
  "summary": "Executive summary of the opportunity...",
  "strengths": ["strength 1", "strength 2"],
  "weaknesses": ["weakness 1", "weakness 2"],
  "risks": ["risk 1", "risk 2"],
  "verdict": "Final investment recommendation"
}
PROMPT;
    }

    private function callGroqLlm(string $prompt): array
    {
        $apiKey = env('GROQ_API_KEY');
        if (empty($apiKey)) {
            throw new \Exception("GROQ_API_KEY is not configured.");
        }

        $response = Http::withoutVerifying()
            ->withToken($apiKey)
            ->timeout(60)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an AI investment reviewer that only outputs valid JSON.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
            ]);

        if ($response->failed()) {
            throw new \Exception("LLM API request failed: " . $response->body());
        }

        $data = $response->json();
        $content = $data['choices'][0]['message']['content'] ?? '{}';
        
        // Ensure LLM text content is clean UTF-8
        $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
        
        $decoded = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Failed to decode JSON response from LLM.");
        }
        
        $decoded['raw_analysis'] = $content;

        return $decoded;
    }
}
