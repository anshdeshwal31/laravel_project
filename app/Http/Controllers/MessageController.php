<?php

namespace App\Http\Controllers;

use App\Models\FundingRequest;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function show(Request $request, FundingRequest $fundingRequest)
    {
        $this->authorizeAccess($request, $fundingRequest);

        $fundingRequest->load([
            'startup.startupProfile',
            'investor.investorProfile',
            'messages.sender',
        ]);

        return view('messages.show', [
            'fundingRequest' => $fundingRequest,
        ]);
    }

    /**
     * Return a partial view with messages for AJAX slide-over.
     */
    public function partial(Request $request, FundingRequest $fundingRequest)
    {
        $this->authorizeAccess($request, $fundingRequest);

        $fundingRequest->load([
            'startup.startupProfile',
            'investor.investorProfile',
            'messages.sender',
        ]);

        return view('messages._partial', [
            'fundingRequest' => $fundingRequest,
        ]);
    }

    public function store(Request $request, FundingRequest $fundingRequest)
    {
        $this->authorizeAccess($request, $fundingRequest);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:3000'],
        ]);

        Message::create([
            'funding_request_id' => $fundingRequest->id,
            'sender_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        // Reload the conversation for AJAX or redirect for normal requests
        $fundingRequest->load(['messages.sender']);

        if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('messages._partial', ['fundingRequest' => $fundingRequest]);
        }

        return redirect()->route('messages.show', $fundingRequest)->with('status', 'Message sent.');
    }

    private function authorizeAccess(Request $request, FundingRequest $fundingRequest): void
    {
        $isParticipant = in_array($request->user()->id, [
            $fundingRequest->startup_user_id,
            $fundingRequest->investor_user_id,
        ], true);

        if (! $isParticipant && $request->user()->role !== 'admin') {
            abort(403);
        }
    }
}
