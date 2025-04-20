<?php

namespace App\Http\Controllers\Platformfeatures;

use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Models\UserPermission;
use App\Models\PlatformFeature;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class PlatformFeatureController extends Controller
{
     /**
     * Display a listing of the features.
     */
    public function index()
    {
        $features = PlatformFeature::paginate(15);
        return view('admin.features.index', compact('features'));
    }

    /**
     * Show the form for creating a new feature.
     */
    public function create()
    {
        return view('admin.features.create');
    }

    /**
     * Store a newly created feature in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'feature' => 'required|string|max:255|unique:platform_features',
            'description' => 'required|string',
        ]);

        PlatformFeature::create($validated);

        return redirect()->route('features.index')
            ->with('success', 'Feature created successfully.');
    }

    /**
     * Display the specified feature.
     */
    public function show(PlatformFeature $feature)
    {
        $feature->load('userPermissions.role');
        return view('admin.features.show', compact('feature'));
    }

    /**
     * Show the form for editing the specified feature.
     */
    public function edit(PlatformFeature $feature)
    {
        return view('admin.features.edit', compact('feature'));
    }

    /**
     * Update the specified feature in storage.
     */
    public function update(Request $request, PlatformFeature $feature)
    {
        $validated = $request->validate([
            'feature' => [
                'required',
                'string',
                'max:255',
                Rule::unique('platform_features')->ignore($feature->id),
            ],
            'description' => 'required|string',
        ]);

        $feature->update($validated);

        return redirect()->route('features.index')
            ->with('success', 'Feature updated successfully.');
    }

    /**
     * Remove the specified feature from storage.
     */
    public function destroy(PlatformFeature $feature)
    {
        // Check if there are permissions with this feature
        if ($feature->userPermissions()->count() > 0) {
            return redirect()->route('features.index')
                ->with('error', 'Cannot delete feature that has associated permissions.');
        }

        $feature->delete();

        return redirect()->route('features.index')
            ->with('success', 'Feature deleted successfully.');
    }
}