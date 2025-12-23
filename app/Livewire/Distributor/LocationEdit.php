<?php

namespace App\Livewire\Distributor;

use App\Models\Location;
use App\Models\StockLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class LocationEdit extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;

    public Location $location;

    public string $name = '';
    public string $address = '';
    public ?float $latitude = null;
    public ?float $longitude = null;
    public int $stock = 0;
    public ?int $capacity = null;
    public bool $is_open = true;
    public ?string $phone = null;
    public ?string $operating_hours = null;

    public $photo;

    public function mount(Location $location): void
    {
        $this->location = $location;
        $this->authorize('update', $this->location);

        $this->name = $location->name;
        $this->address = $location->address;
        $this->latitude = (float) $location->latitude;
        $this->longitude = (float) $location->longitude;
        $this->stock = (int) $location->stock;
        $this->capacity = $location->capacity;
        $this->is_open = (bool) $location->is_open;
        $this->phone = $location->phone;
        $this->operating_hours = $location->operating_hours;
    }

    #[On('map-picked')]
    public function setCoordinates(float $latitude, float $longitude): void
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function save(): void
    {
        $this->authorize('update', $this->location);

        $oldStock = (int) $this->location->stock;

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'stock' => ['required', 'integer', 'min:0'],
            'capacity' => ['nullable', 'integer', 'min:0'],
            'is_open' => ['required', 'boolean'],
            'phone' => ['nullable', 'string', 'max:50'],
            'operating_hours' => ['nullable', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($this->photo) {
            if ($this->location->photo) {
                Storage::disk('public')->delete($this->location->photo);
            }

            $this->location->photo = $this->storePhotoAsWebp($this->photo);
        }

        $this->location->fill([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'latitude' => (float) $validated['latitude'],
            'longitude' => (float) $validated['longitude'],
            'stock' => (int) $validated['stock'],
            'capacity' => $validated['capacity'] ?? null,
            'is_open' => (bool) $validated['is_open'],
            'phone' => $validated['phone'] ?? null,
            'operating_hours' => $validated['operating_hours'] ?? null,
        ]);

        $this->location->save();

        $diff = (int) $this->location->stock - $oldStock;
        if ($diff !== 0) {
            StockLog::create([
                'location_id' => $this->location->id,
                'change_amount' => $diff,
                'note' => 'Stock update',
            ]);
        }

        $this->redirect(route('distributor.locations'), navigate: true);
    }

    public function render()
    {
        return view('livewire.distributor.location-edit')
            ->layout('components.layouts.app', ['title' => __('Edit Location')]);
    }

    private function storePhotoAsWebp($uploaded): string
    {
        $imageData = @file_get_contents($uploaded->getRealPath());
        if ($imageData === false) {
            return $uploaded->storePublicly('locations', 'public');
        }

        $image = @imagecreatefromstring($imageData);
        if (! $image) {
            return $uploaded->storePublicly('locations', 'public');
        }

        if (function_exists('imagepalettetotruecolor')) {
            imagepalettetotruecolor($image);
        }
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $fileName = 'locations/'.Str::uuid().'.webp';

        ob_start();
        $success = imagewebp($image, null, 85);
        $webpData = ob_get_clean();
        imagedestroy($image);

        if (! $success || $webpData === false) {
            return $uploaded->storePublicly('locations', 'public');
        }

        Storage::disk('public')->put($fileName, $webpData);

        return $fileName;
    }
}
