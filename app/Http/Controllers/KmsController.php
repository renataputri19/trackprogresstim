<?php

// app/Http/Controllers/KmsController.php
namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Activity;
use App\Models\ActivityDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KmsController extends Controller
{
    public function index()
    {
        $divisions = Division::with('activities')->get();
        return view('kms.index', compact('divisions'));
    }

    public function division($slug)
    {
        $division = Division::where('slug', $slug)
            ->with(['activities' => function ($query) {
                $query->orderBy('name');
            }])
            ->firstOrFail();
        return view('kms.division', compact('division'));
    }

    public function activity($divisionSlug, $activitySlug)
    {
        $activity = Activity::where('slug', $activitySlug)
            ->whereHas('division', function ($query) use ($divisionSlug) {
                $query->where('slug', $divisionSlug);
            })
            ->with(['documents' => function ($query) {
                $query->orderBy('document_date', 'desc');
            }])
            ->firstOrFail();

        $documents = $activity->documents->groupBy(function ($document) {
            return $document->document_date->format('F Y');
        });

        return view('kms.activity', compact('activity', 'documents'));
    }

    // Division methods
    public function createDivision()
    {
        return view('kms.divisions.create');
    }

    public function storeDivision(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:divisions,name',
            'description' => 'nullable'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Division::create($validated);

        return redirect()->route('kms.index')
            ->with('success', 'Division created successfully.');
    }

    // Activity methods
    public function createActivity(Division $division)
    {
        return view('kms.activities.create', compact('division'));
    }

    public function storeActivity(Request $request, Division $division)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['division_id'] = $division->id;

        Activity::create($validated);

        return redirect()->route('kms.division', $division->slug)
            ->with('success', 'Activity created successfully.');
    }

    // Document methods
    public function createDocument(Activity $activity)
    {
        return view('kms.documents.create', compact('activity'));
    }

    public function storeDocument(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'document_date' => 'required|date',
            'onedrive_link' => 'required|url',
            'description' => 'nullable'
        ]);

        $validated['activity_id'] = $activity->id;

        ActivityDocument::create($validated);

        return redirect()->route('kms.activity', [$activity->division->slug, $activity->slug])
            ->with('success', 'Document added successfully.');
    }


















    // In KmsController.php

    // Division methods
    public function editDivision(Division $division)
    {
        return view('kms.divisions.edit', compact('division'));
    }

    public function updateDivision(Request $request, Division $division)
    {
        $validated = $request->validate([
            'name' => 'required|max:255|unique:divisions,name,' . $division->id,
            'description' => 'nullable'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $division->update($validated);

        // Change this return statement to redirect to index instead of division view
        return redirect()->route('kms.index')
            ->with('success', 'Division updated successfully.');
    }

    public function destroyDivision(Division $division)
    {
        $division->delete();
        return redirect()->route('kms.index')
            ->with('success', 'Division deleted successfully.');
    }

    // Activity methods
    public function editActivity(Activity $activity)
    {
        return view('kms.activities.edit', compact('activity'));
    }

    public function updateActivity(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $activity->update($validated);

        return redirect()->route('kms.division', $activity->division->slug)
            ->with('success', 'Activity updated successfully.');
    }

    public function destroyActivity(Activity $activity)
    {
        $divisionSlug = $activity->division->slug;
        $activity->delete();
        return redirect()->route('kms.division', $divisionSlug)
            ->with('success', 'Activity deleted successfully.');
    }

    // Document methods
    public function editDocument(ActivityDocument $document)
    {
        return view('kms.documents.edit', compact('document'));
    }

    public function updateDocument(Request $request, ActivityDocument $document)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'document_date' => 'required|date',
            'onedrive_link' => 'required|url',
            'description' => 'nullable'
        ]);

        $document->update($validated);

        return redirect()->route('kms.activity', [
            'division' => $document->activity->division->slug,
            'activity' => $document->activity->slug
        ])->with('success', 'Document updated successfully.');
    }

    public function destroyDocument(ActivityDocument $document)
    {
        $activity = $document->activity;
        $document->delete();
        return redirect()->route('kms.activity', [
            'division' => $activity->division->slug,
            'activity' => $activity->slug
        ])->with('success', 'Document deleted successfully.');
    }
}
