<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-100 antialiased">
        <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/55 backdrop-blur-2xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ url('/') }}" class="flex items-center gap-3 hover:opacity-80 transition">
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

                <nav class="hidden items-center gap-2 sm:flex">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="secondary-button">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="secondary-button">Log in</a>
                            <a href="{{ route('register') }}" class="primary-button">Get Started</a>
                        @endauth
                    @endif
                </nav>

                <div class="flex sm:hidden">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-xs font-medium text-cyan-300 hover:text-cyan-200">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-xs font-medium text-cyan-300 hover:text-cyan-200">Log in</a>
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <div class="app-shell flex min-h-screen items-stretch">
            <div class="hidden w-full lg:flex lg:w-[48%] xl:w-[52%] items-center justify-center px-10 py-10">
                <div class="feature-card relative max-w-xl p-10">
                    <div class="section-label mb-6">Startup Financing Platform</div>
                    <h1 class="text-5xl font-semibold leading-tight tracking-tight text-white">Turn funding discovery into a premium workflow.</h1>
                    <p class="mt-5 max-w-lg text-base leading-7 text-slate-300">A unified fintech SaaS experience for startups, investors, and admins with verified profiles, funding requests, and streamlined communication.</p>

                    <div class="mt-8 grid grid-cols-3 gap-4">
                        <div class="metric-badge">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Startups</p>
                            <p class="mt-2 text-2xl font-semibold text-white">120+</p>
                        </div>
                        <div class="metric-badge">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Investors</p>
                            <p class="mt-2 text-2xl font-semibold text-white">45+</p>
                        </div>
                        <div class="metric-badge">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Deals</p>
                            <p class="mt-2 text-2xl font-semibold text-white">8.4M</p>
                        </div>
                    </div>

                    <div class="mt-8 rounded-[1.75rem] border border-white/10 bg-slate-900/50 p-5">
                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <p class="text-sm text-slate-400">Funding pipeline</p>
                                <p class="text-lg font-semibold text-white">Verified opportunities</p>
                            </div>
                            <span class="saas-pill">Live</span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between rounded-2xl bg-white/5 px-4 py-3">
                                <span class="text-sm text-slate-200">Seed round • Healthtech</span>
                                <span class="text-sm text-cyan-300">Pending</span>
                            </div>
                            <div class="flex items-center justify-between rounded-2xl bg-white/5 px-4 py-3">
                                <span class="text-sm text-slate-200">Series A • SaaS</span>
                                <span class="text-sm text-emerald-300">Accepted</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex w-full items-center justify-center px-4 py-10 sm:px-6 lg:w-[52%] lg:px-10">
                <div class="w-full max-w-md">
                    <div class="mb-6 flex items-center gap-3 lg:hidden">
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
                    </div>

                    <div class="shell-surface rounded-[2rem] p-6 sm:p-8 lg:p-10">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
