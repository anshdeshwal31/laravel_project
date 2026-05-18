<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="section-label mb-3">Account</p>
            <h2 class="text-3xl font-bold tracking-tight text-white dark:text-white light:text-slate-900">Profile Settings</h2>
            <p class="mt-2 text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">Manage your account information and security.</p>
        </div>
    </x-slot>

    <div class="px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl space-y-8">
            @if (session('status') === 'profile-updated')
                <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-emerald-200 dark:border-emerald-400/20 dark:bg-emerald-500/10 dark:text-emerald-200 light:border-emerald-200 light:bg-emerald-100 light:text-emerald-900">Profile saved successfully.</div>
            @endif

            <div class="saas-card dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="saas-card dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                @include('profile.partials.update-password-form')
            </div>

            <div class="saas-card dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>