<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="section-label mb-3">AI-Powered Search</p>
                <h2 class="text-3xl font-bold tracking-tight text-white dark:text-white light:text-slate-900">
                    Platform Assistant
                </h2>
                <p class="mt-2 text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">
                    Ask anything about startups, investors, or funding opportunities on this platform.
                    Powered by RAG + Groq.
                </p>
            </div>
            <span class="saas-pill hidden lg:inline-flex">
                <span class="mr-2 h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                RAG · Groq LLM · Pinecone
            </span>
        </div>
    </x-slot>

    <div class="px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl space-y-8">

            {{-- ── Query Form ───────────────────────────────────────────────── --}}
            <div class="saas-card dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                <h3 class="mb-1 text-lg font-semibold text-white dark:text-white light:text-slate-900">
                    Ask a question
                </h3>
                <p class="mb-6 text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">
                    Examples: "Which investors focus on fintech seed stage?" · "What are the active funding opportunities in healthcare?" · "Find startups seeking Series A in the US."
                </p>

                <form id="rag-form" class="space-y-4">
                    @csrf
                    <div>
                        <label for="rag-query" class="saas-label">Your query</label>
                        <textarea
                            id="rag-query"
                            name="query"
                            rows="3"
                            maxlength="500"
                            placeholder="e.g. Which investors have fintech in their preferred industries?"
                            class="saas-input resize-none"
                            required
                        ></textarea>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-500 light:text-slate-400">
                            Min 3 characters · Max 500 characters
                        </p>
                    </div>

                    <div class="flex items-center gap-4">
                        <button
                            type="submit"
                            id="rag-submit"
                            class="primary-button flex items-center gap-2"
                        >
                            <svg id="rag-icon-search" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                            </svg>
                            <svg id="rag-icon-spin" class="hidden h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                            </svg>
                            <span id="rag-btn-text">Search Platform Data</span>
                        </button>
                        <button type="button" id="rag-clear" class="secondary-button hidden">Clear</button>
                    </div>
                </form>
            </div>

            {{-- ── Answer Section ───────────────────────────────────────────── --}}
            <div id="rag-result" class="hidden space-y-6">

                {{-- Answer card --}}
                <div class="saas-card dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                    <div class="mb-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="grid h-8 w-8 place-items-center rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </span>
                            <h3 class="text-lg font-semibold text-white dark:text-white light:text-slate-900">AI Answer</h3>
                        </div>
                        <span id="rag-model-badge" class="saas-pill text-xs"></span>
                    </div>
                    <div
                        id="rag-answer-text"
                        class="text-sm leading-7 text-slate-300 dark:text-slate-300 light:text-slate-700 whitespace-pre-wrap"
                    ></div>
                </div>

                {{-- Citations --}}
                <div id="rag-citations-section" class="hidden">
                    <div class="mb-4 flex items-center gap-2">
                        <p class="section-label">Sources</p>
                        <span id="rag-citation-count" class="saas-pill"></span>
                    </div>
                    <div id="rag-citations" class="grid gap-3 sm:grid-cols-2"></div>
                </div>
            </div>

            {{-- ── Error Section ────────────────────────────────────────────── --}}
            <div id="rag-error" class="hidden rounded-2xl border border-rose-400/20 bg-rose-500/10 px-5 py-4 text-rose-200 dark:border-rose-400/20 dark:bg-rose-500/10 dark:text-rose-200 light:border-rose-200 light:bg-rose-50 light:text-rose-800">
                <div class="flex items-start gap-3">
                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.072 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <p id="rag-error-text" class="text-sm font-medium"></p>
                </div>
            </div>

            {{-- ── How it works ─────────────────────────────────────────────── --}}
            <div class="rounded-[1.75rem] border border-white/10 bg-gradient-to-br from-indigo-500/10 via-violet-500/8 to-cyan-500/10 p-6 dark:border-white/10 light:border-indigo-200 light:from-indigo-50 light:to-violet-50">
                <p class="mb-4 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400 dark:text-slate-400 light:text-slate-600">How it works</p>
                <div class="grid gap-4 sm:grid-cols-3">
                    @foreach([
                        ['🔍', 'Embed & Search', 'Your query is embedded and matched against startup profiles, investor profiles, and funding opportunities in Pinecone.'],
                        ['🤖', 'Grounded Answer', 'Groq LLM generates a precise answer using only the retrieved platform data — no hallucinations.'],
                        ['📎', 'Citations', 'Every answer links to the exact records it was drawn from so you can verify the source.'],
                    ] as [$icon, $title, $desc])
                        <div class="flex gap-3">
                            <span class="text-2xl">{{ $icon }}</span>
                            <div>
                                <p class="text-sm font-semibold text-white dark:text-white light:text-slate-900">{{ $title }}</p>
                                <p class="mt-1 text-xs leading-5 text-slate-400 dark:text-slate-400 light:text-slate-600">{{ $desc }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <script>
    (function () {
        const form       = document.getElementById('rag-form');
        const queryEl    = document.getElementById('rag-query');
        const submitBtn  = document.getElementById('rag-submit');
        const btnText    = document.getElementById('rag-btn-text');
        const iconSearch = document.getElementById('rag-icon-search');
        const iconSpin   = document.getElementById('rag-icon-spin');
        const clearBtn   = document.getElementById('rag-clear');

        const resultEl        = document.getElementById('rag-result');
        const answerEl        = document.getElementById('rag-answer-text');
        const modelBadge      = document.getElementById('rag-model-badge');
        const citationsSection= document.getElementById('rag-citations-section');
        const citationsEl     = document.getElementById('rag-citations');
        const citCountEl      = document.getElementById('rag-citation-count');
        const errorEl         = document.getElementById('rag-error');
        const errorText       = document.getElementById('rag-error-text');

        const TYPE_LABELS = {
            startup_profile:     { label: 'Startup',    color: 'from-cyan-500/20 to-blue-500/20',   border: 'border-cyan-400/20' },
            investor_profile:    { label: 'Investor',   color: 'from-violet-500/20 to-purple-500/20', border: 'border-violet-400/20' },
            funding_opportunity: { label: 'Opportunity', color: 'from-emerald-500/20 to-teal-500/20', border: 'border-emerald-400/20' },
        };

        function setLoading(loading) {
            submitBtn.disabled = loading;
            iconSearch.classList.toggle('hidden', loading);
            iconSpin.classList.toggle('hidden', !loading);
            btnText.textContent = loading ? 'Searching…' : 'Search Platform Data';
        }

        function hideAll() {
            resultEl.classList.add('hidden');
            errorEl.classList.add('hidden');
            citationsSection.classList.add('hidden');
        }

        clearBtn.addEventListener('click', () => {
            queryEl.value = '';
            hideAll();
            clearBtn.classList.add('hidden');
            queryEl.focus();
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const query = queryEl.value.trim();
            if (query.length < 3) return;

            hideAll();
            setLoading(true);

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const res = await fetch('{{ route("ai-assistant.search") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ query, top_k: 6 }),
                });

                const data = await res.json();

                if (!res.ok || data.error) {
                    errorText.textContent = data.error ?? 'An unexpected error occurred.';
                    errorEl.classList.remove('hidden');
                    setLoading(false);
                    return;
                }

                // Show answer
                answerEl.textContent = data.answer ?? '';
                modelBadge.textContent = '⚡ ' + (data.model_used ?? 'groq');
                resultEl.classList.remove('hidden');
                clearBtn.classList.remove('hidden');

                // Show citations
                const citations = data.citations ?? [];
                if (citations.length > 0) {
                    citCountEl.textContent = citations.length + ' source' + (citations.length > 1 ? 's' : '');
                    citationsEl.innerHTML = citations.map(c => {
                        const meta = TYPE_LABELS[c.model_type] ?? { label: c.model_type, color: 'from-slate-500/20 to-slate-500/20', border: 'border-slate-400/20' };
                        return `
                        <div class="rounded-2xl border bg-gradient-to-br p-4 ${meta.color} ${meta.border}">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="grid h-6 w-6 flex-shrink-0 place-items-center rounded-full bg-white/10 text-xs font-bold text-white">[${c.ref}]</span>
                                    <p class="text-sm font-semibold text-white dark:text-white light:text-slate-900 line-clamp-1">${escHtml(c.title)}</p>
                                </div>
                                <span class="saas-pill flex-shrink-0 text-xs">${meta.label}</span>
                            </div>
                            <p class="mt-2 text-xs leading-5 text-slate-400 dark:text-slate-400 light:text-slate-600 line-clamp-3">${escHtml(c.excerpt)}</p>
                            <p class="mt-2 text-xs text-slate-500">Relevance: ${(c.relevance_score * 100).toFixed(1)}%</p>
                        </div>`;
                    }).join('');
                    citationsSection.classList.remove('hidden');
                }

            } catch (err) {
                errorText.textContent = 'Network error: ' + err.message;
                errorEl.classList.remove('hidden');
            } finally {
                setLoading(false);
                resultEl?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });

        function escHtml(str) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(str ?? ''));
            return d.innerHTML;
        }
    })();
    </script>
</x-app-layout>
