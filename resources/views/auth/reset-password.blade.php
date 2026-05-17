<x-guest-layout>
    <div class="mb-8">
        <div class="section-label mb-4">Set new password</div>
        <h1 class="text-3xl font-bold tracking-tight text-white">Choose a new password</h1>
        <p class="mt-3 text-sm leading-6 text-slate-400">Use a secure password to protect your funding account.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="__('Email')" class="saas-label" />
            <x-text-input id="email" class="saas-input" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="saas-error" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="saas-label" />
            <x-text-input id="password" class="saas-input" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="saas-error" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="saas-label" />
            <x-text-input id="password_confirmation" class="saas-input" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="saas-error" />
        </div>

        <div class="flex items-center justify-end">
            <x-primary-button>{{ __('Reset Password') }}</x-primary-button>
        </div>
    </form>
</x-guest-layout>
