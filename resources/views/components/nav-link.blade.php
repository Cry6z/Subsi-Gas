@props(['active' => false, 'icon' => null])

@php
$classes = (
    $active ?? false
        ? 'flex items-center gap-x-3 rounded-lg bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-800 dark:bg-slate-800 dark:text-slate-200'
        : 'flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200'
);
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if ($icon)
        <x-dynamic-component :component="$icon" class="size-5 shrink-0" />
    @endif
    <span class="truncate">{{ $slot }}</span>
</a>
