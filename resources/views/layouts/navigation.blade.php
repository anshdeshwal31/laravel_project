<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/55 backdrop-blur-2xl light:border-slate-200 light:bg-white/80">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <span class="grid h-10 w-10 place-items-center rounded-2xl bg-gradient-to-br from-indigo-500 via-violet-500 to-cyan-400 text-white shadow-[0_15px_45px_-20px_rgba(99,102,241,0.8)]">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M4 17.5V6.8c0-.4.2-.8.6-1l8.1-4.2c.3-.2.7-.2 1 0l5.7 3c.4.2.6.6.6 1v10.7c0 .4-.2.8-.6 1l-8.1 4.2c-.3.2-.7.2-1 0l-5.7-3c-.4-.2-.6-.6-.6-1Z" />
                            <path d="m8 10 4 2.2 4-2.2M12 12.2V18" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Startup Financing Platform</p>
                        <p class="text-sm font-semibold text-white">Larawell</p>
                    </div>
                </a>

                <div class="hidden items-center gap-2 lg:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                    <x-nav-link :href="route('opportunities.index')" :active="request()->routeIs('opportunities.*')">Funding Listings</x-nav-link>
                    <x-nav-link :href="route('requests.index')" :active="request()->routeIs('requests.*') || request()->routeIs('messages.*')">Requests</x-nav-link>
                    {{-- AI Assistant — all roles --}}
                    <x-nav-link :href="route('ai-assistant.index')" :active="request()->routeIs('ai-assistant.*')">
                        <span class="flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            AI Assistant
                        </span>
                    </x-nav-link>
                    @if (Auth::user()->role === 'startup')
                        <x-nav-link :href="route('startup.profile.edit')" :active="request()->routeIs('startup.profile.*')">Startup Profile</x-nav-link>
                        <x-nav-link :href="route('recommendations.index')" :active="request()->routeIs('recommendations.*')">
                            <span class="flex items-center gap-1.5">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                Recommendations
                            </span>
                        </x-nav-link>
                    @endif
                    @if (Auth::user()->role === 'investor')
                        <x-nav-link :href="route('investor.profile.edit')" :active="request()->routeIs('investor.profile.*')">Investor Profile</x-nav-link>
                    @endif
                    @if (Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.*')">Admin</x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden items-center gap-3 lg:flex">
                <button id="theme-toggle" class="rounded-full border border-white/10 bg-white/5 px-3 py-2 text-sm font-medium text-white/90 transition hover:border-white/20 hover:bg-white/10">
                    🌙
                </button>
                <span class="saas-pill">{{ Auth::user()->role }}</span>
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 rounded-full border border-white/10 bg-white/5 px-3 py-2 text-sm font-medium text-white/90 transition hover:border-white/20 hover:bg-white/10">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-cyan-400/30 to-violet-500/30 text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            <span class="hidden xl:block">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-slate-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 10.97l3.71-3.74a.75.75 0 1 1 1.06 1.06l-4.24 4.28a.75.75 0 0 1-1.06 0L5.21 8.29a.75.75 0 0 1 .02-1.08Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <button @click="open = ! open" class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 p-3 text-slate-200 transition hover:bg-white/10 lg:hidden" aria-label="Toggle navigation">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-white/10 bg-slate-950/90 lg:hidden light:border-slate-200 light:bg-white">
        <div class="mx-auto max-w-7xl space-y-2 px-4 py-4 sm:px-6">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('opportunities.index')" :active="request()->routeIs('opportunities.*')">Funding Listings</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('requests.index')" :active="request()->routeIs('requests.*') || request()->routeIs('messages.*')">Requests</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('ai-assistant.index')" :active="request()->routeIs('ai-assistant.*')">🤖 AI Assistant</x-responsive-nav-link>
            @if (Auth::user()->role === 'startup')
                <x-responsive-nav-link :href="route('startup.profile.edit')" :active="request()->routeIs('startup.profile.*')">Startup Profile</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('recommendations.index')" :active="request()->routeIs('recommendations.*')">⭐ Recommendations</x-responsive-nav-link>
            @endif
            @if (Auth::user()->role === 'investor')
                <x-responsive-nav-link :href="route('investor.profile.edit')" :active="request()->routeIs('investor.profile.*')">Investor Profile</x-responsive-nav-link>
            @endif
            @if (Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.*')">Admin</x-responsive-nav-link>
            @endif
            <div class="mt-4 rounded-3xl border border-white/10 bg-white/5 p-4">
                <div class="text-sm font-semibold text-white">{{ Auth::user()->name }}</div>
                <div class="text-sm text-slate-400">{{ Auth::user()->email }}</div>
                <div class="mt-4 flex gap-3">
                    <a class="secondary-button flex-1" href="{{ route('profile.edit') }}">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button class="primary-button w-full" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
