<div x-data="{
    open: false,
    toggle() {
        this.open = !this.open
    },
    close(focusAfter) {
        this.open = false

        focusAfter && focusAfter.focus()
    }
}" x-on:keydown.escape.prevent.stop="close($refs.button)" x-on:focusin.window="!$refs.panel.contains($event.target) && close()" x-id="['dropdown-button']" class="relative">
    <button x-ref="button" @click="toggle()" :aria-expanded="open" :aria-controls="$id('dropdown-button')" type="button" {{ $attributes->merge(['class' => 'flex items-center gap-x-2']) }}>
        {{ $trigger }}
    </button>

    <div x-ref="panel" x-show="open" x-transition.origin.top.right x-on:click.outside="close($refs.button)" :id="$id('dropdown-button')" style="display: none;" class="absolute right-0 mt-2 w-64 origin-top-right rounded-xl border border-slate-200 bg-white/70 p-1.5 shadow-lg backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/70">
        {{ $slot }}
    </div>
</div>
