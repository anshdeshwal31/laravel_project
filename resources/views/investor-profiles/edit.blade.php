<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="section-label mb-3">Profile</p>
            <h2 class="text-3xl font-bold tracking-tight text-white">Investor Profile</h2>
            <p class="mt-2 text-sm text-slate-400">Define your capital range, sector focus, and preferred geography.</p>
        </div>
    </x-slot>

    <div class="px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">
            @if (session('status'))
                <div class="mb-4 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-emerald-200">{{ session('status') }}</div>
            @endif

            <div class="saas-card">
                <form method="POST" action="{{ route('investor.profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="investor_type" value="Investor Type" class="saas-label" />
                        <select id="investor_type" name="investor_type" class="saas-input" required>
                            @php($investorType = old('investor_type', $profile?->investor_type))
                            @foreach (['angel', 'vc', 'bank', 'crowdfunding'] as $type)
                                <option value="{{ $type }}" @selected($investorType === $type)>{{ strtoupper($type) }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('investor_type')" class="saas-error" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="investment_min" value="Investment Min" class="saas-label" />
                            <x-text-input id="investment_min" name="investment_min" type="number" step="0.01" class="saas-input" :value="old('investment_min', $profile?->investment_min)" required />
                            <x-input-error :messages="$errors->get('investment_min')" class="saas-error" />
                        </div>
                        <div>
                            <x-input-label for="investment_max" value="Investment Max" class="saas-label" />
                            <x-text-input id="investment_max" name="investment_max" type="number" step="0.01" class="saas-input" :value="old('investment_max', $profile?->investment_max)" required />
                            <x-input-error :messages="$errors->get('investment_max')" class="saas-error" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="preferred_industries" value="Preferred Industries (comma separated)" class="saas-label" />
                        <x-text-input id="preferred_industries" name="preferred_industries" type="text" class="saas-input" :value="old('preferred_industries', is_array($profile?->preferred_industries) ? implode(', ', $profile->preferred_industries) : '')" />
                        <x-input-error :messages="$errors->get('preferred_industries')" class="saas-error" />
                    </div>

                    <div>
                        <x-input-label for="location_preference" value="Location Preference" class="saas-label" />
                        <x-text-input id="location_preference" name="location_preference" type="text" class="saas-input" :value="old('location_preference', $profile?->location_preference)" />
                        <x-input-error :messages="$errors->get('location_preference')" class="saas-error" />
                    </div>

                    <x-primary-button>Save Investor Profile</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
