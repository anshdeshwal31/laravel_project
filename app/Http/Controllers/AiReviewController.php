<?php

namespace App\Http\Controllers;

use App\Models\FundingRequest;
use App\Models\FundingRequestReview;
use App\Jobs\AnalyzePitchDeckJob;
use Illuminate\Http\Request;

class AiReviewController extends Controller
{
    public function trigger(Request $request, FundingRequest $fundingRequest)
    {
        // Ensure only the investor can trigger the review
        if ($request->user()->id !== $fundingRequest->investor_user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $review = $fundingRequest->aiReview;
        
        if (!$review) {
            $review = FundingRequestReview::create([
                'funding_request_id' => $fundingRequest->id,
                'status' => 'pending'
            ]);
        }

        if ($review->status === 'processing') {
            return response()->json(['message' => 'Analysis is already in progress.', 'status' => 'processing']);
        }

        // Reset status if it failed before or is just created
        $review->update(['status' => 'pending']);

        AnalyzePitchDeckJob::dispatch($fundingRequest);

        return response()->json(['message' => 'Analysis triggered.', 'status' => 'pending']);
    }

    public function status(Request $request, FundingRequest $fundingRequest)
    {
        if ($request->user()->id !== $fundingRequest->investor_user_id && $request->user()->id !== $fundingRequest->startup_user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $review = $fundingRequest->aiReview;

        if (!$review) {
            return response()->json(['status' => 'none']);
        }

        // Sanitize string attributes to prevent json_encode errors on existing legacy records
        if ($review->error_message) {
            $review->error_message = mb_convert_encoding($review->error_message, 'UTF-8', 'UTF-8');
        }
        if ($review->summary) {
            $review->summary = mb_convert_encoding($review->summary, 'UTF-8', 'UTF-8');
        }
        if ($review->verdict) {
            $review->verdict = mb_convert_encoding($review->verdict, 'UTF-8', 'UTF-8');
        }
        if ($review->raw_analysis) {
            $review->raw_analysis = mb_convert_encoding($review->raw_analysis, 'UTF-8', 'UTF-8');
        }

        return response()->json($review);
    }

    public function show(Request $request, FundingRequest $fundingRequest)
    {
        if ($request->user()->id !== $fundingRequest->investor_user_id && $request->user()->id !== $fundingRequest->startup_user_id) {
            abort(403);
        }

        $fundingRequest->load(['startup.startupProfile', 'opportunity']);
        $review = $fundingRequest->aiReview;

        return view('requests.review', [
            'fundingRequest' => $fundingRequest,
            'review' => $review,
        ]);
    }

    public function index(Request $request)
    {
        abort_if($request->user()->role !== 'investor', 403);

        $investorId = $request->user()->id;

        $requests = FundingRequest::where('investor_user_id', $investorId)
            ->with(['startup.startupProfile', 'opportunity', 'aiReview'])
            ->latest()
            ->paginate(12);

        // Calculate statistics for the dashboard
        $totalRequests = FundingRequest::where('investor_user_id', $investorId)->count();
        
        $reviewsQuery = FundingRequestReview::whereHas('fundingRequest', function($query) use ($investorId) {
            $query->where('investor_user_id', $investorId);
        });
        
        $auditedCount = (clone $reviewsQuery)->where('status', 'completed')->count();
        $averageScore = (clone $reviewsQuery)->where('status', 'completed')->avg('overall_score') ?? 0;

        return view('requests.review-list', [
            'requests' => $requests,
            'totalRequests' => $totalRequests,
            'auditedCount' => $auditedCount,
            'averageScore' => round($averageScore, 1),
        ]);
    }
}
