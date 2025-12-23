<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-slate-50 dark:bg-slate-900">
        <div x-data="{ sidebarOpen: false }">
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden" x-cloak></div>

    <div
        class="fixed inset-y-0 left-0 z-50 w-64 -translate-x-full transform border-e border-slate-200 bg-white/80 backdrop-blur-xl transition-transform duration-300 lg:translate-x-0 dark:border-slate-800 dark:bg-slate-900/80"
        :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
    >
        <div class="flex h-full flex-col">
            <div class="flex h-16 shrink-0 items-center justify-between px-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-x-2" wire:navigate>
                    <x-app-logo />
                </a>
                <button @click="sidebarOpen = false" class="-mr-2 inline-flex items-center justify-center rounded-md p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-600 lg:hidden dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-300">
                    <x-heroicon-o-x-mark class="size-6" />
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-4">
                <nav class="flex flex-col gap-y-4">
            
            @php
                $role = auth()->user()->role;
            @endphp

                            <div>
                        <div class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('Platform') }}</div>
                        <div class="space-y-1">
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate icon="heroicon-o-home">{{ __('Dashboard') }}</x-nav-link>

                            @if ($role === 'admin')
                                <x-nav-link :href="route('admin.distributors')" :active="request()->routeIs('admin.distributors*')" wire:navigate icon="heroicon-o-users">{{ __('Distributors') }}</x-nav-link>
                                <x-nav-link :href="route('admin.locations')" :active="request()->routeIs('admin.locations')" wire:navigate icon="heroicon-o-map-pin">{{ __('Locations') }}</x-nav-link>
                                <x-nav-link :href="route('admin.reports.export')" :active="request()->routeIs('admin.reports.export')" wire:navigate icon="heroicon-o-arrow-down-tray">{{ __('Export') }}</x-nav-link>
                            @else
                                <x-nav-link :href="route('distributor.locations')" :active="request()->routeIs('distributor.locations')" wire:navigate icon="heroicon-o-map-pin">{{ __('My Locations') }}</x-nav-link>
                                <x-nav-link :href="route('distributor.locations.create')" :active="request()->routeIs('distributor.locations.create')" wire:navigate icon="heroicon-o-plus">{{ __('Add Location') }}</x-nav-link>
                            @endif
                        </div>
                    </div>
            
            
            </nav>
            </div>

            <div class="mt-auto p-4">
                <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white/70 p-2 shadow-sm backdrop-blur dark:border-slate-800 dark:bg-slate-900/70">
                    <button
                        type="button"
                        x-data
                        @click="$flux.appearance = ($flux.appearance === 'dark' ? 'light' : 'dark')"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-600 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-300"
                        :aria-label="$flux.appearance === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'"
                        :title="$flux.appearance === 'dark' ? 'Light mode' : 'Dark mode'"
                    >
                        <x-heroicon-o-sun class="size-5" x-show="$flux.appearance === 'dark'" />
                        <x-heroicon-o-moon class="size-5" x-show="$flux.appearance !== 'dark'" />
                    </button>

                    <div x-data="{ open: false }" class="relative flex-1">
                        <button @click="open = !open" type="button" class="flex w-full items-center gap-x-2 rounded-lg p-1 text-left transition-colors duration-200 hover:bg-slate-100 dark:hover:bg-slate-800">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-full">
                                <span class="flex h-full w-full items-center justify-center bg-slate-200 font-semibold text-slate-700 dark:bg-slate-700 dark:text-slate-200">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="truncate text-sm font-semibold text-slate-800 dark:text-slate-200">{{ auth()->user()->name }}</span>
                            </span>
                            <x-heroicon-o-chevron-up-down class="size-4 shrink-0 text-slate-500 dark:text-slate-400" />
                        </button>

                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute bottom-full mb-2 w-full min-w-60 origin-bottom-left rounded-xl bg-white p-1.5 shadow-lg ring-1 ring-slate-900/5 dark:bg-slate-800 dark:ring-slate-50/10"
                            style="display: none;"
                        >
                            <div class="p-1.5">
                                <div class="flex items-center gap-x-3 px-2 py-1.5">
                                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-full">
                                        <span class="flex h-full w-full items-center justify-center bg-slate-200 font-semibold text-slate-700 dark:bg-slate-700 dark:text-slate-200">
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="truncate text-sm font-semibold text-slate-800 dark:text-slate-200">{{ auth()->user()->name }}</div>
                                        <div class="truncate text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</div>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-slate-200 dark:border-slate-700" />

                            <div class="p-1.5">
                                <a href="{{ route('profile.edit') }}" wire:navigate class="flex w-full items-center gap-x-3 rounded-md px-2 py-1.5 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800/70">
                                    <x-heroicon-o-cog-6-tooth class="size-5" />
                                    {{ __('Settings') }}
                                </a>
                            </div>

                            <hr class="border-slate-200 dark:border-slate-700" />

                            <div class="p-1.5">
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-x-3 rounded-md px-2 py-1.5 text-sm font-medium text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800/70">
                                        <x-heroicon-o-arrow-left-on-rectangle class="size-5" />
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:pl-64">
        <header class="sticky top-0 z-30 flex h-16 w-full items-center justify-between border-b border-slate-200 bg-white/80 px-4 backdrop-blur-xl lg:justify-end dark:border-slate-800 dark:bg-slate-900/80 sm:px-6 lg:px-8">
            <button @click="sidebarOpen = true" class="-ml-2 inline-flex items-center justify-center rounded-md p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-600 lg:hidden dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-300">
                <x-heroicon-o-bars-3 class="size-6" />
            </button>
        </header>

        {{ $slot }}
    </div>
</div>

@fluxScripts
</body>
</html>
