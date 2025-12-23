<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class DistributorEdit extends Component
{
    public User $user;

    public string $name = '';

    public string $email = '';

    public ?string $phone = null;

    public bool $is_active = true;

    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function mount(User $user): void
    {
        if ($user->role !== 'distributor') {
            abort(404);
        }

        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->is_active = (bool) $user->is_active;
    }

    public function save(): void
    {
        $this->password = $this->password !== null ? trim($this->password) : null;
        $this->password_confirmation = $this->password_confirmation !== null ? trim($this->password_confirmation) : null;

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class, 'email')->ignore($this->user->id),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $this->user->name = $validated['name'];
        $this->user->email = $validated['email'];
        $this->user->phone = $validated['phone'] ?? null;
        $this->user->is_active = (bool) $validated['is_active'];

        $hasPassword = ($validated['password'] ?? null) !== null && $validated['password'] !== '';
        if ($hasPassword) {
            $this->user->password = Hash::make($validated['password']);
        }

        $this->user->save();

        session()->flash('status', 'Distributor berhasil diperbarui.');

        $this->redirect(route('admin.distributors'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.distributor-edit')
            ->layout('components.layouts.app', ['title' => __('Edit Distributor')]);
    }
}
