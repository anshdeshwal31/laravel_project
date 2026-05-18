<?php

namespace App\Http\Controllers;

use App\Models\InvestorProfile;
use App\Models\StartupProfile;
use App\Models\FundingOpportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecommendationController extends Controller
{
    /** GET /recommendations — startup sees their top matching investors */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'startup') {
            abort(403, 'Only startup accounts can view investor recommendations.');
        }

        $startup = StartupProfile::where('user_id', $user->id)->first();

        if (!$startup) {
            return view('recommendations', [
                'startup'         => null,
                'recommendations' => [],
                'error'           => 'Please complete your startup profile before viewing recommendations.',
            ]);
        }

        $recommenderUrl = rtrim(config('services.recommender.url', 'http://localhost:3002'), '/');

        // Gather all investor profiles with their user's last activity (updated_at as proxy)
        $investors = InvestorProfile::with('user')->get()->map(function ($inv) {
            return [
                'id'                    => $inv->id,
                'user_id'               => $inv->user_id,
                'investor_type'         => $inv->investor_type,
                'investment_min'        => (float) $inv->investment_min,
                'investment_max'        => (float) $inv->investment_max,
                'preferred_industries'  => $inv->preferred_industries ?? [],
                'location_preference'   => $inv->location_preference,
                'preferred_stage'       => null, // no separate field; handled by scoring engine
                'last_active_at'        => optional($inv->updated_at)->toIso8601String(),
            ];
        })->values()->toArray();

        if (empty($investors)) {
            return view('recommendations', [
                'startup'         => $startup,
                'recommendations' => [],
                'error'           => 'No investor profiles are registered on the platform yet.',
            ]);
        }

        try {
            $response = Http::timeout(60)->post(
                "{$recommenderUrl}/recommend/startup/{$startup->id}",
                [
                    'startup' => [
                        'id'                  => $startup->id,
                        'startup_name'        => $startup->startup_name,
                        'domain'              => $startup->domain,
                        'industry'            => $startup->industry,
                        'stage'               => $startup->stage,
                        'funding_requirement' => (float) $startup->funding_requirement,
                        'location'            => $startup->location,
                        'pitch_description'   => $startup->pitch_description,
                    ],
                    'investors' => $investors,
                    'top_n'     => 10,
                ]
            );

            if ($response->failed()) {
                Log::warning('[Recommendations] Recommender error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return view('recommendations', [
                    'startup'         => $startup,
                    'recommendations' => [],
                    'error'           => 'The recommendation engine is temporarily unavailable.',
                ]);
            }

            $data = $response->json();
            $recommendations = $data['recommendations'] ?? [];

            // Enrich each recommendation with investor + user details
            $investorMap = InvestorProfile::with('user')
                ->whereIn('id', collect($recommendations)->pluck('investor_id')->toArray())
                ->get()
                ->keyBy('id');

            $enriched = collect($recommendations)->map(function ($rec) use ($investorMap) {
                $inv  = $investorMap->get($rec['investor_id']);
                $user = $inv?->user;
                return array_merge($rec, [
                    'investor_name'    => $user?->name ?? 'Unknown Investor',
                    'investor_type'    => $inv?->investor_type ?? '-',
                    'investment_range' => '$' . number_format($inv?->investment_min ?? 0)
                                         . ' – $' . number_format($inv?->investment_max ?? 0),
                    'industries'       => implode(', ', $inv?->preferred_industries ?? []),
                    'location'         => $inv?->location_preference ?? 'Global',
                ]);
            })->toArray();

            return view('recommendations', [
                'startup'         => $startup,
                'recommendations' => $enriched,
                'error'           => null,
            ]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('[Recommendations] Cannot reach recommender service', ['message' => $e->getMessage()]);

            return view('recommendations', [
                'startup'         => $startup,
                'recommendations' => [],
                'error'           => 'Could not connect to the recommendation service (port 3002).',
            ]);
        }
    }
}
