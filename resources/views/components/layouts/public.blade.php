<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-slate-50 dark:bg-slate-900">
        <button
            type="button"
            x-data
            @click="$flux.appearance = ($flux.appearance === 'dark' ? 'light' : 'dark')"
            class="fixed right-4 top-4 z-50 sg-icon-btn"
            :aria-label="$flux.appearance === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'"
            :title="$flux.appearance === 'dark' ? 'Light mode' : 'Dark mode'"
        >
            <x-heroicon-o-sun class="size-5" x-show="$flux.appearance === 'dark'" />
            <x-heroicon-o-moon class="size-5" x-show="$flux.appearance !== 'dark'" />
        </button>
        {{ $slot }}

        @fluxScripts
    </body>
</html>
