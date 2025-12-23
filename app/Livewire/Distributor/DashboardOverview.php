<?php

namespace App\Livewire\Distributor;

use App\Models\Location;
use Livewire\Component;

class DashboardOverview extends Component
{
    public int $totalLocations = 0;
    public int $totalStock = 0;
    public int $lowStockCount = 0;

    public function mount(): void
    {
        $this->refreshStats();
    }

    public function refreshStats(): void
    {
        $query = Location::query()->where('distributor_id', auth()->id());

        $this->totalLocations = $query->count();
        $this->totalStock = (int) $query->sum('stock');
        $this->lowStockCount = (int) $query->where('stock', '<=', 5)->count();
    }

    public function render()
    {
        return view('livewire.distributor.dashboard-overview')
            ->layout('components.layouts.app', ['title' => __('Distributor Dashboard')]);
    }
}
