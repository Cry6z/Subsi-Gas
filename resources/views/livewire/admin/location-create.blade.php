<div class="sg-page">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Create Location') }}</flux:heading>
            <flux:subheading>{{ __('Add a new distribution point on behalf of a distributor.') }}</flux:subheading>
        </div>
        <flux:button variant="outline" href="{{ route('admin.locations') }}" wire:navigate>{{ __('Back') }}</flux:button>
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle" heading="{{ __('Success') }}">
            <div class="text-sm">{{ session('status') }}</div>
        </flux:callout>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="sg-card p-5">
            <form wire:submit.prevent="save" class="space-y-4">
                <flux:select wire:model="distributor_id" :label="__('Distributor')" required>
                    <option value="">{{ __('Select distributor') }}</option>
                    @foreach ($this->distributors as $distributor)
                        <option value="{{ $distributor->id }}">
                            {{ $distributor->name }} ({{ $distributor->email }})
                        </option>
                    @endforeach
                </flux:select>

                <flux:input wire:model="name" :label="__('Name')" required />
                <flux:textarea wire:model="address" :label="__('Address')" required />

                <div class="grid grid-cols-2 gap-3">
                    <flux:input wire:model="latitude" :label="__('Latitude')" type="number" step="0.0000001" required />
                    <flux:input wire:model="longitude" :label="__('Longitude')" type="number" step="0.0000001" required />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <flux:input wire:model="stock" :label="__('Stock')" type="number" min="0" required />
                    <flux:input wire:model="capacity" :label="__('Capacity (optional)')" type="number" min="0" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <flux:input wire:model="phone" :label="__('Phone (optional)')" />
                    <flux:input wire:model="operating_hours" :label="__('Operating Hours (optional)')" placeholder="08:00-17:00" />
                </div>

                <flux:checkbox wire:model="is_open" :label="__('Open')" />

                <flux:input wire:model="photo" :label="__('Photo (optional)')" type="file" />

                <div class="flex gap-2">
                    <flux:button variant="outline" href="{{ route('admin.locations') }}" wire:navigate>{{ __('Cancel') }}</flux:button>
                    <flux:button variant="primary" type="submit">{{ __('Save Location') }}</flux:button>
                </div>
            </form>
        </div>

        <div class="sg-card p-5 space-y-3">
            <flux:heading size="lg">{{ __('Pick on Map') }}</flux:heading>
            <livewire:shared.map-picker :latitude="$latitude" :longitude="$longitude" height-class="h-[420px]" />
        </div>
    </div>
</div>
