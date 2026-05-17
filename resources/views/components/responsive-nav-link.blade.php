@props(['active'])

@php
$classes = ($active ?? false)
            ? 'saas-sidebar-link saas-sidebar-link--active'
            : 'saas-sidebar-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
