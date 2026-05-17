<x-guest-layout>
    <div class="mb-8">
        <div class="section-label mb-4">Secure access</div>
        <h1 class="text-3xl font-bold tracking-tight text-white">Welcome back</h1>
        <p class="mt-3 text-sm leading-6 text-slate-400">Sign in to manage funding requests, opportunities, and investor conversations.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="saas-label" />
            <x-text-input id="email" class="saas-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="saas-error" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="saas-label" />
            <x-text-input id="password" class="saas-input" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="saas-error" />
        </div>

        <div class="flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex items-center gap-3 text-sm text-slate-300">
                <input id="remember_me" type="checkbox" class="rounded border-white/20 bg-white/5 text-cyan-400 shadow-sm focus:ring-cyan-400/40" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-cyan-300 transition hover:text-cyan-200" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('register') }}" class="secondary-button">Create account</a>
            <x-primary-button>{{ __('Log in') }}</x-primary-button>
        </div>
    </form>
    <div class="mt-6 text-sm text-slate-400">
        <div class="mb-2">Quick demo logins:</div>
        <div class="flex gap-3">
            <button id="demo-admin" class="secondary-button">Use admin</button>
            <button id="demo-investor" class="secondary-button">Use investor</button>
            <button id="demo-startup" class="secondary-button">Use startup</button>
        </div>
    </div>

    <script>
        (function(){
            const demoAccounts = {
                admin: { email: 'admin@fundhub.test', password: 'password' },
                investor: { email: 'investor@fundhub.test', password: 'password' },
                startup: { email: 'startup@fundhub.test', password: 'password' },
            };

            function fillAndSubmit(account){
                const e = document.getElementById('email');
                const p = document.getElementById('password');
                if(!e || !p) return;
                e.value = account.email;
                p.value = account.password;
                // Submit the enclosing form
                e.closest('form')?.submit();
            }

            document.getElementById('demo-admin')?.addEventListener('click', function(e){ e.preventDefault(); fillAndSubmit(demoAccounts.admin); });
            document.getElementById('demo-investor')?.addEventListener('click', function(e){ e.preventDefault(); fillAndSubmit(demoAccounts.investor); });
            document.getElementById('demo-startup')?.addEventListener('click', function(e){ e.preventDefault(); fillAndSubmit(demoAccounts.startup); });
        })();
    </script>
</x-guest-layout>
