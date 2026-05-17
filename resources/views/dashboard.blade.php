<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="section-label mb-3">Overview</p>
                <h2 class="text-3xl font-bold tracking-tight text-white dark:text-white light:text-slate-900">Funding Platform Dashboard</h2>
                <p class="mt-2 text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">Monitor funding activity, request status, and platform trust in one place.</p>
            </div>
            <div class="hidden gap-3 lg:flex">
                <a href="{{ route('opportunities.index') }}" class="secondary-button">Browse Listings</a>
                @if (auth()->user()->role === 'investor')
                    <a href="{{ route('opportunities.create') }}" class="primary-button">Post Opportunity</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-8">
            @if (session('status'))
                <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-emerald-200 dark:border-emerald-400/20 dark:bg-emerald-500/10 dark:text-emerald-200 light:border-emerald-200 light:bg-emerald-100 light:text-emerald-900">{{ session('status') }}</div>
            @endif

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($stats as $label => $value)
                    <div class="metric-badge dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-slate-100 light:border-slate-200 light:text-slate-900">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-400 light:text-slate-600">{{ str_replace('_', ' ', $label) }}</p>
                        <p class="mt-2 text-3xl font-semibold text-white dark:text-white light:text-slate-900">{{ $value }}</p>
                    </div>
                @endforeach
            </div>

            <div class="grid gap-6 xl:grid-cols-[280px_minmax(0,1fr)]">
                <aside class="saas-card dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200 space-y-6">
                    <div>
                        <p class="section-label dark:text-slate-300 light:text-slate-900">Quick Actions</p>
                        <div class="mt-4 space-y-3">
                            <a href="{{ route('opportunities.index') }}" class="saas-sidebar-link dark:text-cyan-300 dark:hover:text-cyan-200 light:text-cyan-600 light:hover:text-cyan-700">Browse funding listings</a>
                            <a href="{{ route('requests.index') }}" class="saas-sidebar-link dark:text-cyan-300 dark:hover:text-cyan-200 light:text-cyan-600 light:hover:text-cyan-700">Review requests</a>
                            @if (auth()->user()->role === 'startup')
                                <a href="{{ route('startup.profile.edit') }}" class="saas-sidebar-link dark:text-cyan-300 dark:hover:text-cyan-200 light:text-cyan-600 light:hover:text-cyan-700">Edit startup profile</a>
                            @endif
                            @if (auth()->user()->role === 'investor')
                                <a href="{{ route('investor.profile.edit') }}" class="saas-sidebar-link dark:text-cyan-300 dark:hover:text-cyan-200 light:text-cyan-600 light:hover:text-cyan-700">Edit investor profile</a>
                            @endif
                            @if (auth()->user()->role === 'admin')
                                <a href="{{ route('admin.index') }}" class="saas-sidebar-link dark:text-cyan-300 dark:hover:text-cyan-200 light:text-cyan-600 light:hover:text-cyan-700">Moderate users</a>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-[1.75rem] border border-white/10 bg-gradient-to-br from-indigo-500/15 via-violet-500/10 to-cyan-500/10 p-5 dark:border-white/10 dark:bg-gradient-to-br dark:from-indigo-500/15 dark:via-violet-500/10 dark:to-cyan-500/10 light:border-indigo-200 light:bg-gradient-to-br light:from-indigo-100 light:via-violet-100 light:to-cyan-100">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-400 light:text-slate-600">Platform pulse</p>
                        <p class="mt-2 text-2xl font-semibold text-white dark:text-white light:text-slate-900">Fast, verified deal flow</p>
                        <p class="mt-3 text-sm leading-6 text-slate-300 dark:text-slate-300 light:text-slate-700">Use the dashboard to watch requests move from pending to accepted while keeping conversations attached to each deal.</p>
                    </div>
                </aside>

                <div class="space-y-6">
                    <div class="saas-card dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-white dark:text-white light:text-slate-900">Recent Funding Requests</h3>
                                <p class="text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">Latest activity across your platform</p>
                            </div>
                            <span class="saas-pill dark:bg-white/10 dark:text-white light:bg-slate-100 light:text-slate-900">Updated live</span>
                        </div>

                        @if ($recentRequests->isEmpty())
                            <p class="text-slate-400 dark:text-slate-400 light:text-slate-600">No requests yet.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="saas-table dark:text-white light:text-slate-900">
                                    <thead>
                                        <tr class="dark:border-white/10 light:border-slate-200">
                                            <th class="dark:text-slate-400 light:text-slate-600">Startup</th>
                                            <th class="dark:text-slate-400 light:text-slate-600">Investor</th>
                                            <th class="dark:text-slate-400 light:text-slate-600">Amount</th>
                                            <th class="dark:text-slate-400 light:text-slate-600">Status</th>
                                            <th class="dark:text-slate-400 light:text-slate-600">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentRequests as $item)
                                            <tr class="transition dark:hover:bg-white/5 light:hover:bg-slate-50">
                                                <td>{{ $item->startup?->name ?? '-' }}</td>
                                                <td>{{ $item->investor?->name ?? '-' }}</td>
                                                <td>${{ number_format($item->requested_amount, 2) }}</td>
                                                <td><span class="saas-pill capitalize dark:bg-white/10 dark:text-white light:bg-slate-100 light:text-slate-900">{{ $item->status }}</span></td>
                                                <td><a class="text-cyan-300 dark:text-cyan-300 dark:hover:text-cyan-200 light:text-cyan-600 light:hover:text-cyan-700 transition" href="{{ route('messages.show', $item) }}">Open Thread</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
                </div>
        </div>
        </div>
    </div>
</x-app-layout>
