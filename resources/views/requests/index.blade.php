<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="section-label mb-3">Workflow</p>
            <h2 class="text-3xl font-bold tracking-tight text-white dark:text-white light:text-slate-900">Funding Requests</h2>
            <p class="mt-2 text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">Track request status and continue discussions in one central thread.</p>
        </div>
    </x-slot>

    <div class="px-4 py-10 sm:px-6 lg:px-8 dark:bg-slate-950 light:bg-slate-50 min-h-screen transition-colors">
        <div class="mx-auto max-w-7xl">
            @if (session('status'))
                <div class="mb-4 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-emerald-200 dark:border-emerald-400/20 dark:bg-emerald-500/10 dark:text-emerald-200 light:border-emerald-200 light:bg-emerald-100 light:text-emerald-900">{{ session('status') }}</div>
            @endif

            <div class="saas-card overflow-hidden p-0 dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                <div class="overflow-x-auto">
                    <table class="saas-table dark:text-white light:text-slate-900">
                        <thead>
                            <tr class="text-left border-b dark:border-white/10 light:border-slate-200 dark:bg-white/5 light:bg-slate-50">
                                <th class="dark:text-slate-400 light:text-slate-600">Startup</th>
                                <th class="dark:text-slate-400 light:text-slate-600">Investor</th>
                                <th class="dark:text-slate-400 light:text-slate-600">Opportunity</th>
                                <th class="dark:text-slate-400 light:text-slate-600">Amount</th>
                                <th class="dark:text-slate-400 light:text-slate-600">Status</th>
                                <th class="dark:text-slate-400 light:text-slate-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests as $item)
                                <tr class="transition dark:hover:bg-white/5 light:hover:bg-slate-50">
                                    <td>{{ $item->startup?->name ?? '-' }}</td>
                                    <td>{{ $item->investor?->name ?? '-' }}</td>
                                    <td>{{ $item->opportunity?->title ?? 'Direct request' }}</td>
                                    <td>${{ number_format($item->requested_amount, 2) }}</td>
                                    <td><span class="saas-pill capitalize dark:bg-white/10 dark:text-white light:bg-slate-100 light:text-slate-900">{{ $item->status }}</span></td>
                                        <td class="space-x-3">
                                            <a href="{{ route('messages.show', $item) }}" class="text-cyan-300 dark:text-cyan-300 dark:hover:text-cyan-200 light:text-cyan-600 light:hover:text-cyan-700 transition">Messages</a>
                                        @if (auth()->user()->role === 'investor' && auth()->id() === $item->investor_user_id)
                                            <form method="POST" action="{{ route('requests.status', $item) }}" class="mt-3 inline-flex items-center gap-2 lg:mt-0">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="saas-input max-w-[150px] py-2 dark:bg-white/10 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900">
                                                    @foreach (['pending', 'accepted', 'rejected'] as $status)
                                                        <option value="{{ $status }}" @selected($item->status === $status)>{{ ucfirst($status) }}</option>
                                                    @endforeach
                                                </select>
                                                <button class="secondary-button px-4 py-2 text-xs">Update</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-8 text-slate-400 dark:text-slate-400 light:text-slate-600">No requests found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-white/10 dark:border-white/10 light:border-slate-200 p-4">{{ $requests->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
