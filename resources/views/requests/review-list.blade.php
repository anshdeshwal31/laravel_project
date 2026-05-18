<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="section-label mb-3">Venture Intelligence Engine</p>
            <h2 class="text-3xl font-bold tracking-tight text-white dark:text-white light:text-slate-900">AI Startup Audits</h2>
            <p class="mt-2 text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">
                Track, audit, and evaluate incoming funding requests using advanced LLM-powered pitch deck vector analysis.
            </p>
        </div>
    </x-slot>

    <div class="px-4 py-10 sm:px-6 lg:px-8 dark:bg-slate-950 light:bg-slate-50 min-h-screen transition-colors">
        <div class="mx-auto max-w-7xl space-y-10">
            
            <!-- Statistics Banner -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Stat Card 1 -->
                <div class="saas-card p-6 dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200 flex items-center gap-4 relative overflow-hidden">
                    <div class="absolute -right-5 -bottom-5 w-24 h-24 bg-indigo-500/5 rounded-full blur-2xl"></div>
                    <div class="h-12 w-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <span class="block text-xs uppercase tracking-wider text-slate-500">Total Requests</span>
                        <span class="text-2xl font-black text-white">{{ $totalRequests }}</span>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="saas-card p-6 dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200 flex items-center gap-4 relative overflow-hidden">
                    <div class="absolute -right-5 -bottom-5 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl"></div>
                    <div class="h-12 w-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <span class="block text-xs uppercase tracking-wider text-slate-500">Audits Completed</span>
                        <span class="text-2xl font-black text-white">{{ $auditedCount }}</span>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="saas-card p-6 dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200 flex items-center gap-4 relative overflow-hidden">
                    <div class="absolute -right-5 -bottom-5 w-24 h-24 bg-cyan-400/5 rounded-full blur-2xl"></div>
                    <div class="h-12 w-12 rounded-2xl bg-cyan-400/10 border border-cyan-400/20 flex items-center justify-center text-cyan-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <span class="block text-xs uppercase tracking-wider text-slate-500">Average Score</span>
                        <span class="text-2xl font-black text-white">{{ $averageScore > 0 ? $averageScore . '%' : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Startup Cards Grid -->
            <div class="space-y-6">
                <h3 class="text-xl font-bold text-white tracking-tight">Active Venture Requests</h3>
                
                @if($requests->isEmpty())
                    <div class="saas-card p-12 text-center flex flex-col items-center justify-center space-y-4 dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                        <div class="h-16 w-16 rounded-2xl bg-slate-900 border border-white/10 flex items-center justify-center text-slate-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-white">No Funding Requests Yet</h4>
                            <p class="text-slate-400 text-sm">When startups submit funding requests to your opportunities, they will appear here for AI auditing.</p>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($requests as $item)
                            @php
                                $profile = $item->startup?->startupProfile;
                                $review = $item->aiReview;
                            @endphp
                            
                            <!-- Request Card -->
                            <div class="saas-card hover:bg-white/10 hover:border-white/20 transition-all duration-300 flex flex-col justify-between dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200 relative overflow-hidden group">
                                
                                <div class="p-6 space-y-4 relative z-10">
                                    <!-- Card Header: Startup Logo Circle & Score Indicator -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-cyan-400 text-lg font-black text-white shadow-lg shadow-indigo-500/20">
                                                {{ strtoupper(substr($item->startup?->name ?? 'S', 0, 1)) }}
                                            </span>
                                            <div>
                                                <h4 class="text-base font-bold text-white group-hover:text-indigo-300 transition-colors leading-snug">{{ $item->startup?->name }}</h4>
                                                <span class="text-slate-500 text-xs font-semibold capitalize">{{ $profile?->stage ?? 'No Stage' }} Stage</span>
                                            </div>
                                        </div>
                                        
                                        @if($review && $review->status === 'completed')
                                            <!-- Mini Circular Score Gauge -->
                                            <div class="flex items-center justify-center h-10 w-10 rounded-full border-4 font-black text-xs transition {{
                                                     $review->overall_score >= 75 ? 'border-emerald-500 text-emerald-400' : 
                                                     ($review->overall_score >= 50 ? 'border-amber-500 text-amber-400' : 'border-red-500 text-red-400')
                                                 }}">
                                                {{ $review->overall_score }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Opportunity Attached -->
                                    @if($item->opportunity)
                                        <div class="bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-xs text-slate-300 leading-normal">
                                            <span class="text-slate-500 uppercase tracking-wider text-[10px] font-black block mb-0.5">Opportunity</span>
                                            <span class="truncate block font-semibold text-slate-200">{{ $item->opportunity->title }}</span>
                                        </div>
                                    @endif

                                    <!-- Requested Details -->
                                    <div class="grid grid-cols-2 gap-4 border-t border-b border-white/10 py-3">
                                        <div>
                                            <span class="block text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-0.5">Requested</span>
                                            <span class="text-white text-sm font-black">${{ number_format($item->requested_amount, 0) }}</span>
                                        </div>
                                        <div>
                                            <span class="block text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-0.5">Industry</span>
                                            <span class="text-indigo-300 text-xs font-semibold truncate block">{{ $profile?->industry ?? 'N/A' }}</span>
                                        </div>
                                    </div>

                                    <!-- Summary Snippet -->
                                    <div class="h-16 overflow-hidden">
                                        <span class="block text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1">Founder Pitch</span>
                                        <p class="text-slate-400 text-xs leading-relaxed line-clamp-2">
                                            {{ $profile?->pitch_description ?? 'No description provided.' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Card Action Footer -->
                                <div class="px-6 py-4 bg-white/5 border-t border-white/10 flex items-center justify-between relative z-10">
                                    <div>
                                        @if(!$review || $review->status === 'none')
                                            <span class="inline-flex items-center gap-1.5 text-xs text-slate-400 font-semibold">
                                                <span class="h-2 w-2 rounded-full bg-slate-500"></span>
                                                Audit Pending
                                            </span>
                                        @elseif($review->status === 'pending' || $review->status === 'processing')
                                            <span class="inline-flex items-center gap-1.5 text-xs text-indigo-400 font-semibold">
                                                <span class="h-2 w-2 rounded-full bg-indigo-500 animate-ping"></span>
                                                Auditing...
                                            </span>
                                        @elseif($review->status === 'completed')
                                            <span class="inline-flex items-center gap-1.5 text-xs text-emerald-400 font-semibold">
                                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                                Audit Complete
                                            </span>
                                        @elseif($review->status === 'failed')
                                            <span class="inline-flex items-center gap-1.5 text-xs text-red-400 font-semibold">
                                                <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                                Audit Failed
                                            </span>
                                        @endif
                                    </div>

                                    <a href="{{ route('requests.review.show', $item) }}" 
                                       class="secondary-button text-xs py-1.5 px-4 rounded-xl flex items-center gap-1.5 transition duration-200">
                                        @if($review && $review->status === 'completed')
                                            View Report
                                        @else
                                            Run AI Review
                                        @endif
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </div>
                                
                            </div>
                        @endforeach
                    </div>
                
                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $requests->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
