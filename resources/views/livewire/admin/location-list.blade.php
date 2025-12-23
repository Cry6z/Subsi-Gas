<div class="sg-page">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <flux:heading size="xl">{{ __('Locations') }}</flux:heading>
                <flux:subheading>{{ __('All distributor locations') }}</flux:subheading>
            </div>

        <div class="flex flex-wrap gap-2">
            <flux:button variant="primary" href="{{ route('admin.locations.create') }}" wire:navigate>
                {{ __('Add Location') }}
            </flux:button>
            <flux:button href="{{ route('admin.locations.import') }}" wire:navigate>
                {{ __('Import CSV/Excel') }}
            </flux:button>
        </div>

            <div class="w-full md:w-[320px]">
                <flux:input wire:model.live="search" :label="__('Search')" placeholder="name / address" />
            </div>
        </div>

        <div class="sg-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-950/40 text-zinc-500">
                        <tr>
                            <th class="text-left p-3">{{ __('Name') }}</th>
                            <th class="text-left p-3">{{ __('Distributor') }}</th>
                            <th class="text-left p-3">{{ __('Stock') }}</th>
                            <th class="text-left p-3">{{ __('Status') }}</th>
                            <th class="text-left p-3">{{ __('Updated') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach ($this->locations as $location)
                            <tr class="hover:bg-zinc-50/70 dark:hover:bg-zinc-800/40 transition">
                                <td class="p-3">
                                    <div class="font-medium">{{ $location->name }}</div>
                                    <div class="text-xs text-zinc-500 mt-0.5">{{ str($location->address)->limit(80) }}</div>
                                </td>
                                <td class="p-3 text-zinc-500">{{ $location->distributor?->name }}</td>
                                <td class="p-3">
                                    <span class="font-semibold">{{ number_format($location->stock) }}</span>
                                </td>
                                <td class="p-3">
                                    @if ($location->is_open)
                                        <flux:badge color="green">{{ __('Open') }}</flux:badge>
                                    @else
                                        <flux:badge color="zinc">{{ __('Closed') }}</flux:badge>
                                    @endif
                                </td>
                                <td class="p-3 text-zinc-500">{{ $location->updated_at?->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $this->locations->links() }}
            </div>
        </div>
</div>
