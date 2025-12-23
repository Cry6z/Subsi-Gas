<div class="sg-page">
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Import Locations') }}</flux:heading>
            <flux:subheading>{{ __('Upload CSV or Excel to create multiple locations at once.') }}</flux:subheading>
        </div>
        <flux:button variant="outline" href="{{ route('admin.locations') }}" wire:navigate>{{ __('Back') }}</flux:button>
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle" heading="{{ __('Import Complete') }}">
            <div class="text-sm">{{ session('status') }}</div>
        </flux:callout>
    @endif

    @if ($errors->any())
        <flux:callout variant="danger" icon="exclamation-circle" heading="{{ __('Import Errors') }}" class="space-y-2">
            @foreach ($errors->all() as $error)
                <div class="text-sm">{{ $error }}</div>
            @endforeach
        </flux:callout>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="sg-card p-5">
            <form wire:submit.prevent="import" class="space-y-4">
                <flux:input wire:model="file" :label="__('File (CSV/XLS/XLSX)')" type="file" required />

                <div class="text-xs text-zinc-500 dark:text-zinc-400 space-y-2">
                    <p>{{ __('Required columns: distributor_email, name, address, latitude, longitude, stock.') }}</p>
                    <p>{{ __('Optional columns: capacity, is_open, phone, operating_hours.') }}</p>
                </div>

                <div class="flex gap-2">
                    <flux:button variant="primary" type="submit">{{ __('Import Locations') }}</flux:button>
                </div>
            </form>
        </div>

        <div class="sg-card p-5 space-y-4">
            <flux:heading size="lg">{{ __('Sample CSV structure') }}</flux:heading>
            <pre class="rounded-2xl bg-zinc-900/90 p-4 text-xs text-white overflow-auto">
distributor_email,name,address,latitude,longitude,stock,capacity,is_open,phone,operating_hours
admin@example.com,Pangkalan A,"Jl. Merpati No. 12", -6.20, 106.81, 40, 50, true, 082312345678, 08:00-17:00
admin@example.com,Pangkalan B,"Jl. Kenari No. 5", -6.22, 106.82, 30, , false, , "07:00-15:00"
            </pre>

            @if ($result['created'] || $result['skipped'])
                <div class="text-sm space-y-1">
                    <div>{{ __('Created: :count', ['count' => $result['created']]) }}</div>
                    <div>{{ __('Skipped: :count', ['count' => $result['skipped']]) }}</div>
                </div>

                @if (! empty($result['errors']))
                    <div class="text-xs text-rose-500 space-y-1">
                        @foreach ($result['errors'] as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
