@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'space-y-1 text-sm text-rose-300']) }}>
        @foreach ((array) $messages as $message)
            <li class="rounded-2xl border border-rose-400/15 bg-rose-500/10 px-4 py-3">{{ $message }}</li>
        @endforeach
    </ul>
@endif
