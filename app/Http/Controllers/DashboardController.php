<?php

namespace App\Http\Controllers;

use App\Models\FundingOpportunity;
use App\Models\FundingRequest;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === User::ROLE_STARTUP) {
            $stats = [
                'requests_total' => FundingRequest::where('startup_user_id', $user->id)->count(),
                'requests_accepted' => FundingRequest::where('startup_user_id', $user->id)->where('status', 'accepted')->count(),
                'open_opportunities' => FundingOpportunity::where('is_active', true)->count(),
            ];

            $recentRequests = FundingRequest::with('investor')
                ->where('startup_user_id', $user->id)
                ->latest()
                ->take(8)
                ->get();
        } elseif ($user->role === User::ROLE_INVESTOR) {
            $stats = [
                'opportunities_total' => FundingOpportunity::where('user_id', $user->id)->count(),
                'pending_requests' => FundingRequest::where('investor_user_id', $user->id)->where('status', 'pending')->count(),
                'accepted_requests' => FundingRequest::where('investor_user_id', $user->id)->where('status', 'accepted')->count(),
            ];

            $recentRequests = FundingRequest::with('startup')
                ->where('investor_user_id', $user->id)
                ->latest()
                ->take(8)
                ->get();
        } else {
            $stats = [
                'users_total' => User::count(),
                'startups' => User::where('role', User::ROLE_STARTUP)->count(),
                'investors' => User::where('role', User::ROLE_INVESTOR)->count(),
                'pending_verification' => User::where('is_verified', false)->count(),
            ];

            $recentRequests = FundingRequest::with(['startup', 'investor'])
                ->latest()
                ->take(8)
                ->get();
        }

        return view('dashboard', [
            'stats' => $stats,
            'recentRequests' => $recentRequests,
        ]);
    }
}
