<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-full border border-rose-400/25 bg-rose-500/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-rose-100 transition hover:border-rose-300/40 hover:bg-rose-500/20 focus:outline-none focus:ring-2 focus:ring-rose-400/30']) }}>
    {{ $slot }}
</button>
