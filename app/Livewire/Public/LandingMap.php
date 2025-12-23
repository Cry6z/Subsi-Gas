<?php

namespace App\Livewire\Public;

use App\Models\Location;
use Livewire\Attributes\On;
use Livewire\Component;

class LandingMap extends Component
{
    public string $q = '';

    public ?float $latitude = null;

    public ?float $longitude = null;

    #[On('geo-position')]
    public function setPosition(float $latitude, float $longitude): void
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;

        $this->broadcastLocations();
    }

    public function updatedQ(): void
    {
        $this->broadcastLocations();
    }

    public function refresh(): void
    {
        $this->broadcastLocations();
    }

    public function getLocationsProperty()
    {
        $query = Location::query();

        if ($this->latitude !== null && $this->longitude !== null) {
            $driver = $query->getConnection()->getDriverName();

            if ($driver === 'sqlite') {
                return $query
                    ->when($this->q !== '', function ($q) {
                        $q->where(function ($q) {
                            $q->where('name', 'like', '%'.$this->q.'%')
                                ->orWhere('address', 'like', '%'.$this->q.'%');
                        });
                    })
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
                    ->sortBy('distance')
                    ->take(30)
                    ->values();
            }

            $query->withDistance($this->latitude, $this->longitude)
                ->orderBy('distance');
        }

        return $query
            ->when($this->q !== '', function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->q.'%')
                        ->orWhere('address', 'like', '%'.$this->q.'%');
                });
            })
            ->when($this->latitude === null || $this->longitude === null, function ($q) {
                $q->orderByDesc('updated_at');
            })
            ->limit(30)
            ->get();
    }

    private function broadcastLocations(): void
    {
        $locations = $this->locations->map(function ($location) {
            return [
                'id' => $location->id,
                'name' => $location->name,
                'address' => $location->address,
                'latitude' => (float) $location->latitude,
                'longitude' => (float) $location->longitude,
                'stock' => (int) $location->stock,
                'is_open' => (bool) $location->is_open,
                'photo_url' => $location->photo_url,
                'distance' => isset($location->distance) ? (float) $location->distance : null,
                'updated_at' => optional($location->updated_at)->toISOString(),
                'updated_human' => optional($location->updated_at)?->diffForHumans(),
            ];
        })->values()->all();

        $this->dispatch('public-locations-updated', locations: $locations, center: [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);
    }

    public function mount(): void
    {
        $this->broadcastLocations();
    }

    public function render()
    {
        return view('livewire.public.landing-map')
            ->layout('components.layouts.public', ['title' => __('Distribusi Gas Subsidi')]);
    }
}
