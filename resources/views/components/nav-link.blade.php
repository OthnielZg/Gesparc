@props(['active', 'icon'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-3 text-sm font-bold text-white bg-slate-800 rounded-xl sidebar-link-active transition-all group'
            : 'flex items-center px-4 py-3 text-sm font-bold text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-all group';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <i class="fa-solid {{ $icon }} mr-3 text-sm {{ ($active ?? false) ? 'text-ci-orange' : 'text-slate-500' }} group-hover:text-ci-orange transition-colors"></i>
    {{ $slot }}
</a>
