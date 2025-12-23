@php
    $color = match ($this->level) {
        'low' => 'red',
        'medium' => 'yellow',
        default => 'green',
    };
@endphp

<flux:badge :color="$color">
    {{ __('Stock') }}: {{ number_format($stock) }}
</flux:badge>
