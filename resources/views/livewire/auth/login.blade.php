<x-layouts.auth>
    <div class="w-full max-w-sm">
        <x-auth-header :title="__('Masuk ke akun Anda')" :description="__('Gunakan email dan kata sandi yang terdaftar.')" />

        <div class="mt-6">
            <x-auth-session-status class="text-center" :status="session('status')" />
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-6">
            @csrf

            <div class="space-y-3">
                <flux:input
                    name="email"
                    :value="old('email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    :label="__('Alamat Email')"
                    placeholder="nama@perusahaan.com"
                >
                    <x-slot:prefix>
                        <x-heroicon-o-envelope class="size-5 text-slate-400" />
                    </x-slot:prefix>
                </flux:input>
            </div>

            <div class="space-y-3">
                <flux:input
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    :label="__('Kata Sandi')"
                    placeholder="••••••••"
                    viewable
                >
                    <x-slot:prefix>
                        <x-heroicon-o-lock-closed class="size-5 text-slate-400" />
                    </x-slot:prefix>
                </flux:input>
            </div>

            <div class="flex items-center justify-between gap-4">
                <flux:checkbox name="remember" :label="__('Ingat saya')" :checked="old('remember')" />
                @if (Route::has('password.request'))
                    <flux:link class="text-sm font-semibold" :href="route('password.request')" wire:navigate>
                        {{ __('Lupa kata sandi?') }}
                    </flux:link>
                @endif
            </div>

            <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                <span class="inline-flex items-center justify-center gap-2">
                    {{ __('Masuk') }}
                </span>
            </flux:button>
        </form>
    </div>
</x-layouts.auth>
