<div class="sg-page">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Distributor Management') }}</flux:heading>
            <flux:subheading>{{ __('Create, invite, and manage distributor accounts') }}</flux:subheading>
        </div>

        <div class="flex flex-col gap-2 md:flex-row md:items-end">
            <div class="w-full md:w-[320px]">
                <flux:input wire:model.live="search" :label="__('Search')" placeholder="name / email / phone" />
            </div>
            <flux:button variant="primary" href="{{ route('admin.distributors.create') }}" wire:navigate class="bg-gradient-to-r from-primary to-primary-500">
                {{ __('Create Distributor') }}
            </flux:button>
        </div>
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle" heading="{{ __('Info') }}">
            <div class="text-sm">{{ session('status') }}</div>
        </flux:callout>
    @endif

    <div class="sg-card overflow-hidden backdrop-blur">
        <div class="flex flex-col gap-2 p-3 md:flex-row md:items-center md:justify-between border-b border-zinc-200/70 dark:border-zinc-800">
            <div class="text-sm text-zinc-600 dark:text-zinc-300">
                {{ __('Selected:') }} <span class="font-semibold">{{ count($this->selected) }}</span>
                <span class="text-zinc-400">/</span>
                <span class="text-zinc-500">{{ $this->distributors->total() }}</span>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-2">
                <flux:button
                    variant="danger"
                    size="sm"
                    :disabled="count($this->selected) === 0"
                    wire:click="deleteSelected"
                    onclick="if(!confirm('Hapus distributor yang dipilih?')){event.preventDefault();event.stopImmediatePropagation();}"
                >
                    {{ __('Delete Selected') }}
                </flux:button>

                <flux:button
                    variant="danger"
                    size="sm"
                    wire:click="deleteAll"
                    onclick="if(!confirm('Hapus SEMUA distributor (sesuai filter pencarian saat ini)?')){event.preventDefault();event.stopImmediatePropagation();}"
                >
                    {{ __('Delete All') }}
                </flux:button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-950/40 text-zinc-500">
                    <tr>
                        <th class="text-left p-3 w-10">
                            <input
                                type="checkbox"
                                class="h-4 w-4 rounded border-zinc-300 dark:border-zinc-600 text-primary focus:ring-primary"
                                wire:model.live="selectAll"
                            />
                        </th>
                        <th class="text-left p-3">{{ __('Name') }}</th>
                        <th class="text-left p-3">{{ __('Email') }}</th>
                        <th class="text-left p-3">{{ __('Phone') }}</th>
                        <th class="text-left p-3">{{ __('Created') }}</th>
                        <th class="text-left p-3">{{ __('Status') }}</th>
                        <th class="text-right p-3">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach ($this->distributors as $user)
                        <tr class="hover:bg-zinc-50/70 dark:hover:bg-zinc-800/40 transition">
                            <td class="p-3 align-top">
                                <input
                                    type="checkbox"
                                    class="h-4 w-4 rounded border-zinc-300 dark:border-zinc-600 text-primary focus:ring-primary"
                                    wire:model.live="selected"
                                    value="{{ $user->id }}"
                                />
                            </td>
                            <td class="p-3 font-medium">{{ $user->name }}</td>
                            <td class="p-3 text-zinc-500">{{ $user->email }}</td>
                            <td class="p-3 text-zinc-500">{{ $user->phone ?? '-' }}</td>
                            <td class="p-3 text-zinc-500">{{ $user->created_at?->diffForHumans() }}</td>
                            <td class="p-3">
                                @if ($user->is_active)
                                    <flux:badge color="green">{{ __('Active') }}</flux:badge>
                                @else
                                    <flux:badge color="zinc">{{ __('Inactive') }}</flux:badge>
                                @endif
                            </td>
                            <td class="p-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <flux:button variant="outline" size="sm" href="{{ route('admin.distributors.edit', $user) }}" wire:navigate>
                                        {{ __('Edit') }}
                                    </flux:button>
                                    <flux:button variant="outline" size="sm" wire:click="toggleActive({{ $user->id }})">
                                        {{ $user->is_active ? __('Deactivate') : __('Activate') }}
                                    </flux:button>
                                    <flux:button variant="danger" size="sm" wire:click="deleteDistributor({{ $user->id }})">
                                        {{ __('Delete') }}
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-3">
            {{ $this->distributors->links() }}
        </div>
    </div>
</div>
