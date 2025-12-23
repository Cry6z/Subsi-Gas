<?php

namespace App\Livewire\Admin;

use App\Livewire\Shared\MapPicker;
use App\Models\Location;
use App\Models\StockLog;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class LocationCreate extends Component
{
    use WithFileUploads;
    use AuthorizesRequests;

    public ?int $distributor_id = null;

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

    #[On('map-picked')]
    public function setCoordinates(float $latitude, float $longitude): void
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function save(): void
    {
        $this->authorize('create', Location::class);

        $validated = $this->validate([
            'distributor_id' => ['required', 'integer', 'exists:users,id'],
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

        $path = $this->storePhotoAsWebp($this->photo);

        $location = Location::create([
            'distributor_id' => (int) $validated['distributor_id'],
            'name' => $validated['name'],
            'address' => $validated['address'],
            'latitude' => (float) $validated['latitude'],
            'longitude' => (float) $validated['longitude'],
            'stock' => (int) $validated['stock'],
            'capacity' => $validated['capacity'] ?? null,
            'is_open' => (bool) $validated['is_open'],
            'phone' => $validated['phone'] ?? null,
            'photo' => $path,
            'operating_hours' => $validated['operating_hours'] ?? null,
        ]);

        if ($location->stock !== 0) {
            StockLog::create([
                'location_id' => $location->id,
                'change_amount' => $location->stock,
                'note' => 'Initial stock (admin create)',
            ]);
        }

        session()->flash('status', __('Location successfully created.'));

        $this->redirect(route('admin.locations'), navigate: true);
    }

    public function getDistributorsProperty(): Collection
    {
        return User::query()
            ->where('role', 'distributor')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }

    public function render()
    {
        return view('livewire.admin.location-create')
            ->layout('components.layouts.app', ['title' => __('Create Location')]);
    }

    private function storePhotoAsWebp($uploaded): ?string
    {
        if (! $uploaded) {
            return null;
        }

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
