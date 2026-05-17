<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-100" id="app-body" data-theme="dark">
        <div class="app-shell">
            <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/55 backdrop-blur-2xl light:border-slate-200 light:bg-white/85">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 transition hover:opacity-90">
                        <span class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-indigo-500 via-violet-500 to-cyan-400 text-white shadow-[0_15px_45px_-20px_rgba(99,102,241,0.8)]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M4 17.5V6.8c0-.4.2-.8.6-1l8.1-4.2c.3-.2.7-.2 1 0l5.7 3c.4.2.6.6.6 1v10.7c0 .4-.2.8-.6 1l-8.1 4.2c-.3.2-.7.2-1 0l-5.7-3c-.4-.2-.6-.6-.6-1Z" />
                                <path d="m8 10 4 2.2 4-2.2M12 12.2V18" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Startup Financing Platform</p>
                            <p class="text-sm font-semibold text-white dark:text-white light:text-slate-900">Larawell</p>
                        </div>
                    </a>

                    <div class="flex items-center gap-3">
                        <button id="theme-toggle" class="rounded-full border border-white/10 bg-white/5 px-3 py-2 text-sm font-medium text-white/90 transition hover:border-white/20 hover:bg-white/10 light:border-slate-200 light:bg-slate-100 light:text-slate-800 light:hover:bg-slate-200">
                            🌙
                        </button>

                        <nav class="hidden items-center gap-3 sm:flex">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="secondary-button">Go to Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="secondary-button">Log in</a>
                                <a href="{{ route('register') }}" class="primary-button">Create Account</a>
                            @endauth
                        @endif
                        </nav>
                    </div>
                </div>
            </header>

            <main>
                <section class="relative overflow-hidden">
                    <div class="absolute inset-0">
                        <div class="absolute left-1/2 top-0 h-[32rem] w-[32rem] -translate-x-1/2 rounded-full bg-indigo-500/15 blur-3xl"></div>
                        <div class="absolute right-0 top-40 h-64 w-64 rounded-full bg-cyan-400/10 blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 h-72 w-72 rounded-full bg-violet-500/10 blur-3xl"></div>
                    </div>

                    <div class="mx-auto grid max-w-7xl gap-14 px-4 py-16 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8 lg:py-24">
                        <div class="relative z-10 animate-fadeUp">
                            <div class="section-label mb-6">Startup funding, made simple</div>
                            <h1 class="max-w-3xl text-5xl font-extrabold tracking-tight text-white dark:text-white light:text-slate-900 sm:text-6xl lg:text-7xl">
                                A modern marketplace where startups and investors connect faster.
                            </h1>
                            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300 dark:text-slate-300 light:text-slate-700">
                                Larawell helps startups discover capital, investors publish opportunities, and teams manage requests, messaging, and approvals in one focused workspace.
                            </p>

                            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                                <a href="{{ route('register') }}" class="primary-button px-7 py-4 text-base">Start Free</a>
                                <a href="{{ route('login') }}" class="secondary-button px-7 py-4 text-base">View Your Workspace</a>
                                <a href="#how-it-works" class="secondary-button px-7 py-4 text-base">See How It Works</a>
                            </div>

                            <div class="mt-10 grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div class="metric-badge">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-400 light:text-slate-600">Who it serves</p>
                                    <p class="mt-2 text-xl font-semibold text-white dark:text-white light:text-slate-900">Startups, investors, admins</p>
                                </div>
                                <div class="metric-badge">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-400 light:text-slate-600">Core workflow</p>
                                    <p class="mt-2 text-xl font-semibold text-white dark:text-white light:text-slate-900">Discover, request, chat</p>
                                </div>
                                <div class="metric-badge">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-400 light:text-slate-600">Built for trust</p>
                                    <p class="mt-2 text-xl font-semibold text-white dark:text-white light:text-slate-900">Verified profiles and moderation</p>
                                </div>
                            </div>
                        </div>

                        <div class="relative z-10">
                            <div class="feature-card relative overflow-hidden p-6 sm:p-8 animate-fadeUp">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">Platform snapshot</p>
                                        <h2 class="text-2xl font-semibold text-white dark:text-white light:text-slate-900">Everything in one flow</h2>
                                    </div>
                                    <span class="saas-pill">Live</span>
                                </div>

                                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4 dark:border-white/10 dark:bg-white/5 light:border-slate-200 light:bg-slate-50">
                                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-400 light:text-slate-600">Funding listings</p>
                                        <p class="mt-3 text-lg font-semibold text-white dark:text-white light:text-slate-900">Publish opportunities or browse deals with filters.</p>
                                    </div>
                                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4 dark:border-white/10 dark:bg-white/5 light:border-slate-200 light:bg-slate-50">
                                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-400 light:text-slate-600">Request management</p>
                                        <p class="mt-3 text-lg font-semibold text-white dark:text-white light:text-slate-900">Track pending, accepted, and rejected requests.</p>
                                    </div>
                                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4 dark:border-white/10 dark:bg-white/5 light:border-slate-200 light:bg-slate-50">
                                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-400 light:text-slate-600">Messaging</p>
                                        <p class="mt-3 text-lg font-semibold text-white dark:text-white light:text-slate-900">Keep conversations attached to each deal thread.</p>
                                    </div>
                                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4 dark:border-white/10 dark:bg-white/5 light:border-slate-200 light:bg-slate-50">
                                        <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-400 light:text-slate-600">Verification</p>
                                        <p class="mt-3 text-lg font-semibold text-white dark:text-white light:text-slate-900">Protect the marketplace with role-based access.</p>
                                    </div>
                                </div>

                                <div class="mt-5 rounded-[1.75rem] border border-white/10 bg-slate-900/55 p-5 dark:border-white/10 dark:bg-slate-900/55 light:border-slate-200 light:bg-slate-50">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-slate-300 dark:text-slate-300 light:text-slate-700">Typical flow</span>
                                        <span class="text-cyan-300 dark:text-cyan-300 light:text-cyan-600">Fast, secure, organized</span>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-3">
                                        <span class="saas-pill">1. Discover</span>
                                        <span class="saas-pill">2. Request</span>
                                        <span class="saas-pill">3. Message</span>
                                        <span class="saas-pill">4. Close</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mx-auto max-w-7xl px-4 pb-10 sm:px-6 lg:px-8">
                    <div class="grid gap-5 md:grid-cols-3">
                        <div class="feature-card">
                            <div class="section-label mb-4">For startups</div>
                            <p class="text-base leading-7 text-slate-300 dark:text-slate-300 light:text-slate-700">Build trust with a verified profile, send a funding request, and keep every investor conversation attached to the opportunity.</p>
                        </div>
                        <div class="feature-card">
                            <div class="section-label mb-4">For investors</div>
                            <p class="text-base leading-7 text-slate-300 dark:text-slate-300 light:text-slate-700">Publish opportunities, review inbound requests, and move the best conversations forward without losing context.</p>
                        </div>
                        <div class="feature-card">
                            <div class="section-label mb-4">For admins</div>
                            <p class="text-base leading-7 text-slate-300 dark:text-slate-300 light:text-slate-700">Manage verification, remove spam, and keep the funding network clean and credible.</p>
                        </div>
                    </div>
                </section>

                <section id="how-it-works" class="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
                    <div class="mb-10 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <div class="section-label mb-4">What the platform is about</div>
                            <h2 class="text-3xl font-bold text-white dark:text-white light:text-slate-900 sm:text-4xl">Built to make funding conversations clear and trustworthy.</h2>
                        </div>
                        <p class="max-w-2xl text-slate-300 dark:text-slate-300 light:text-slate-700">
                            Larawell is a funding marketplace for startups and investors. Startups can create profiles and send funding requests. Investors can post opportunities, review inbound requests, and keep every conversation organized in one place.
                        </p>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ([
                            ['title' => 'For startups', 'copy' => 'Show your company, request capital, and follow every deal thread without losing context.'],
                            ['title' => 'For investors', 'copy' => 'Post opportunities, manage incoming requests, and respond in a structured workflow.'],
                            ['title' => 'For admins', 'copy' => 'Verify accounts, oversee the platform, and keep the marketplace clean and trusted.'],
                            ['title' => 'Clear CTAs', 'copy' => 'Visitors can register, log in, or jump straight into the workspace from the landing page.'],
                            ['title' => 'Simple process', 'copy' => 'Search, shortlist, message, and track status changes in a focused product flow.'],
                            ['title' => 'Polished experience', 'copy' => 'A premium fintech look and feel that is separate from the dashboard interface.'],
                        ] as $feature)
                            <div class="feature-card">
                                <p class="section-label">{{ $feature['title'] }}</p>
                                <p class="mt-4 text-base leading-7 text-slate-300 dark:text-slate-300 light:text-slate-700">{{ $feature['copy'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="mx-auto max-w-7xl px-4 pb-10 sm:px-6 lg:px-8">
                    <div class="rounded-[2rem] border border-white/10 bg-white/5 p-8 dark:border-white/10 dark:bg-white/5 light:border-slate-200 light:bg-white">
                        <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                            <div>
                                <div class="section-label mb-4">What users get</div>
                                <h2 class="text-3xl font-bold text-white dark:text-white light:text-slate-900">A clean workflow, not another noisy dashboard.</h2>
                                <p class="mt-4 text-slate-300 dark:text-slate-300 light:text-slate-700">Larawell keeps the path from discovery to conversation to decision in one focused product surface.</p>
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="rounded-3xl border border-white/10 bg-slate-900/50 p-5 dark:border-white/10 dark:bg-slate-900/50 light:border-slate-200 light:bg-slate-50">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-400 light:text-slate-600">Search</p>
                                    <p class="mt-2 text-lg font-semibold text-white dark:text-white light:text-slate-900">Find the right match quickly</p>
                                </div>
                                <div class="rounded-3xl border border-white/10 bg-slate-900/50 p-5 dark:border-white/10 dark:bg-slate-900/50 light:border-slate-200 light:bg-slate-50">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-400 light:text-slate-600">Chat</p>
                                    <p class="mt-2 text-lg font-semibold text-white dark:text-white light:text-slate-900">Keep funding conversations organized</p>
                                </div>
                                <div class="rounded-3xl border border-white/10 bg-slate-900/50 p-5 dark:border-white/10 dark:bg-slate-900/50 light:border-slate-200 light:bg-slate-50">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-400 light:text-slate-600">Track</p>
                                    <p class="mt-2 text-lg font-semibold text-white dark:text-white light:text-slate-900">See request status at a glance</p>
                                </div>
                                <div class="rounded-3xl border border-white/10 bg-slate-900/50 p-5 dark:border-white/10 dark:bg-slate-900/50 light:border-slate-200 light:bg-slate-50">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-400 light:text-slate-600">Verify</p>
                                    <p class="mt-2 text-lg font-semibold text-white dark:text-white light:text-slate-900">Build trust with role-based access</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mx-auto max-w-7xl px-4 pb-24 sm:px-6 lg:px-8">
                    <div class="rounded-[2rem] border border-white/10 bg-gradient-to-br from-indigo-500/15 via-violet-500/10 to-cyan-500/15 p-8 shadow-[0_20px_70px_-30px_rgba(15,23,42,0.5)] dark:border-white/10 light:border-slate-200 light:bg-white">
                        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <div class="section-label mb-4">Ready to begin</div>
                                <h2 class="text-3xl font-bold text-white dark:text-white light:text-slate-900">Create your account and start the right funding conversation.</h2>
                                <p class="mt-3 max-w-2xl text-slate-300 dark:text-slate-300 light:text-slate-700">Use one platform for discovery, messaging, requests, and verification. Whether you are raising capital or investing, Larawell keeps the process clean and professional.</p>
                            </div>
                            <div class="flex flex-col gap-3 sm:flex-row lg:shrink-0">
                                <a href="{{ route('register') }}" class="primary-button px-7 py-4 text-base">Create Account</a>
                                <a href="{{ route('login') }}" class="secondary-button px-7 py-4 text-base">Log in</a>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>

        <script>
            (function () {
                function applyTheme(theme) {
                    const htmlElement = document.documentElement;
                    const body = document.getElementById('app-body');
                    const themeButton = document.getElementById('theme-toggle');

                    if (body) {
                        body.setAttribute('data-theme', theme);
                    }

                    if (htmlElement) {
                        htmlElement.setAttribute('data-theme', theme);
                        htmlElement.classList.toggle('dark', theme === 'dark');
                        htmlElement.classList.toggle('light', theme === 'light');
                    }

                    if (themeButton) {
                        themeButton.textContent = theme === 'dark' ? '☀️' : '🌙';
                    }

                    localStorage.setItem('theme', theme);
                }

                function initTheme() {
                    const savedTheme = localStorage.getItem('theme') || 'dark';
                    applyTheme(savedTheme);

                    const themeButton = document.getElementById('theme-toggle');
                    if (themeButton && !themeButton.dataset.bound) {
                        themeButton.dataset.bound = 'true';
                        themeButton.addEventListener('click', function () {
                            const currentTheme = localStorage.getItem('theme') || 'dark';
                            applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
                        });
                    }
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initTheme, { once: true });
                } else {
                    initTheme();
                }
            })();
        </script>
    </body>
</html>
