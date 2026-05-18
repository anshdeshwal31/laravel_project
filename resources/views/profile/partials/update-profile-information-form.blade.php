<section>
    <header class="mb-6">
        <h2 class="text-lg font-semibold text-white dark:text-white light:text-slate-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>
    @endif

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" class="saas-label dark:text-slate-400 light:text-slate-700" />
            <x-text-input id="name" name="name" type="text" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="saas-error" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="saas-label dark:text-slate-400 light:text-slate-700" />
            <x-text-input id="email" name="email" type="email" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="saas-error" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-2xl border border-amber-400/20 bg-amber-500/10 px-4 py-3 dark:border-amber-400/20 dark:bg-amber-500/10 light:border-amber-200 light:bg-amber-50">
                    <p class="text-sm text-amber-200 dark:text-amber-200 light:text-amber-800">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="ml-1 underline font-medium text-cyan-300 dark:text-cyan-300 light:text-cyan-600 hover:text-cyan-200 dark:hover:text-cyan-200 light:hover:text-cyan-700 rounded focus:outline-none focus:ring-2 focus:ring-cyan-400/30">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-emerald-300 dark:text-emerald-300 light:text-emerald-700">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-300 dark:text-emerald-300 light:text-emerald-700">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
