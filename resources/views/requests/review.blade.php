<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="section-label mb-3">Venture Intelligence Engine</p>
                <h2 class="text-3xl font-bold tracking-tight text-white dark:text-white light:text-slate-900">AI Pitch Deck Evaluation</h2>
                <p class="mt-2 text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">
                    Comprehensive startup suitability audit for <span class="text-indigo-400 font-semibold">{{ $fundingRequest->startup?->name }}</span>
                </p>
            </div>
            <div>
                <a href="{{ route('requests.index') }}" class="secondary-button px-5 py-2.5 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Requests
                </a>
            </div>
        </div>
    </x-slot>

    <div x-data="reviewPage({{ $fundingRequest->id }}, '{{ $fundingRequest->aiReview?->status ?? 'none' }}')" 
         class="px-4 py-10 sm:px-6 lg:px-8 dark:bg-slate-950 light:bg-slate-50 min-h-screen transition-colors">
        <div class="mx-auto max-w-7xl">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Core Metrics & Request Details -->
                <div class="col-span-1 space-y-6">
                    
                    <!-- Dynamic Feasibility Score Gauge (only shown when completed) -->
                    <div x-show="status === 'completed'" 
                         x-transition.opacity 
                         class="saas-card p-6 flex flex-col items-center justify-center dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                        <span class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-4">Investment Suitability</span>
                        <div class="relative w-40 h-40 flex items-center justify-center rounded-full border-[10px] shadow-2xl transition-all duration-500"
                             :class="{
                                'border-emerald-500 text-emerald-400 shadow-emerald-500/10': reviewData.overall_score >= 75,
                                'border-amber-500 text-amber-400 shadow-amber-500/10': reviewData.overall_score >= 50 && reviewData.overall_score < 75,
                                'border-red-500 text-red-400 shadow-red-500/10': reviewData.overall_score < 50
                             }">
                            <div class="text-center">
                                <span class="text-5xl font-black tracking-tight" x-text="reviewData.overall_score"></span>
                                <span class="block text-xs font-medium text-slate-400 mt-1">out of 100</span>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <span class="saas-pill"
                                  :class="{
                                      'bg-emerald-500/10 text-emerald-400 border border-emerald-400/20': reviewData.overall_score >= 75,
                                      'bg-amber-500/10 text-amber-400 border border-amber-400/20': reviewData.overall_score >= 50 && reviewData.overall_score < 75,
                                      'bg-red-500/10 text-red-400 border border-red-400/20': reviewData.overall_score < 50
                                  }"
                                  x-text="reviewData.overall_score >= 75 ? 'Strong Match' : (reviewData.overall_score >= 50 ? 'Average Match' : 'High Risk')">
                            </span>
                        </div>
                    </div>

                    <!-- Startup Card -->
                    <div class="saas-card p-6 space-y-4 dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                        <h3 class="text-lg font-bold text-white border-b border-white/10 pb-3">Startup Profile</h3>
                        <div>
                            <span class="block text-xs uppercase tracking-wider text-slate-500">Company Name</span>
                            <span class="text-white text-base font-medium">{{ $fundingRequest->startup?->name }}</span>
                        </div>
                        @if($fundingRequest->startup?->startupProfile)
                            @php $profile = $fundingRequest->startup->startupProfile; @endphp
                            <div>
                                <span class="block text-xs uppercase tracking-wider text-slate-500">Industry</span>
                                <span class="text-indigo-300 font-medium text-sm">{{ $profile->industry }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="block text-xs uppercase tracking-wider text-slate-500">Stage</span>
                                    <span class="text-white text-sm font-medium capitalize">{{ $profile->stage }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs uppercase tracking-wider text-slate-500">Location</span>
                                    <span class="text-white text-sm font-medium">{{ $profile->location }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="block text-xs uppercase tracking-wider text-slate-500">Domain</span>
                                <a href="{{ $profile->domain }}" target="_blank" class="text-cyan-400 hover:underline text-sm font-medium flex items-center gap-1">
                                    {{ $profile->domain }}
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            </div>
                            
                            @if(!empty($profile->document_paths))
                                <div class="pt-2">
                                    <span class="block text-xs uppercase tracking-wider text-slate-500 mb-2">Attached Documents</span>
                                    @foreach($profile->document_paths as $path)
                                        <a href="{{ Storage::disk('public')->url($path) }}" target="_blank" class="flex items-center gap-2 p-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition text-slate-300 text-xs">
                                            <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            <span class="truncate">{{ basename($path) }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <p class="text-slate-400 text-sm italic">Profile information unavailable.</p>
                        @endif
                    </div>

                    <!-- Funding Request Info -->
                    <div class="saas-card p-6 space-y-4 dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                        <h3 class="text-lg font-bold text-white border-b border-white/10 pb-3">Request Context</h3>
                        <div>
                            <span class="block text-xs uppercase tracking-wider text-slate-500">Requested Funding</span>
                            <span class="text-white text-2xl font-black">${{ number_format($fundingRequest->requested_amount, 2) }}</span>
                        </div>
                        @if($fundingRequest->opportunity)
                            <div>
                                <span class="block text-xs uppercase tracking-wider text-slate-500">Associated Program</span>
                                <span class="text-slate-200 text-sm font-semibold">{{ $fundingRequest->opportunity->title }}</span>
                            </div>
                        @endif
                        <div>
                            <span class="block text-xs uppercase tracking-wider text-slate-500 mb-1">Founder Message</span>
                            <p class="text-slate-300 text-xs leading-relaxed bg-white/5 border border-white/10 p-3 rounded-xl">
                                "{{ $fundingRequest->message ?? 'No message attached.' }}"
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Interactive AI Evaluation Panel -->
                <div class="col-span-1 lg:col-span-2 space-y-6">
                    
                    <!-- State: None or Failed -->
                    <div x-show="status === 'none' || status === 'failed'" 
                         class="saas-card p-12 text-center flex flex-col items-center justify-center space-y-6 dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200 min-h-[400px]">
                        
                        <div class="w-20 h-20 rounded-3xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                            <svg class="w-10 h-10 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                        
                        <div class="max-w-md">
                            <h3 class="text-2xl font-bold text-white mb-2">No AI Suitability Audit</h3>
                            <p class="text-slate-400 text-sm leading-relaxed">
                                Get a complete, venture-grade startup analysis covering feasibility scoring, executive breakdown, key vulnerabilities, and risk lists by compiling the pitch deck documents.
                            </p>
                        </div>

                        <!-- Error Message View -->
                        <div x-show="status === 'failed'" 
                             x-transition.opacity 
                             class="max-w-xl p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-400 text-sm text-left flex items-start gap-2">
                            <span class="text-lg mt-0.5">⚠</span>
                            <div>
                                <span class="font-bold block mb-0.5">Execution Failed</span>
                                <span x-text="errorMessage"></span>
                            </div>
                        </div>

                        <button @click="triggerReview()" 
                                class="primary-button text-lg px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl shadow-xl shadow-indigo-500/20 transition-all font-bold">
                            Generate AI Venture Audit
                        </button>
                    </div>

                    <!-- State: Pending or Processing -->
                    <div x-show="status === 'pending' || status === 'processing'" 
                         x-transition.opacity 
                         class="saas-card p-12 text-center flex flex-col items-center justify-center space-y-6 dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200 min-h-[400px]"
                         style="display: none;">
                        
                        <div class="relative w-24 h-24">
                            <div class="absolute inset-0 rounded-full border-[3px] border-indigo-500/20"></div>
                            <div class="absolute inset-0 rounded-full border-t-[3px] border-indigo-500 animate-spin"></div>
                            <div class="absolute inset-3 rounded-full border-r-[3px] border-cyan-400 animate-spin" style="animation-direction: reverse; animation-duration: 1.5s;"></div>
                        </div>

                        <div class="max-w-md">
                            <h3 class="text-2xl font-bold text-white mb-2 animate-pulse">Running Pitch Deck Audit...</h3>
                            <p class="text-slate-400 text-sm leading-relaxed">
                                Loading raw PDF pitch deck, parsing document content vectors, compiling startup financials, and executing LLM VC evaluation matrix. This takes approximately 4-5 seconds.
                            </p>
                        </div>
                    </div>

                    <!-- State: Completed Report View -->
                    <div x-show="status === 'completed'" 
                         class="space-y-6" 
                         style="display: none;">
                        
                        <!-- Executive Summary Card -->
                        <div class="saas-card p-6 dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                            <h4 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Executive Evaluation
                            </h4>
                            <p class="text-slate-300 text-sm leading-relaxed" x-text="reviewData.summary"></p>
                        </div>

                        <!-- Strengths & Weaknesses Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Strengths Card -->
                            <div class="saas-card p-6 bg-emerald-500/5 border border-emerald-500/10">
                                <h4 class="text-lg font-bold text-emerald-400 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Venture Strengths
                                </h4>
                                <ul class="space-y-3">
                                    <template x-for="item in reviewData.strengths">
                                        <li class="flex items-start gap-3 text-xs text-slate-300 leading-normal">
                                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-400 font-bold">✓</span>
                                            <span x-text="item"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>

                            <!-- Weaknesses Card -->
                            <div class="saas-card p-6 bg-amber-500/5 border border-amber-500/10">
                                <h4 class="text-lg font-bold text-amber-400 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    Weaknesses & Concerns
                                </h4>
                                <ul class="space-y-3">
                                    <template x-for="item in reviewData.weaknesses">
                                        <li class="flex items-start gap-3 text-xs text-slate-300 leading-normal">
                                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-amber-500/10 flex items-center justify-center text-amber-400 font-bold">!</span>
                                            <span x-text="item"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <!-- Risks Assessment -->
                        <div x-show="reviewData.risks && reviewData.risks.length > 0" 
                             class="saas-card p-6 bg-red-500/5 border border-red-500/10">
                            <h4 class="text-lg font-bold text-red-400 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Strategic Venture Risks
                            </h4>
                            <ul class="space-y-3">
                                <template x-for="item in reviewData.risks">
                                    <li class="flex items-start gap-3 text-xs text-slate-300 leading-normal">
                                        <span class="flex-shrink-0 w-5 h-5 rounded-full bg-red-500/10 flex items-center justify-center text-red-400 font-bold">⚠</span>
                                        <span x-text="item"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <!-- Final Investment Verdict -->
                        <div class="saas-card p-6 bg-indigo-500/10 border border-indigo-500/20 relative overflow-hidden">
                            <!-- Background decoration -->
                            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl"></div>
                            
                            <h4 class="text-lg font-bold text-indigo-400 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Investment Verdict
                            </h4>
                            <p class="text-indigo-100 text-lg italic leading-relaxed relative z-10" x-text="reviewData.verdict"></p>
                        </div>
                        
                    </div>

                </div>

            </div>

        </div>
    </div>

    <script>
        function reviewPage(id, initialStatus) {
            return {
                requestId: id,
                status: initialStatus,
                errorMessage: '',
                reviewData: {},
                pollInterval: null,

                init() {
                    if (this.status === 'completed') {
                        this.fetchData();
                    } else if (this.status === 'pending' || this.status === 'processing') {
                        this.startPolling();
                    }
                },

                async fetchData() {
                    try {
                        const response = await fetch(`/requests/${this.requestId}/review/status`);
                        const data = await response.json();
                        this.reviewData = data;
                    } catch (error) {
                        console.error("Failed to load completed review data", error);
                    }
                },

                async checkStatus() {
                    try {
                        const response = await fetch(`/requests/${this.requestId}/review/status`);
                        const data = await response.json();

                        if (data.status === 'none') {
                            this.status = 'none';
                            this.stopPolling();
                        } else if (data.status === 'pending' || data.status === 'processing') {
                            this.status = 'processing';
                            this.startPolling();
                        } else if (data.status === 'completed') {
                            this.status = 'completed';
                            this.reviewData = data;
                            this.stopPolling();
                        } else if (data.status === 'failed') {
                            this.status = 'failed';
                            this.errorMessage = data.error_message || 'An unknown error occurred during LLM parsing.';
                            this.stopPolling();
                        }
                    } catch (error) {
                        console.error("Error checking status", error);
                        this.stopPolling();
                    }
                },

                async triggerReview() {
                    this.status = 'processing';
                    try {
                        const response = await fetch(`/requests/${this.requestId}/review/trigger`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        if (response.ok) {
                            this.startPolling();
                        } else {
                            this.status = 'failed';
                            this.errorMessage = 'Failed to request background analysis.';
                        }
                    } catch (error) {
                        console.error("Failed to trigger review", error);
                        this.status = 'failed';
                        this.errorMessage = 'Network connectivity issue.';
                    }
                },

                startPolling() {
                    if (this.pollInterval) return;
                    this.pollInterval = setInterval(() => {
                        this.checkStatus();
                    }, 3000);
                },

                stopPolling() {
                    if (this.pollInterval) {
                        clearInterval(this.pollInterval);
                        this.pollInterval = null;
                    }
                }
            }
        }
    </script>
</x-app-layout>
