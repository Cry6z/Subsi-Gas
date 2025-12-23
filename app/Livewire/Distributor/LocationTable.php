<?php

namespace App\Livewire\Distributor;

use App\Models\Location;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class LocationTable extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public string $search = '';

    public array $selected = [];

    public bool $selectAll = false;

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    public function updatingPage(): void
    {
        $this->resetSelection();
    }

    public function updatedSelectAll(): void
    {
        if ($this->selectAll) {
            $this->selected = $this->locations
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->all();

            return;
        }

        $this->selected = [];
    }

    public function updatedSelected(): void
    {
        $pageIds = $this->locations
            ->pluck('id')
            ->map(fn ($id) => (string) $id);

        if ($pageIds->isEmpty()) {
            $this->selectAll = false;
            return;
        }

        $selected = collect($this->selected)->map(fn ($id) => (string) $id);

        $this->selectAll = $pageIds->diff($selected)->isEmpty();
    }

    public function toggleOpen(int $locationId): void
    {
        $location = Location::query()->where('distributor_id', auth()->id())->findOrFail($locationId);
        $this->authorize('update', $location);

        $location->is_open = ! $location->is_open;
        $location->save();
    }

    public function deleteLocation(int $locationId): void
    {
        $location = Location::query()->where('distributor_id', auth()->id())->findOrFail($locationId);
        $this->authorize('delete', $location);

        $location->delete();

        $this->resetSelection();
        $this->resetPage();
    }

    public function deleteSelected(): void
    {
        $ids = collect($this->selected)
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();

        if ($ids->isEmpty()) {
            return;
        }

        $locations = Location::query()
            ->where('distributor_id', auth()->id())
            ->whereIn('id', $ids)
            ->get();

        foreach ($locations as $location) {
            $this->authorize('delete', $location);
            $location->delete();
        }

        $this->resetSelection();
        $this->resetPage();
    }

    public function deleteAll(): void
    {
        $query = Location::query()
            ->where('distributor_id', auth()->id())
            ->when($this->search !== '', function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('address', 'like', '%'.$this->search.'%');
                });
            });

        $locations = $query->get();

        foreach ($locations as $location) {
            $this->authorize('delete', $location);
            $location->delete();
        }

        $this->resetSelection();
        $this->resetPage();
    }

    private function resetSelection(): void
    {
        $this->selectAll = false;
        $this->selected = [];
    }

    public function getLocationsProperty()
    {
        return Location::query()
            ->where('distributor_id', auth()->id())
            ->when($this->search !== '', function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('address', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.distributor.location-table')
            ->layout('components.layouts.app', ['title' => __('My Locations')]);
    }
}
