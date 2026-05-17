<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="section-label mb-3">Marketplace</p>
                <h2 class="text-3xl font-bold tracking-tight text-white dark:text-white light:text-slate-900">Funding Listings</h2>
                <p class="mt-2 text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">Search verified capital opportunities with a fast, polished discovery flow.</p>
            </div>
            @if (auth()->user()->role === 'investor')
                <a href="{{ route('opportunities.create') }}" class="primary-button self-start">Post Opportunity</a>
            @endif
        </div>
    </x-slot>

    <div class="px-4 py-10 sm:px-6 lg:px-8 dark:bg-slate-950 light:bg-slate-50 min-h-screen transition-colors">
        <div class="mx-auto max-w-7xl space-y-6">
            @if (session('status'))
                <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-emerald-200 dark:border-emerald-400/20 dark:bg-emerald-500/10 dark:text-emerald-200 light:border-emerald-200 light:bg-emerald-100 light:text-emerald-900">{{ session('status') }}</div>
            @endif

            <div class="saas-card dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                <form method="GET" action="{{ route('opportunities.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-5">
                    <x-text-input type="text" name="industry" placeholder="Industry" :value="$filters['industry'] ?? ''" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" />
                    <x-text-input type="text" name="stage" placeholder="Stage" :value="$filters['stage'] ?? ''" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" />
                    <x-text-input type="text" name="location" placeholder="Location" :value="$filters['location'] ?? ''" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" />
                    <x-text-input type="number" step="0.01" name="min_amount" placeholder="Min Amount" :value="$filters['min_amount'] ?? ''" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" />
                    <x-text-input type="number" step="0.01" name="max_amount" placeholder="Max Amount" :value="$filters['max_amount'] ?? ''" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" />
                    <div class="flex gap-3 md:col-span-5">
                        <x-primary-button>Search</x-primary-button>
                        <a href="{{ route('opportunities.index') }}" class="secondary-button">Reset</a>
                    </div>
                </form>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($opportunities as $item)
                    <div class="feature-card group dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-semibold text-white dark:text-white light:text-slate-900">{{ $item->title }}</h3>
                                <p class="mt-1 text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">By {{ $item->investor->name }}</p>
                            </div>
                            <span class="saas-pill dark:bg-white/10 dark:text-white light:bg-slate-100 light:text-slate-900">{{ $item->stage }}</span>
                        </div>

                        <p class="mt-4 text-sm leading-7 text-slate-300 dark:text-slate-300 light:text-slate-700">{{ \Illuminate\Support\Str::limit($item->description, 120) }}</p>

                        <div class="mt-5 grid grid-cols-2 gap-3 text-sm text-slate-300 dark:text-slate-300 light:text-slate-700">
                            <div class="rounded-2xl bg-white/5 p-3 dark:bg-white/5 light:bg-slate-100"><span class="block text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-500 light:text-slate-600">Industry</span>{{ $item->industry }}</div>
                            <div class="rounded-2xl bg-white/5 p-3 dark:bg-white/5 light:bg-slate-100"><span class="block text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-500 light:text-slate-600">Location</span>{{ $item->location ?: 'Any' }}</div>
                            <div class="rounded-2xl bg-white/5 p-3 col-span-2 dark:bg-white/5 light:bg-slate-100"><span class="block text-xs uppercase tracking-[0.18em] text-slate-500 dark:text-slate-500 light:text-slate-600">Funding Range</span>${{ number_format($item->min_amount, 0) }} - ${{ number_format($item->max_amount, 0) }}</div>
                        </div>

                        <a href="{{ route('opportunities.show', $item) }}" class="mt-5 inline-flex text-sm font-medium text-cyan-300 dark:text-cyan-300 dark:hover:text-cyan-200 light:text-cyan-600 light:hover:text-cyan-700 transition">View Details</a>
                    </div>
                @empty
                    <div class="md:col-span-2 xl:col-span-3 saas-card text-slate-400 dark:bg-white/5 dark:border-white/10 dark:text-slate-400 light:bg-white light:border-slate-200 light:text-slate-600">No opportunities found.</div>
                @endforelse
            </div>

            <div class="rounded-3xl border border-white/10 bg-white/5 p-4 backdrop-blur-xl dark:border-white/10 dark:bg-white/5 light:border-slate-200 light:bg-slate-50">
                {{ $opportunities->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
