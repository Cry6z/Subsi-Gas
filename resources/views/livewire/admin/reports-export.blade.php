<div class="sg-page">
    <div>
        <flux:heading size="xl">{{ __('Export Reports') }}</flux:heading>
        <flux:subheading>{{ __('Download a CSV of current stock per location') }}</flux:subheading>
    </div>

    <div class="sg-card p-5">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('CSV includes distributor, coordinates, and stock fields.') }}
            </div>

            <flux:button variant="primary" wire:click="download">
                {{ __('Download CSV') }}
            </flux:button>
        </div>
    </div>
</div>
