<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Post Funding Opportunity</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('opportunities.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="title" value="Title" />
                        <x-text-input id="title" name="title" type="text" class="block mt-1 w-full" :value="old('title')" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="5" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="industry" value="Industry" />
                            <x-text-input id="industry" name="industry" type="text" class="block mt-1 w-full" :value="old('industry')" required />
                            <x-input-error :messages="$errors->get('industry')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="stage" value="Stage" />
                            <x-text-input id="stage" name="stage" type="text" class="block mt-1 w-full" :value="old('stage')" required />
                            <x-input-error :messages="$errors->get('stage')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="min_amount" value="Min Amount" />
                            <x-text-input id="min_amount" name="min_amount" type="number" step="0.01" class="block mt-1 w-full" :value="old('min_amount')" required />
                            <x-input-error :messages="$errors->get('min_amount')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="max_amount" value="Max Amount" />
                            <x-text-input id="max_amount" name="max_amount" type="number" step="0.01" class="block mt-1 w-full" :value="old('max_amount')" required />
                            <x-input-error :messages="$errors->get('max_amount')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="location" value="Location" />
                            <x-text-input id="location" name="location" type="text" class="block mt-1 w-full" :value="old('location')" />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>
                    </div>

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                        <span class="text-sm text-gray-700">Active Listing</span>
                    </label>

                    <x-primary-button>Publish Listing</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
