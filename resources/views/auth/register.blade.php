<x-guest-layout>
    <div class="mb-8">
        <div class="section-label mb-4">Create account</div>
        <h1 class="text-3xl font-bold tracking-tight text-white">Start your funding journey</h1>
        <p class="mt-3 text-sm leading-6 text-slate-400">Join as a startup or investor and start matching with the right opportunities.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" class="saas-label" />
            <x-text-input id="name" class="saas-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="saas-error" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="saas-label" />
            <x-text-input id="email" class="saas-input" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="saas-error" />
        </div>

        <div>
            <x-input-label for="role" :value="__('I am a')" class="saas-label" />
            <select id="role" name="role" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" required>
                <option value="startup" @selected(old('role') === 'startup')>Startup</option>
                <option value="investor" @selected(old('role') === 'investor')>Investor</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="saas-error" />
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

        <div class="flex items-center justify-between gap-4 pt-2">
            <a class="text-sm font-medium text-cyan-300 transition hover:text-cyan-200" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button>{{ __('Register') }}</x-primary-button>
        </div>
    </form>
</x-guest-layout>
