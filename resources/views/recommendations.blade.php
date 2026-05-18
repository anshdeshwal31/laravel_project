<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="section-label mb-3">Smart Matching</p>
                <h2 class="text-3xl font-bold tracking-tight text-white dark:text-white light:text-slate-900">
                    Investor Recommendations
                </h2>
                <p class="mt-2 text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">
                    @if($startup)
                        Showing the best-matched investors for <span class="font-semibold text-cyan-300 dark:text-cyan-300 light:text-cyan-600">{{ $startup->startup_name }}</span>
                    @else
                        Your AI-powered investor matches based on semantic fit, stage, ticket size, geography, and activity.
                    @endif
                </p>
            </div>
            @if($startup)
                <div class="hidden gap-3 lg:flex">
                    <span class="saas-pill">
                        <span class="mr-2 h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Semantic · Stage · Ticket · Geo · Activity
                    </span>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- ── Error / empty states ─────────────────────────────────────── --}}
            @if($error)
                <div class="rounded-2xl border border-amber-400/20 bg-amber-500/10 px-5 py-4 text-amber-200 dark:border-amber-400/20 dark:bg-amber-500/10 dark:text-amber-200 light:border-amber-200 light:bg-amber-50 light:text-amber-800">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium">{{ $error }}</p>
                    </div>
                </div>
            @endif

            {{-- ── Startup profile summary ──────────────────────────────────── --}}
            @if($startup)
                <div class="saas-card dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="grid h-12 w-12 place-items-center rounded-2xl bg-gradient-to-br from-cyan-400/30 to-violet-500/30 text-2xl">🚀</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-400 dark:text-slate-400 light:text-slate-600">Your startup</p>
                            <p class="text-lg font-semibold text-white dark:text-white light:text-slate-900">{{ $startup->startup_name }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="saas-pill">{{ $startup->industry }}</span>
                            <span class="saas-pill">{{ $startup->stage }}</span>
                            <span class="saas-pill">{{ $startup->location }}</span>
                            <span class="saas-pill">${{ number_format($startup->funding_requirement) }}</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── Recommendations table ────────────────────────────────────── --}}
            @if(!empty($recommendations))
                <div class="saas-card dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-white dark:text-white light:text-slate-900">Top Investor Matches</h3>
                            <p class="text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">Ranked by composite match score</p>
                        </div>
                        <span class="saas-pill">{{ count($recommendations) }} matches</span>
                    </div>

                    {{-- Score bars legend --}}
                    <div class="mb-6 flex flex-wrap gap-4 text-xs text-slate-400 dark:text-slate-400 light:text-slate-600">
                        @foreach(['semantic' => 'Semantic (35%)', 'stage' => 'Stage (25%)', 'ticket' => 'Ticket (20%)', 'geo' => 'Geography (10%)', 'activity' => 'Activity (10%)'] as $key => $label)
                            <div class="flex items-center gap-1.5">
                                <span class="h-2 w-2 rounded-full
                                    @if($key === 'semantic') bg-violet-400
                                    @elseif($key === 'stage') bg-cyan-400
                                    @elseif($key === 'ticket') bg-emerald-400
                                    @elseif($key === 'geo') bg-amber-400
                                    @else bg-rose-400 @endif"></span>
                                {{ $label }}
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-4">
                        @foreach($recommendations as $rank => $rec)
                            @php $score = $rec['score'] * 100; $bd = $rec['breakdown']; @endphp
                            <div class="group rounded-2xl border border-white/10 bg-white/3 p-5 transition-all duration-300 hover:border-white/20 hover:bg-white/5 dark:border-white/10 dark:bg-white/3 dark:hover:border-white/20 light:border-slate-200 light:bg-slate-50 light:hover:bg-white">
                                <div class="flex flex-wrap items-center gap-4">

                                    {{-- Rank badge --}}
                                    <div class="grid h-10 w-10 flex-shrink-0 place-items-center rounded-xl
                                        {{ $rank === 0 ? 'bg-gradient-to-br from-amber-400 to-orange-500 text-white font-bold' : 'bg-white/10 text-slate-300' }}
                                        text-sm font-semibold">
                                        #{{ $rank + 1 }}
                                    </div>

                                    {{-- Investor info --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-white dark:text-white light:text-slate-900">{{ $rec['investor_name'] }}</p>
                                        <div class="mt-1 flex flex-wrap gap-2">
                                            <span class="saas-pill text-xs">{{ $rec['investor_type'] }}</span>
                                            <span class="saas-pill text-xs">{{ $rec['investment_range'] }}</span>
                                            <span class="saas-pill text-xs">📍 {{ $rec['location'] }}</span>
                                            @if($rec['industries'])
                                                <span class="saas-pill text-xs">{{ $rec['industries'] }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Overall score --}}
                                    <div class="text-right flex-shrink-0">
                                        <p class="text-2xl font-bold
                                            {{ $score >= 70 ? 'text-emerald-400' : ($score >= 45 ? 'text-amber-400' : 'text-slate-400') }}">
                                            {{ number_format($score, 1) }}<span class="text-sm font-normal opacity-60">%</span>
                                        </p>
                                        <p class="text-xs text-slate-500">match score</p>
                                    </div>
                                </div>

                                {{-- Score breakdown bars --}}
                                <div class="mt-4 grid grid-cols-5 gap-2">
                                    @foreach([
                                        ['semantic', 'violet', $bd['semantic']],
                                        ['stage', 'cyan', $bd['stage']],
                                        ['ticket', 'emerald', $bd['ticket']],
                                        ['geo', 'amber', $bd['geo']],
                                        ['activity', 'rose', $bd['activity']],
                                    ] as [$key, $color, $val])
                                        @php $pct = round($val * 100); @endphp
                                        <div>
                                            <div class="mb-1 flex justify-between">
                                                <span class="text-xs capitalize text-slate-500">{{ $key }}</span>
                                                <span class="text-xs font-medium text-slate-300 dark:text-slate-300 light:text-slate-700">{{ $pct }}%</span>
                                            </div>
                                            <div class="h-1.5 overflow-hidden rounded-full bg-white/10 dark:bg-white/10 light:bg-slate-200">
                                                <div class="h-full rounded-full bg-{{ $color }}-400 transition-all duration-700"
                                                     style="width: {{ $pct }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            @elseif(!$error)
                <div class="saas-card flex flex-col items-center gap-4 py-16 text-center dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                    <span class="text-5xl">🔍</span>
                    <h3 class="text-lg font-semibold text-white dark:text-white light:text-slate-900">No recommendations yet</h3>
                    <p class="max-w-sm text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">
                        Complete your startup profile and ensure at least one investor is registered on the platform.
                    </p>
                    <a href="{{ route('startup.profile.edit') }}" class="primary-button">Edit Startup Profile</a>
                </div>
            @endif

            {{-- ── Scoring formula info ─────────────────────────────────────── --}}
            <div class="rounded-[1.75rem] border border-white/10 bg-gradient-to-br from-violet-500/10 via-indigo-500/8 to-cyan-500/10 p-6 dark:border-white/10 light:border-violet-200 light:from-violet-50 light:to-cyan-50">
                <p class="mb-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-400 light:text-slate-600">Scoring formula</p>
                <p class="font-mono text-sm text-cyan-300 dark:text-cyan-300 light:text-cyan-700">
                    Score = 0.35·S<sub>semantic</sub> + 0.25·S<sub>stage</sub> + 0.20·S<sub>ticket</sub> + 0.10·S<sub>geo</sub> + 0.10·S<sub>activity</sub>
                </p>
                <p class="mt-3 text-xs leading-6 text-slate-400 dark:text-slate-400 light:text-slate-600">
                    Semantic: cosine similarity of OpenAI embeddings ·
                    Stage: exact=100%, adjacent=50% ·
                    Ticket: distance-decay from funding ask vs. investor range ·
                    Geo: exact country=100%, same region=70% ·
                    Activity: exp(−0.01 × days since last active)
                </p>
            </div>

        </div>
    </div>
</x-app-layout>
