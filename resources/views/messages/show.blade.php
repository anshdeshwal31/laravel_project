<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white light:text-slate-900">Messages</h2>
            <button id="theme-toggle" class="px-3 py-1 rounded-lg border border-white/10 bg-white/5 text-sm text-gray-300 hover:bg-white/10 transition">
                🌙
            </button>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
            <div class="bg-white/5 glass-card rounded-lg p-6 text-sm text-gray-300 border border-white/10 dark:bg-white/5 dark:text-gray-300 dark:border-white/10 light:bg-gray-100 light:text-gray-800 light:border-gray-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Funding Discussion</h2>
                        <p class="text-white font-semibold text-lg dark:text-white light:text-gray-900">{{ $fundingRequest->startup?->name }}</p>
                        <p class="text-gray-400 text-xs mt-1 dark:text-gray-400 light:text-gray-600">Requested: ${{ number_format($fundingRequest->requested_amount, 0) }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('requests.index') }}" class="secondary-button">Back to requests</a>
                    </div>
                </div>

                <div class="flex flex-col h-[70vh]">
                    <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-6 bg-white/[0.03] glass-panel rounded-lg scrollbar-thin scrollbar-thumb-slate-700 dark:bg-white/[0.03] light:bg-white light:border light:border-gray-300">
                        @forelse ($fundingRequest->messages as $message)
                            <div class="flex flex-col {{ auth()->id() === $message->sender_id ? 'items-end' : 'items-start' }}">
                                <span class="text-[10px] text-gray-500 mb-1 font-bold uppercase tracking-tight dark:text-gray-500 light:text-gray-600">
                                    {{ $message->sender->name }} • {{ $message->created_at->diffForHumans() }}
                                </span>
                                <div class="px-4 py-3 rounded-2xl text-sm shadow-lg {{ auth()->id() === $message->sender_id ? 'bg-indigo-700 text-white rounded-tr-none dark:bg-indigo-700 light:bg-indigo-600' : 'bg-gray-800 text-gray-100 rounded-tl-none border border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 light:bg-gray-200 light:text-gray-900 light:border-gray-400' }} max-w-[90%]">
                                    {{ $message->body }}
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full text-gray-600 italic dark:text-gray-600 light:text-gray-500">
                                <p>No prior conversation found.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4 sticky bottom-0 bg-slate-950/50 border-t border-white/6 p-4 rounded-b-lg dark:bg-slate-950/50 dark:border-white/6 light:bg-white light:border-t light:border-gray-300">
                        <form id="chat-form" action="{{ route('messages.store', $fundingRequest) }}" method="POST">
                            @csrf
                            <div class="space-y-3">
                                <label for="body" class="sr-only">New message</label>
                                <textarea
                                    id="body"
                                    name="body"
                                    rows="3"
                                    class="w-full min-h-[96px] bg-white/5 border border-white/6 text-slate-900 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder:text-slate-400 p-4 resize-none dark:bg-white/5 dark:border-white/6 dark:text-slate-900 dark:placeholder:text-slate-400 light:bg-gray-50 light:border-gray-300 light:text-gray-900 light:placeholder:text-gray-500"
                                    placeholder="Write a message to the startup..."
                                    required></textarea>

                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('requests.index') }}" class="secondary-button px-4 py-2">Close</a>
                                    <button type="submit" class="primary-button px-6 py-2">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const form = document.getElementById('chat-form');
            const messagesEl = document.getElementById('chat-messages');
                const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute('content') : '';

            if (!form || !messagesEl) return;

            async function loadMessages() {
                try {
                    const res = await fetch('{{ route('messages.partial', $fundingRequest) }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' });
                    if (!res.ok) throw new Error('Failed to load messages');
                    const html = await res.text();
                    const wrapper = document.createElement('div');
                    wrapper.innerHTML = html;
                    const newMessages = wrapper.querySelector('#chat-messages');
                    if (newMessages) {
                        messagesEl.innerHTML = newMessages.innerHTML;
                        messagesEl.scrollTop = messagesEl.scrollHeight;
                    }
                } catch (err) {
                    console.error(err);
                }
            }

            form.addEventListener('submit', async function (ev) {
                ev.preventDefault();
                const submitBtn = form.querySelector('button[type="submit"]');
                const fd = new FormData(form);
                try {
                    if (submitBtn) submitBtn.disabled = true;
                    const res = await fetch(form.action, {
                        method: 'POST',
                        body: fd,
                        credentials: 'same-origin',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken
                            }
                    });
                    if (!res.ok) throw new Error('Failed to send message');
                    await loadMessages();
                    const ta = form.querySelector('textarea[name="body"]');
                    if (ta) ta.value = '';
                } catch (err) {
                    console.error(err);
                    alert('Unable to send message.');
                } finally {
                    if (submitBtn) submitBtn.disabled = false;
                }
            });

            // initial scroll
            messagesEl.scrollTop = messagesEl.scrollHeight;

            // Poll for new messages every 2 seconds
            setInterval(loadMessages, 2000);
        })();
    </script>
 </x-app-layout>