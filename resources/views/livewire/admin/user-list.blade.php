<div class="sg-page">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <flux:heading size="xl">{{ __('Manage Users') }}</flux:heading>
                <flux:subheading>{{ __('Assign roles and manage accounts') }}</flux:subheading>
            </div>

            <div class="w-full md:w-[320px]">
                <flux:input wire:model.live="search" :label="__('Search')" placeholder="name / email" />
            </div>
        </div>

        <div class="sg-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-950/40 text-zinc-500">
                        <tr>
                            <th class="text-left p-3">{{ __('Name') }}</th>
                            <th class="text-left p-3">{{ __('Email') }}</th>
                            <th class="text-left p-3">{{ __('Role') }}</th>
                            <th class="text-right p-3">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @foreach ($this->users as $user)
                            <tr class="hover:bg-zinc-50/70 dark:hover:bg-zinc-800/40 transition">
                                <td class="p-3 font-medium">{{ $user->name }}</td>
                                <td class="p-3 text-zinc-500">{{ $user->email }}</td>
                                <td class="p-3">
                                    <flux:select
                                        wire:change="updateRole({{ $user->id }}, $event.target.value)"
                                        :value="$user->role"
                                        class="min-w-[160px]"
                                    >
                                        <option value="admin">admin</option>
                                        <option value="distributor">distributor</option>
                                    </flux:select>
                                </td>
                                <td class="p-3 text-right">
                                    <flux:button
                                        variant="danger"
                                        size="sm"
                                        wire:click="deleteUser({{ $user->id }})"
                                        :disabled="$user->id === auth()->id()"
                                    >
                                        {{ __('Delete') }}
                                    </flux:button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $this->users->links() }}
            </div>
        </div>
</div>
