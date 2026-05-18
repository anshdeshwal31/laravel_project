<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="section-label mb-3">Opportunities</p>
            <h2 class="text-3xl font-bold tracking-tight text-white dark:text-white light:text-slate-900">Post Funding Opportunity</h2>
            <p class="mt-2 text-sm text-slate-400 dark:text-slate-400 light:text-slate-600">Create a new listing that startups can discover and request funding from.</p>
        </div>
    </x-slot>

    <div class="px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">
            @if ($errors->any())
                <div class="mb-4 rounded-2xl border border-rose-400/20 bg-rose-500/10 px-4 py-3 text-rose-200 dark:border-rose-400/20 dark:bg-rose-500/10 dark:text-rose-200 light:border-rose-200 light:bg-rose-50 light:text-rose-800">
                    Please fix the errors below before submitting.
                </div>
            @endif

            <div class="saas-card dark:bg-white/5 dark:border-white/10 light:bg-white light:border-slate-200">
                <form method="POST" action="{{ route('opportunities.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="title" value="Title" class="saas-label dark:text-slate-400 light:text-slate-700" />
                        <x-text-input id="title" name="title" type="text" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" :value="old('title')" required />
                        <x-input-error :messages="$errors->get('title')" class="saas-error" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Description" class="saas-label dark:text-slate-400 light:text-slate-700" />
                        <textarea id="description" name="description" rows="5" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" required>{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="saas-error" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="industry" value="Industry" class="saas-label dark:text-slate-400 light:text-slate-700" />
                            <x-text-input id="industry" name="industry" type="text" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" :value="old('industry')" required />
                            <x-input-error :messages="$errors->get('industry')" class="saas-error" />
                        </div>
                        <div>
                            <x-input-label for="stage" value="Stage" class="saas-label dark:text-slate-400 light:text-slate-700" />
                            <select id="stage" name="stage" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" required>
                                @php($selected = old('stage'))
                                @foreach(['pre-seed', 'seed', 'series-a', 'series-b', 'growth', 'any'] as $s)
                                    <option value="{{ $s }}" @selected($selected === $s)>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('stage')" class="saas-error" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="min_amount" value="Min Amount" class="saas-label dark:text-slate-400 light:text-slate-700" />
                            <x-text-input id="min_amount" name="min_amount" type="number" step="0.01" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" :value="old('min_amount')" required />
                            <x-input-error :messages="$errors->get('min_amount')" class="saas-error" />
                        </div>
                        <div>
                            <x-input-label for="max_amount" value="Max Amount" class="saas-label dark:text-slate-400 light:text-slate-700" />
                            <x-text-input id="max_amount" name="max_amount" type="number" step="0.01" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" :value="old('max_amount')" required />
                            <x-input-error :messages="$errors->get('max_amount')" class="saas-error" />
                        </div>
                        <div>
                            <x-input-label for="location" value="Location" class="saas-label dark:text-slate-400 light:text-slate-700" />
                            <x-text-input id="location" name="location" type="text" class="saas-input dark:bg-white/5 dark:border-white/10 dark:text-white light:bg-white light:border-slate-300 light:text-slate-900" :value="old('location')" />
                            <x-input-error :messages="$errors->get('location')" class="saas-error" />
                        </div>
                    </div>

                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="h-5 w-5 rounded border-slate-400/30 bg-white/10 text-indigo-500 focus:ring-2 focus:ring-cyan-400/30 light:border-slate-300 light:bg-white" />
                        <span class="text-sm text-slate-300 dark:text-slate-300 light:text-slate-700">Active Listing</span>
                    </label>

                    <div class="flex items-center gap-3 pt-2">
                        <x-primary-button>Publish Listing</x-primary-button>
                        <a href="{{ route('opportunities.index') }}" class="secondary-button">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
