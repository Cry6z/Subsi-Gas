<?php

namespace App\Livewire\Admin;

use App\Models\Location;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public int $totalUsers = 0;
    public int $totalDistributors = 0;
    public int $totalLocations = 0;
    public int $totalStock = 0;

    public function mount(): void
    {
        $this->refreshStats();
    }

    public function refreshStats(): void
    {
        $this->totalUsers = User::query()->count();
        $this->totalDistributors = User::query()->where('role', 'distributor')->count();
        $this->totalLocations = Location::query()->count();
        $this->totalStock = (int) Location::query()->sum('stock');
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('components.layouts.app', ['title' => __('Admin Dashboard')]);
    }
}
