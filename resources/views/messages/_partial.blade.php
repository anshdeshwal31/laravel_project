<div class="fixed inset-y-0 right-0 z-[100] flex w-full max-w-lg flex-col bg-black shadow-2xl">
    
    <div class="bg-gray-900 p-5 border-b border-gray-800 shrink-0">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Funding Discussion</h2>
                <p class="text-white font-semibold text-lg">{{ $fundingRequest->startup?->name }}</p>
                <p class="text-gray-400 text-xs mt-1">Requested: ${{ number_format($fundingRequest->requested_amount, 0) }}</p>
            </div>
            <button type="button" id="chat-close" class="text-gray-500 hover:text-white p-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-6 bg-black">
        @forelse ($fundingRequest->messages as $message)
            <div class="flex flex-col {{ auth()->id() === $message->sender_id ? 'items-end' : 'items-start' }}">
                <span class="text-[10px] text-gray-500 mb-1 font-bold uppercase tracking-tight">
                    {{ $message->sender->name }} • {{ $message->created_at->diffForHumans() }}
                </span>
                
                <div class="px-4 py-3 rounded-2xl text-sm shadow-lg {{ auth()->id() === $message->sender_id ? 'bg-indigo-700 text-white rounded-tr-none' : 'bg-gray-800 text-gray-100 rounded-tl-none border border-gray-700' }} max-w-[90%]">
                    {{ $message->body }}
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center h-full text-gray-600 italic">
                <p>No prior conversation found.</p>
            </div>
        @endforelse
    </div>

    <div class="p-6 bg-gray-900 border-t border-gray-800 shrink-0">
        <form id="chat-form" action="{{ route('messages.store', $fundingRequest) }}" method="POST">
            @csrf
            <div class="space-y-3">
                <textarea 
                    name="body" 
                    rows="3" 
                    class="w-full bg-black border-gray-700 text-white rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-gray-600 resize-none" 
                    placeholder="Type your message here..." 
                    required></textarea>
                
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 rounded-xl transition-all shadow-lg active:scale-95">
                        Send Message
                    </button>
                    <button type="button" id="chat-close-btn" class="px-6 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-xl border border-gray-700 transition-all font-medium">
                        Close
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>