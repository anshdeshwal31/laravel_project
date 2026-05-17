<?php

namespace App\Http\Controllers;

use App\Models\InvestorProfile;
use Illuminate\Http\Request;

class InvestorProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('investor-profiles.edit', [
            'profile' => $request->user()->investorProfile,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'investor_type' => ['required', 'string', 'max:255'],
            'investment_min' => ['required', 'numeric', 'min:0'],
            'investment_max' => ['required', 'numeric', 'gte:investment_min'],
            'preferred_industries' => ['nullable', 'string'],
            'location_preference' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['preferred_industries'] = $validated['preferred_industries']
            ? array_values(array_filter(array_map('trim', explode(',', $validated['preferred_industries']))))
            : [];

        InvestorProfile::updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return redirect()->route('investor.profile.edit')->with('status', 'Investor profile saved.');
    }
}
