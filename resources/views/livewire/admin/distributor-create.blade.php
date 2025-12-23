<div class="sg-page">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Create Distributor') }}</flux:heading>
            <flux:subheading>{{ __('Create a distributor account. Set a password now or send an invite to set password.') }}</flux:subheading>
        </div>
        <flux:button variant="outline" href="{{ route('admin.distributors') }}" wire:navigate>{{ __('Back') }}</flux:button>
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle" heading="{{ __('Success') }}">
            <div class="text-sm">{{ session('status') }}</div>
        </flux:callout>
    @endif

    <div class="sg-card p-5">
        <form wire:submit.prevent="create" class="space-y-4">
            <flux:input wire:model="name" :label="__('Name')" required />
            <flux:input wire:model="email" :label="__('Email')" type="email" required />
            <flux:input wire:model="phone" :label="__('Phone (optional)')" />
            <flux:textarea wire:model="initial_note" :label="__('Initial Note (optional)')" rows="3" />

            <div class="grid gap-4 md:grid-cols-2">
                <flux:input wire:model="password" :label="__('Password')" type="password" viewable />
                <flux:input wire:model="password_confirmation" :label="__('Confirm Password')" type="password" viewable />
            </div>

            <div class="flex gap-2">
                <flux:button variant="primary" type="submit" class="bg-gradient-to-r from-primary to-primary-500">
                    {{ __('Create Distributor') }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
