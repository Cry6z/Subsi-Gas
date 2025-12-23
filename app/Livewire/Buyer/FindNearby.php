<?php

namespace App\Livewire\Buyer;

use App\Models\Location;
use Livewire\Attributes\On;
use Livewire\Component;

class FindNearby extends Component
{
    public ?float $latitude = null;
    public ?float $longitude = null;

    public float $radiusKm = 5;

    public string $search = '';

    public int $lowStockThreshold = 5;

    #[On('geo-position')]
    public function setPosition(float $latitude, float $longitude): void
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;

        $this->broadcastNearby();
    }

    public function updatedRadiusKm(): void
    {
        $this->broadcastNearby();
    }

    public function updatedSearch(): void
    {
        $this->broadcastNearby();
    }

    public function refresh(): void
    {
        $this->broadcastNearby();
    }

    public function getLocationsProperty()
    {
        if ($this->latitude === null || $this->longitude === null) {
            return collect();
        }

        $driver = Location::query()->getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            return Location::query()
                ->get()
                ->map(function ($location) {
                    $location->distance = Location::haversineDistanceKm(
                        $this->latitude,
                        $this->longitude,
                        (float) $location->latitude,
                        (float) $location->longitude,
                    );

                    return $location;
                })
                ->when($this->search !== '', function ($items) {
                    $search = mb_strtolower($this->search);

                    return $items->filter(function ($location) use ($search) {
                        return str_contains(mb_strtolower($location->name), $search)
                            || str_contains(mb_strtolower($location->address), $search);
                    });
                })
                ->filter(fn ($location) => $location->distance <= $this->radiusKm)
                ->sortBy('distance')
                ->take(30)
                ->values();
        }

        return Location::query()
            ->nearby($this->latitude, $this->longitude, $this->radiusKm)
            ->when($this->search !== '', function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('address', 'like', '%'.$this->search.'%');
                });
            })
            ->limit(30)
            ->get();
    }

    public function getLowStockNearbyCountProperty(): int
    {
        return (int) $this->locations
            ->where('stock', '<=', $this->lowStockThreshold)
            ->count();
    }

    private function broadcastNearby(): void
    {
        if ($this->latitude === null || $this->longitude === null) {
            return;
        }

        $locations = $this->locations->map(function ($location) {
            return [
                'id' => $location->id,
                'name' => $location->name,
                'address' => $location->address,
                'latitude' => (float) $location->latitude,
                'longitude' => (float) $location->longitude,
                'stock' => (int) $location->stock,
                'is_open' => (bool) $location->is_open,
                'distance' => isset($location->distance) ? (float) $location->distance : null,
            ];
        })->values()->all();

        $this->dispatch('nearby-updated', locations: $locations, center: [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);
    }

    public function render()
    {
        return view('livewire.buyer.find-nearby')
            ->layout('components.layouts.app', ['title' => __('Find Nearby')]);
    }
}
