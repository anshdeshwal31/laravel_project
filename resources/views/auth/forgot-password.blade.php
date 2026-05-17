<x-guest-layout>
    <div class="mb-8">
        <div class="section-label mb-4">Account recovery</div>
        <h1 class="text-3xl font-bold tracking-tight text-white">Reset your password</h1>
        <p class="mt-3 text-sm leading-6 text-slate-400">Enter your email and we will send a secure reset link.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="saas-label" />
            <x-text-input id="email" class="saas-input" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="saas-error" />
        </div>

        <div class="flex items-center justify-end">
            <x-primary-button>{{ __('Email Password Reset Link') }}</x-primary-button>
        </div>
    </form>
</x-guest-layout>
