<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-slate-50 antialiased dark:bg-slate-900">
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
        <div class="flex min-h-svh flex-col items-center justify-center p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-9 w-9 mb-1 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-9 fill-current text-black dark:text-white" />
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
