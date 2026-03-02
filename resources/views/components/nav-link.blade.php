@props(['active', 'icon'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-3 text-sm font-bold text-white bg-white/10 rounded-2xl transition-all group border-l-4 border-ci-orange shadow-inner'
            : 'flex items-center px-4 py-3 text-sm font-bold text-white/60 hover:text-white hover:bg-white/5 rounded-2xl transition-all group border-l-4 border-transparent';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <i class="fa-solid {{ $icon }} w-8 text-center text-lg {{ ($active ?? false) ? 'text-ci-orange' : 'text-white/40' }} group-hover:scale-110 group-hover:text-white transition-all"></i>
    {{ $slot }}
</a>
