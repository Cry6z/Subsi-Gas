<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class MapPicker extends Component
{
    public ?float $latitude = null;

    public ?float $longitude = null;

    public string $heightClass = 'h-72';

    public function mount(?float $latitude = null, ?float $longitude = null, string $heightClass = 'h-72'): void
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->heightClass = $heightClass;

        if ($this->latitude === null || $this->longitude === null) {
            $this->latitude = -6.2000000;
            $this->longitude = 106.8166660;
        }
    }
}
