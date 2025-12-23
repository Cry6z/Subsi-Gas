<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main class="bg-zinc-50/70 dark:bg-zinc-950/40">
        <div class="mx-auto w-full max-w-7xl px-4 py-6 lg:px-6">
            {{ $slot }}
        </div>
    </flux:main>
</x-layouts.app.sidebar>
