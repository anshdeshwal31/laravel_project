<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="section-label mb-3">Operations</p>
            <h2 class="text-3xl font-bold tracking-tight text-white">Admin Panel</h2>
            <p class="mt-2 text-sm text-slate-400">Verify users, remove spam, and keep the marketplace trustworthy.</p>
        </div>
    </x-slot>

    <div class="px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-4">
            @if (session('status'))
                <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-emerald-200">{{ session('status') }}</div>
            @endif

            <div class="saas-card">
                <form method="GET" action="{{ route('admin.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <select name="role" class="saas-input sm:max-w-[220px]">
                        <option value="">All roles</option>
                        @foreach (['startup', 'investor', 'admin'] as $role)
                            <option value="{{ $role }}" @selected($roleFilter === $role)>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                    <x-primary-button>Filter</x-primary-button>
                </form>
            </div>

            <div class="saas-card overflow-hidden p-0">
                <div class="overflow-x-auto">
                    <table class="saas-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Email Verified</th>
                                <th>Profile Verified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="transition hover:bg-white/5">
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="saas-pill capitalize">{{ $user->role }}</span></td>
                                    <td>{{ $user->email_verified_at ? 'Yes' : 'No' }}</td>
                                    <td>{{ $user->is_verified ? 'Yes' : 'No' }}</td>
                                    <td class="flex flex-wrap gap-2 py-4">
                                        @if (!$user->is_verified)
                                            <form method="POST" action="{{ route('admin.users.verify', $user) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="secondary-button px-4 py-2 text-xs">Verify</button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Remove this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="inline-flex items-center justify-center rounded-full border border-rose-400/30 bg-rose-500/15 px-4 py-2 text-xs font-semibold text-rose-200 transition hover:bg-rose-500/20 hover:text-white">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-white/10 p-4">{{ $users->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
