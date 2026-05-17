@props(['value'])

<label {{ $attributes->merge(['class' => 'saas-label']) }}>
    {{ $value ?? $slot }}
</label>
