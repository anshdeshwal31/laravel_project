<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="section-label mb-3">Profile</p>
            <h2 class="text-3xl font-bold tracking-tight text-white">Startup Profile</h2>
            <p class="mt-2 text-sm text-slate-400">Show investors exactly what you are building and how much capital you need.</p>
        </div>
    </x-slot>

    <div class="px-4 py-10 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">
            @if (session('status'))
                <div class="mb-4 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-emerald-200">{{ session('status') }}</div>
            @endif

            <div class="saas-card">
                <form method="POST" action="{{ route('startup.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="startup_name" value="Startup Name" class="saas-label" />
                        <x-text-input id="startup_name" name="startup_name" type="text" class="saas-input" :value="old('startup_name', $profile?->startup_name)" required />
                        <x-input-error :messages="$errors->get('startup_name')" class="saas-error" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="domain" value="Domain" class="saas-label" />
                            <x-text-input id="domain" name="domain" type="text" class="saas-input" :value="old('domain', $profile?->domain)" required />
                            <x-input-error :messages="$errors->get('domain')" class="saas-error" />
                        </div>
                        <div>
                            <x-input-label for="industry" value="Industry" class="saas-label" />
                            <x-text-input id="industry" name="industry" type="text" class="saas-input" :value="old('industry', $profile?->industry)" required />
                            <x-input-error :messages="$errors->get('industry')" class="saas-error" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="stage" value="Stage" class="saas-label" />
                            <select id="stage" name="stage" class="saas-input" required>
                                @php($selectedStage = old('stage', $profile?->stage))
                                @foreach (['idea', 'pre-seed', 'seed', 'series-a', 'growth'] as $stage)
                                    <option value="{{ $stage }}" @selected($selectedStage === $stage)>{{ ucfirst($stage) }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('stage')" class="saas-error" />
                        </div>
                        <div>
                            <x-input-label for="funding_requirement" value="Funding Requirement" class="saas-label" />
                            <x-text-input id="funding_requirement" name="funding_requirement" type="number" step="0.01" class="saas-input" :value="old('funding_requirement', $profile?->funding_requirement)" required />
                            <x-input-error :messages="$errors->get('funding_requirement')" class="saas-error" />
                        </div>
                        <div>
                            <x-input-label for="location" value="Location" class="saas-label" />
                            <x-text-input id="location" name="location" type="text" class="saas-input" :value="old('location', $profile?->location)" required />
                            <x-input-error :messages="$errors->get('location')" class="saas-error" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="pitch_description" value="Pitch Description" class="saas-label" />
                        <textarea id="pitch_description" name="pitch_description" rows="5" class="saas-input" required>{{ old('pitch_description', $profile?->pitch_description) }}</textarea>
                        <x-input-error :messages="$errors->get('pitch_description')" class="saas-error" />
                    </div>

                    <div>
                        <x-input-label for="documents" value="Upload Documents (PDF, PPT, DOC)" class="saas-label" />
                        <input id="documents" name="documents[]" type="file" multiple class="saas-input file:mr-4 file:rounded-full file:border-0 file:bg-white/10 file:px-4 file:py-2 file:text-white hover:file:bg-white/15" />
                        <x-input-error :messages="$errors->get('documents.*')" class="saas-error" />
                    </div>

                    @if (!empty($profile?->document_paths))
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
                            <p class="mb-3 font-medium text-white">Uploaded Documents</p>
                            <ul class="space-y-2">
                                @foreach ($profile->document_paths as $path)
                                    <li><a href="{{ asset('storage/'.$path) }}" target="_blank" class="text-cyan-300 transition hover:text-cyan-200">{{ basename($path) }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <x-primary-button>Save Startup Profile</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
