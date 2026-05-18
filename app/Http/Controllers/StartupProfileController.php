<?php

namespace App\Http\Controllers;

use App\Models\StartupProfile;
use Illuminate\Http\Request;

class StartupProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('startup-profiles.edit', [
            'profile' => $request->user()->startupProfile,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'startup_name' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:255'],
            'industry' => ['required', 'string', 'max:255'],
            'stage' => ['required', 'string', 'max:255'],
            'funding_requirement' => ['required', 'numeric', 'min:0'],
            'location' => ['required', 'string', 'max:255'],
            'pitch_description' => ['required', 'string'],
            'documents.*' => ['nullable', 'file', 'mimes:pdf,ppt,pptx,doc,docx'],
        ], [
            'documents.*.uploaded' => 'One of the files exceeds the allowed upload limit (usually 2MB) or failed to upload.',
            'documents.*.file' => 'One of the files must be a valid uploaded file.',
            'documents.*.mimes' => 'Each uploaded document must be a PDF, PowerPoint, or Word file.',
        ]);

        $existingPaths = $request->user()->startupProfile?->document_paths ?? [];

        if ($request->hasFile('documents')) {
            $uploaded = [];
            foreach ($request->file('documents') as $file) {
                $uploaded[] = $file->store('startup-documents', 'public');
            }
            $existingPaths = array_values(array_unique(array_merge($existingPaths, $uploaded)));
        }

        $validated['document_paths'] = $existingPaths;

        StartupProfile::updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return redirect()->route('startup.profile.edit')->with('status', 'Startup profile saved.');
    }
}
