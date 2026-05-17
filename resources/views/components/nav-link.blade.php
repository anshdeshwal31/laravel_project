@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link nav-link--active px-4 py-2 rounded-full bg-white/8'
            : 'nav-link px-4 py-2 rounded-full hover:bg-white/5';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
