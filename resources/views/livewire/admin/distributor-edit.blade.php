<div class="sg-page">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Edit Distributor') }}</flux:heading>
            <flux:subheading>{{ $user->name }}</flux:subheading>
        </div>
        <flux:button variant="outline" href="{{ route('admin.distributors') }}" wire:navigate>{{ __('Back') }}</flux:button>
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle" heading="{{ __('Success') }}">
            <div class="text-sm">{{ session('status') }}</div>
        </flux:callout>
    @endif

    <div class="sg-card p-5">
        <form wire:submit.prevent="save" class="space-y-4">
            <flux:input wire:model="name" :label="__('Name')" required />
            <flux:input wire:model="email" :label="__('Email')" type="email" required />
            <flux:input wire:model="phone" :label="__('Phone (optional)')" />

            <flux:checkbox wire:model="is_active" :label="__('Active')" />

            <div class="grid gap-4 md:grid-cols-2">
                <flux:input wire:model="password" :label="__('New Password (optional)')" type="password" viewable />
                <flux:input wire:model="password_confirmation" :label="__('Confirm New Password')" type="password" viewable />
            </div>

            <div class="flex gap-2">
                <flux:button variant="outline" href="{{ route('admin.distributors') }}" wire:navigate>{{ __('Cancel') }}</flux:button>
                <flux:button variant="primary" type="submit" class="bg-gradient-to-r from-primary to-primary-500">
                    {{ __('Save') }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
