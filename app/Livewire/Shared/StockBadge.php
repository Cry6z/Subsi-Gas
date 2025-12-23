<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class StockBadge extends Component
{
    public int $stock;

    public int $threshold = 5;

    public function mount(int $stock, int $threshold = 5): void
    {
        $this->stock = $stock;
        $this->threshold = $threshold;
    }

    public function getLevelProperty(): string
    {
        if ($this->stock <= $this->threshold) {
            return 'low';
        }

        if ($this->stock <= 20) {
            return 'medium';
        }

        return 'high';
    }
}
