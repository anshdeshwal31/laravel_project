<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="section-label mb-3">Listing details</p>
            <h2 class="text-3xl font-bold tracking-tight text-white dark:text-white light:text-slate-900">Opportunity Details</h2>
        </div>
    </x-slot>

    <div class="px-4 py-10 sm:px-6 lg:px-8 dark:bg-slate-950 light:bg-slate-50 min-h-screen transition-colors">
        <div class="mx-auto max-w-5xl">
            <div class="feature-card space-y-6 dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <span class="saas-pill dark:bg-white/10 dark:text-white light:bg-slate-100 light:text-slate-900">{{ $opportunity->industry }}</span>
                        <h1 class="mt-4 text-3xl font-bold tracking-tight text-white dark:text-white light:text-slate-900">{{ $opportunity->title }}</h1>
                        <p class="mt-2 text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">By {{ $opportunity->investor->name }}</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/5 px-4 py-3 text-right dark:border-white/10 dark:bg-white/5 light:border-slate-200 light:bg-slate-50">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-400 light:text-slate-600">Amount Range</p>
                        <p class="mt-1 text-2xl font-semibold text-white dark:text-white light:text-slate-900">${{ number_format($opportunity->min_amount, 0) }} - ${{ number_format($opportunity->max_amount, 0) }}</p>
                    </div>
                </div>

                <p class="text-slate-300 dark:text-slate-300 light:text-slate-700">{{ $opportunity->description }}</p>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <div class="rounded-3xl bg-white/5 p-4 dark:bg-white/5 light:bg-slate-100"><span class="block text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-500 light:text-slate-600">Investor</span><span class="mt-2 block text-white dark:text-white light:text-slate-900">{{ $opportunity->investor->name }}</span></div>
                    <div class="rounded-3xl bg-white/5 p-4 dark:bg-white/5 light:bg-slate-100"><span class="block text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-500 light:text-slate-600">Type</span><span class="mt-2 block text-white dark:text-white light:text-slate-900">{{ $opportunity->investor->investorProfile?->investor_type ?? 'N/A' }}</span></div>
                    <div class="rounded-3xl bg-white/5 p-4 dark:bg-white/5 light:bg-slate-100"><span class="block text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-500 light:text-slate-600">Stage</span><span class="mt-2 block text-white dark:text-white light:text-slate-900">{{ $opportunity->stage }}</span></div>
                    <div class="rounded-3xl bg-white/5 p-4 dark:bg-white/5 light:bg-slate-100"><span class="block text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-500 light:text-slate-600">Location</span><span class="mt-2 block text-white dark:text-white light:text-slate-900">{{ $opportunity->location ?: 'Any' }}</span></div>
                    <div class="rounded-3xl bg-white/5 p-4 dark:bg-white/5 light:bg-slate-100"><span class="block text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-500 light:text-slate-600">Status</span><span class="mt-2 block text-white dark:text-white light:text-slate-900">{{ $opportunity->is_active ? 'Active' : 'Inactive' }}</span></div>
                </div>

                @if (auth()->user()->role === 'startup')
                    <div class="border-t border-white/10 pt-6 dark:border-white/10 light:border-slate-200">
                        <h3 class="text-xl font-semibold text-white mb-3 dark:text-white light:text-slate-900">Send Funding Request</h3>
                        <form method="POST" action="{{ route('requests.store') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="investor_user_id" value="{{ $opportunity->user_id }}" />
                            <input type="hidden" name="funding_opportunity_id" value="{{ $opportunity->id }}" />

                            <div>
                                <x-input-label for="requested_amount" value="Requested Amount" class="saas-label dark:text-slate-400 light:text-slate-700" />
                                <x-text-input id="requested_amount" name="requested_amount" type="number" step="0.01" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" :value="old('requested_amount')" required />
                                <x-input-error :messages="$errors->get('requested_amount')" class="saas-error" />
                            </div>

                            <div>
                                <x-input-label for="message" value="Message" class="saas-label dark:text-slate-400 light:text-slate-700" />
                                <textarea id="message" name="message" rows="4" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900">{{ old('message') }}</textarea>
                                <x-input-error :messages="$errors->get('message')" class="saas-error" />
                            </div>

                            <x-primary-button>Submit Request</x-primary-button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
