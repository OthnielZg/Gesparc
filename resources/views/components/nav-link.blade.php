@props(['active', 'icon'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-3 text-sm font-bold text-ci-orange bg-white/5 rounded-2xl group transition-colors'
            : 'flex items-center px-4 py-3 text-sm font-bold text-white/50 hover:text-white hover:bg-white/5 rounded-2xl group transition-colors';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <i class="fa-solid {{ $icon }} w-8 text-center text-lg {{ ($active ?? false) ? 'text-ci-orange' : 'text-white/30' }} group-hover:text-white transition-colors"></i>
    {{ $slot }}
</a>
