<?php

namespace App\Http\Controllers;

use App\Models\FundingOpportunity;
use App\Models\FundingRequest;
use App\Models\Message;
use App\Notifications\FundingRequestAccepted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class FundingRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = FundingRequest::query()->with(['startup.startupProfile', 'investor.investorProfile', 'opportunity']);

        if ($user->role === 'startup') {
            $query->where('startup_user_id', $user->id);
        } elseif ($user->role === 'investor') {
            $query->where('investor_user_id', $user->id);
        }

        return view('requests.index', [
            'requests' => $query->latest()->paginate(15),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'investor_user_id' => ['required', 'exists:users,id'],
            'funding_opportunity_id' => ['nullable', 'exists:funding_opportunities,id'],
            'requested_amount' => ['required', 'numeric', 'min:0'],
            'message' => ['nullable', 'string'],
        ]);

        if (! is_null($validated['funding_opportunity_id'] ?? null)) {
            $opportunity = FundingOpportunity::findOrFail($validated['funding_opportunity_id']);
            if ((int) $validated['investor_user_id'] !== $opportunity->user_id) {
                return back()->withErrors(['investor_user_id' => 'Selected investor does not own this opportunity.']);
            }
        }

        FundingRequest::create([
            'startup_user_id' => $request->user()->id,
            'investor_user_id' => $validated['investor_user_id'],
            'funding_opportunity_id' => $validated['funding_opportunity_id'] ?? null,
            'requested_amount' => $validated['requested_amount'],
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('requests.index')->with('status', 'Funding request submitted.');
    }

    public function updateStatus(Request $request, FundingRequest $fundingRequest)
    {
        abort_if($request->user()->id !== $fundingRequest->investor_user_id, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:accepted,rejected,pending'],
        ]);

        $fundingRequest->update(['status' => $validated['status']]);

        // When an investor accepts a funding request, seed an initial message
        // and redirect both parties to the conversation view.
        if ($validated['status'] === 'accepted') {
            // Create a system message from the investor to start the chat thread.
            Message::create([
                'funding_request_id' => $fundingRequest->id,
                'sender_id' => $request->user()->id,
                'body' => 'Your funding request has been accepted. Please use this thread to coordinate next steps.',
            ]);

            // Send a simple notification email to the startup (optional).
            try {
                $fundingRequest->startup->notify(new FundingRequestAccepted());
            } catch (\Throwable $e) {
                // Don't block the flow if notifications fail.
            }

            return redirect()->route('requests.index', ['open' => $fundingRequest->id])->with('status', 'Request accepted — conversation opened.');
        }

        return redirect()->route('requests.index')->with('status', 'Request status updated.');
    }
}
