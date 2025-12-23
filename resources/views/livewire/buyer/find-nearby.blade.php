<div class="sg-page" wire:poll.30s="refresh" data-buyer-nearby>
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <flux:heading size="xl">{{ __('Find Gas Subsidy Nearby') }}</flux:heading>
                <flux:subheading>{{ __('Use your location to find the nearest distributor') }}</flux:subheading>
            </div>

            <div class="flex flex-col gap-2 md:flex-row md:items-end">
                <div class="w-full md:w-[220px]">
                    <flux:select wire:model.live="radiusKm" :label="__('Distance')">
                        <option value="1">1 km</option>
                        <option value="3">3 km</option>
                        <option value="5">5 km</option>
                    </flux:select>
                </div>
                <div class="w-full md:w-[320px]">
                    <flux:input wire:model.live="search" :label="__('Search')" placeholder="name / address" />
                </div>
            </div>
        </div>

        @if ($this->lowStockNearbyCount > 0)
            <flux:callout
                variant="warning"
                icon="triangle-alert"
                heading="{{ __('Low stock alert') }}"
            >
                <div class="text-sm">
                    {{ __('Some nearby locations have low stock (<= :threshold).', ['threshold' => $lowStockThreshold]) }}
                </div>
            </flux:callout>
        @endif

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="sg-card overflow-hidden">
                <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
                    <div class="font-semibold">{{ __('Map') }}</div>
                    <div class="text-xs text-zinc-500 mt-1">{{ __('Markers are color-coded by stock level') }}</div>
                </div>
                <div class="h-[420px]" data-buyer-map>
                    <div class="w-full h-full" data-buyer-map-canvas wire:ignore></div>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                @if ($latitude === null || $longitude === null)
                    <div class="sg-card p-4">
                        <div class="text-sm text-zinc-500">
                            {{ __('Waiting for location permission...') }}
                        </div>
                    </div>
                @endif

                @forelse ($this->locations as $location)
                    <div class="sg-card p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-semibold">{{ $location->name }}</div>
                                <div class="text-sm text-zinc-500 mt-1">{{ str($location->address)->limit(120) }}</div>
                            </div>

                            <div class="text-right">
                                <livewire:shared.stock-badge :stock="$location->stock" :threshold="$lowStockThreshold" :key="'stock-'.$location->id" />
                                <div class="text-xs text-zinc-500 mt-1">
                                    @if (isset($location->distance))
                                        {{ number_format($location->distance, 2) }} km
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <div>
                                @if ($location->is_open)
                                    <flux:badge color="green">{{ __('Open') }}</flux:badge>
                                @else
                                    <flux:badge color="zinc">{{ __('Closed') }}</flux:badge>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                <flux:button
                                    variant="outline"
                                    href="https://www.google.com/maps?q={{ $location->latitude }},{{ $location->longitude }}"
                                    target="_blank"
                                >
                                    {{ __('Directions') }}
                                </flux:button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="sg-card p-4">
                        <div class="text-sm text-zinc-500">
                            {{ __('No locations found in selected radius.') }}
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
</div>
