<section>
    <header class="mb-6">
        <h2 class="text-lg font-semibold text-white dark:text-white light:text-slate-900">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-1 text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="saas-label dark:text-slate-400 light:text-slate-700" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="saas-error" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" class="saas-label dark:text-slate-400 light:text-slate-700" />
            <x-text-input id="update_password_password" name="password" type="password" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="saas-error" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="saas-label dark:text-slate-400 light:text-slate-700" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="saas-error" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-300 dark:text-emerald-300 light:text-emerald-700">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
