<x-guest-layout>
    <div class="mb-8">
        <div class="section-label mb-4">Security checkpoint</div>
        <h1 class="text-3xl font-bold tracking-tight text-white">Confirm your password</h1>
        <p class="mt-3 text-sm leading-6 text-slate-400">Confirm your password to continue into the protected area.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Password')" class="saas-label" />
            <x-text-input id="password" class="saas-input" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="saas-error" />
        </div>

        <div class="flex justify-end">
            <x-primary-button>{{ __('Confirm') }}</x-primary-button>
        </div>
    </form>
</x-guest-layout>
