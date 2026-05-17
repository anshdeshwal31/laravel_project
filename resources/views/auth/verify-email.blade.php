<x-guest-layout>
    <div class="mb-8">
        <div class="section-label mb-4">Verify email</div>
        <h1 class="text-3xl font-bold tracking-tight text-white">Check your inbox</h1>
        <p class="mt-3 text-sm leading-6 text-slate-400">Verify your email to unlock the full startup financing workspace.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>{{ __('Resend Verification Email') }}</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="secondary-button">Log Out</button>
        </form>
    </div>
</x-guest-layout>
