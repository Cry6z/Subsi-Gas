<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updateRole(int $userId, string $role): void
    {
        $validated = validator(['role' => $role], [
            'role' => ['required', Rule::in(['admin', 'distributor'])],
        ])->validate();

        $user = User::query()->findOrFail($userId);
        $user->role = $validated['role'];
        $user->save();
    }

    public function deleteUser(int $userId): void
    {
        $user = User::query()->findOrFail($userId);

        if ((int) $user->id === (int) auth()->id()) {
            return;
        }

        $user->delete();
        $this->resetPage();
    }

    public function getUsersProperty()
    {
        return User::query()
            ->when($this->search !== '', function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.user-list')
            ->layout('components.layouts.app', ['title' => __('Manage Users')]);
    }
}
