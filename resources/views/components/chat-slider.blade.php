<div id="chat-slider" class="fixed top-16 bottom-16 right-0 w-[32rem] max-w-full transform translate-x-full transition-transform duration-300 z-[2147483647]" style="z-index: 2147483647;">
    <div class="h-full flex flex-col shadow-2xl bg-slate-950 text-gray-100">
        <div class="px-4 py-3 border-b border-white/6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.97-4.03 9-9 9a8.96 8.96 0 01-4-.9L3 21l1.9-4.1A8.96 8.96 0 013 12c0-4.97 4.03-9 9-9s9 4.03 9 9z" />
                </svg>
                <h3 class="text-sm font-semibold">Conversation</h3>
            </div>
            <div class="flex items-center gap-2">
                <button id="chat-minimize" class="text-sm text-gray-300 hover:text-white">Minimize</button>
                <button id="chat-close" class="text-gray-300 hover:text-white">✕</button>
            </div>
        </div>

        <div id="chat-content" class="flex-1 p-4 overflow-auto">
            <!-- AJAX-loaded content goes here -->
            <div class="text-center text-sm text-gray-400">Open a conversation from requests to begin.</div>
        </div>
    </div>
</div>

<!-- Floating chat button -->
<button id="open-chat" aria-expanded="false" title="Open chat" class="fixed right-6 bottom-6 z-[2147483646] bg-indigo-600 text-white rounded-full w-14 h-14 shadow-lg flex items-center justify-center hover:bg-indigo-500" style="z-index: 2147483646;">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.97-4.03 9-9 9a8.96 8.96 0 01-4-.9L3 21l1.9-4.1A8.96 8.96 0 013 12c0-4.97 4.03-9 9-9s9 4.03 9 9z"/></svg>
</button>

<script>
(function () {
    const slider = document.getElementById('chat-slider');
    const openBtn = document.getElementById('open-chat');
    const closeBtn = document.getElementById('chat-close');
    const minimizeBtn = document.getElementById('chat-minimize');
    const content = document.getElementById('chat-content');

    if (!slider || !openBtn || !content) {
        return;
    }

    function openSlider() {
        slider.classList.remove('translate-x-full');
        openBtn.setAttribute('aria-expanded', 'true');
    }
    function closeSlider() {
        slider.classList.add('translate-x-full');
        openBtn.setAttribute('aria-expanded', 'false');
    }

    openBtn.addEventListener('click', () => openSlider());
    if (closeBtn) closeBtn.addEventListener('click', () => closeSlider());
    if (minimizeBtn) minimizeBtn.addEventListener('click', () => closeSlider());
    document.addEventListener('click', (e) => {
        // If clicking an element with data-chat-request attribute, open and load that request
        const el = e.target.closest && e.target.closest('[data-chat-request]');
        if (el) {
            const requestId = el.getAttribute('data-chat-request');
            if (!requestId) return;
            // Prevent default navigation if element is a link
            if (el.tagName === 'A') e.preventDefault();
            console.debug('[chat-slider] intercepted click for request', requestId, 'element:', el);
            openSlider();
            loadConversation(requestId);
        }
    });

    // Also bind click handlers to any existing elements for immediate UX
    document.querySelectorAll('[data-chat-request]').forEach(el => {
        el.addEventListener('click', (e) => {
            const id = el.getAttribute('data-chat-request');
            if (!id) return;
            if (el.tagName === 'A') e.preventDefault();
            console.debug('[chat-slider] direct handler click for request', id);
            openSlider();
            loadConversation(id);
        });
    });

    function loadConversation(requestId) {
        content.innerHTML = '<div class="text-center py-6 text-sm text-gray-400">Loading…</div>';
        fetch(`/requests/${requestId}/messages/partial`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        }).then(r => {
            if (!r.ok) {
                if (r.status === 403) throw new Error('You do not have access to this conversation (403).');
                if (r.status === 404) throw new Error('Conversation not found (404).');
                throw new Error('Failed to load conversation. HTTP ' + r.status);
            }
            return r.text();
        }).then(html => {
            content.innerHTML = html;
            wireForm(requestId);
            // scroll to bottom and focus input
            const msgs = document.getElementById('chat-messages');
            if (msgs) msgs.scrollTo({ top: msgs.scrollHeight, behavior: 'smooth' });
            const input = content.querySelector('#body');
            if (input) input.focus({ preventScroll: true });
        }).catch(err => {
            content.innerHTML = `<div class="p-4 text-sm text-red-300">Unable to load conversation. ${err.message}</div>`;
            console.error('Chat load error:', err);
        });
    }

    function wireForm(requestId) {
        const form = document.getElementById('chat-form');
        if (!form) return;
        form.addEventListener('submit', function (ev) {
            ev.preventDefault();
            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                credentials: 'same-origin',
                body: formData
            }).then(r => {
                if (!r.ok) {
                    throw new Error('Failed to send message. HTTP ' + r.status);
                }
                // After successful POST, reload partial to show the new message
                return fetch(`/requests/${requestId}/messages/partial`, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' });
            }).then(r2 => r2.text()).then(html => {
                content.innerHTML = html;
                wireForm(requestId);
                const msgs = document.getElementById('chat-messages');
                if (msgs) msgs.scrollTo({ top: msgs.scrollHeight, behavior: 'smooth' });
                const input = content.querySelector('#body');
                if (input) input.focus({ preventScroll: true });
            }).catch(err => {
                content.insertAdjacentHTML('afterbegin', `<div class="p-3 text-sm text-red-300">${err.message}</div>`);
                console.error('Chat send error:', err);
            });
        });

        const closeBtnSecondary = document.getElementById('chat-close-secondary');
        if (closeBtnSecondary) closeBtnSecondary.addEventListener('click', () => closeSlider());
    }

    // Expose for debugging
    window.chatSlider = { open: openSlider, close: closeSlider, load: loadConversation };
})();
</script>
