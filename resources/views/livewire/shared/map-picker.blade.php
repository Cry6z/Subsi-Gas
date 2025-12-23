<div class="sg-card overflow-hidden">
    <div
        data-map-picker
        data-lat="{{ $latitude }}"
        data-lng="{{ $longitude }}"
        class="w-full {{ $heightClass }}"
    >
        <div class="w-full h-full" data-map-picker-canvas wire:ignore></div>
    </div>

    <div class="px-4 py-3 border-t border-zinc-200 dark:border-zinc-700 text-xs text-zinc-500">
        {{ __('Click or drag marker to set coordinates.') }}
    </div>
</div>
