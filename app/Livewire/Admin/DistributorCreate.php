<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class DistributorCreate extends Component
{
    public string $name = '';

    public string $email = '';

    public ?string $phone = null;

    public ?string $initial_note = null;

    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function create(): void
    {
        $this->password = $this->password !== null ? trim($this->password) : null;
        $this->password_confirmation = $this->password_confirmation !== null ? trim($this->password_confirmation) : null;

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class, 'email')],
            'phone' => ['nullable', 'string', 'max:50'],
            'initial_note' => ['nullable', 'string', 'max:500'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $hasPassword = ($validated['password'] ?? null) !== null && $validated['password'] !== '';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => 'distributor',
            'password' => $hasPassword
                ? Hash::make($validated['password'])
                : Hash::make(Str::random(40)),
            'created_by_admin_id' => auth()->id(),
            'is_active' => true,
        ]);

        if (($validated['initial_note'] ?? null) !== null) {
            logger()->info('Admin created distributor', [
                'admin_id' => auth()->id(),
                'distributor_id' => $user->id,
                'note' => $validated['initial_note'],
            ]);
        }

        if (! $hasPassword) {
            Password::broker()->sendResetLink(['email' => $user->email]);
            session()->flash('status', 'Distributor berhasil dibuat dan undangan sudah dikirim.');
        } else {
            session()->flash('status', 'Distributor berhasil dibuat.');
        }

        $this->redirect(route('admin.distributors'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.distributor-create')
            ->layout('components.layouts.app', ['title' => __('Create Distributor')]);
    }
}
