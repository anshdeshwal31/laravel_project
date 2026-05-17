<?php

namespace App\Http\Controllers;

use App\Models\FundingOpportunity;
use Illuminate\Http\Request;

class FundingOpportunityController extends Controller
{
    public function index(Request $request)
    {
        $query = FundingOpportunity::query()->with('investor')->where('is_active', true);

        if ($request->filled('industry')) {
            $query->where('industry', 'like', '%'.$request->string('industry').'%');
        }

        if ($request->filled('stage')) {
            $query->where('stage', $request->string('stage'));
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%'.$request->string('location').'%');
        }

        if ($request->filled('min_amount')) {
            $query->where('max_amount', '>=', (float) $request->input('min_amount'));
        }

        if ($request->filled('max_amount')) {
            $query->where('min_amount', '<=', (float) $request->input('max_amount'));
        }

        return view('opportunities.index', [
            'opportunities' => $query->latest()->paginate(12)->withQueryString(),
            'filters' => $request->only(['industry', 'stage', 'location', 'min_amount', 'max_amount']),
        ]);
    }

    public function create()
    {
        return view('opportunities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'industry' => ['required', 'string', 'max:255'],
            'stage' => ['required', 'string', 'max:255'],
            'min_amount' => ['required', 'numeric', 'min:0'],
            'max_amount' => ['required', 'numeric', 'gte:min_amount'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['is_active'] = (bool) ($validated['is_active'] ?? true);

        FundingOpportunity::create($validated);

        return redirect()->route('opportunities.index')->with('status', 'Funding opportunity published.');
    }

    public function show(FundingOpportunity $opportunity)
    {
        $opportunity->load('investor.investorProfile');

        return view('opportunities.show', [
            'opportunity' => $opportunity,
        ]);
    }
}
