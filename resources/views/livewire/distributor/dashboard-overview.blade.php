<div>
    <div class="space-y-4">
        <div class="flex flex-col gap-y-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">{{ __('Distributor Dashboard') }}</h1>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ __('Kelola stok, pantau lokasi, dan pastikan gas siap disalurkan.') }}</p>
            </div>
            <div class="flex shrink-0 items-center gap-x-2">
                <button type="button" wire:click="refreshStats" class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 disabled:pointer-events-none disabled:opacity-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">
                    <x-heroicon-o-arrow-path class="size-4" />
                    {{ __('Refresh') }}
                </button>
                <a href="{{ route('distributor.locations.create') }}" wire:navigate class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-transparent bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:pointer-events-none disabled:opacity-50">
                    <x-heroicon-o-plus class="size-4" />
                    {{ __('Tambah Lokasi') }}
                </a>
            </div>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-x-4">
                    <div>
                        <div class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Lokasi Anda') }}</div>
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
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-x-4">
                    <div>
                        <div class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Stok Kritis (<= 5)') }}</div>
                        <div class="mt-2 text-3xl font-bold text-red-600 dark:text-red-500">{{ number_format($lowStockCount) }}</div>
                    </div>
                    <div class="rounded-lg bg-red-100 p-2 dark:bg-red-900/50">
                        <x-heroicon-o-exclamation-triangle class="size-5 text-red-600 dark:text-red-500" />
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="flex flex-col justify-between rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Proyeksi Permintaan Mingguan') }}</h3>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Pastikan setiap lokasi memiliki stok di atas batas minimal sebelum akhir pekan.') }}</p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('distributor.locations') }}" wire:navigate class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 disabled:pointer-events-none disabled:opacity-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800">
                        {{ __('Pantau Lokasi') }}
                    </a>
                </div>
            </div>
            <div class="flex flex-col justify-between rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Laporkan Stok Kritis') }}</h3>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Kirim pembaruan ke admin agar pasokan cadangan segera dikirimkan.') }}</p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('distributor.locations.create') }}" wire:navigate class="inline-flex items-center justify-center gap-x-2 rounded-lg border border-transparent bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:pointer-events-none disabled:opacity-50">
                        {{ __('Ajukan Permintaan') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
