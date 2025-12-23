<div>
    <div class="space-y-4">
        <div class="flex flex-col gap-y-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">{{ __('Admin Dashboard') }}</h1>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                    {{ __('Sinkronisasi data pengguna, distributor, dan stok setiap detik untuk keputusan cepat.') }}
                </p>
            </div>
            <div class="flex shrink-0 items-center gap-x-2">
                <flux:button wire:click="refreshStats" variant="outline">
                    <span class="inline-flex items-center gap-2">
                        <x-heroicon-o-arrow-path class="size-4" />
                        {{ __('Refresh') }}
                    </span>
                </flux:button>
                <flux:button href="{{ route('admin.reports.export') }}" wire:navigate>
                    <span class="inline-flex items-center gap-2">
                        <x-heroicon-o-arrow-down-tray class="size-4" />
                        {{ __('Export') }}
                    </span>
                </flux:button>
            </div>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-x-4">
                    <div>
                        <div class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Total Pengguna') }}</div>
                        <div class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($totalUsers) }}</div>
                    </div>
                    <div class="rounded-lg bg-slate-100 p-2 dark:bg-slate-800">
                        <x-heroicon-o-users class="size-5 text-slate-500 dark:text-slate-400" />
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-x-4">
                    <div>
                        <div class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Distributor Aktif') }}</div>
                        <div class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($totalDistributors) }}</div>
                    </div>
                    <div class="rounded-lg bg-slate-100 p-2 dark:bg-slate-800">
                        <x-heroicon-o-truck class="size-5 text-slate-500 dark:text-slate-400" />
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-x-4">
                    <div>
                        <div class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Lokasi Terdaftar') }}</div>
                        <div class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($totalLocations) }}</div>
                    </div>
                    <div class="rounded-lg bg-slate-100 p-2 dark:bg-slate-800">
                        <x-heroicon-o-map-pin class="size-5 text-slate-500 dark:text-slate-400" />
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-x-4">
                    <div>
                        <div class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Total Stok') }}</div>
                        <div class="mt-2 text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($totalStock) }}</div>
                    </div>
                    <div class="rounded-lg bg-slate-100 p-2 dark:bg-slate-800">
                        <x-heroicon-o-archive-box class="size-5 text-slate-500 dark:text-slate-400" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
