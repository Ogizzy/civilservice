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

class UserPermissionController extends Controller
{
    /**
     * Display a listing of the permissions.
     */
    public function index()
    {
        $permissions = UserPermission::with(['role', 'feature'])->paginate(15);
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create()
    {
        $roles = UserRole::all();
        $features = PlatformFeature::all();
        return view('admin.permissions.create', compact('roles', 'features'));
    }

    /**
     * Store a newly created permission in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:user_roles,id',
            'feature_id' => 'required|exists:platform_features,id',
            'can_create' => 'required|boolean',
            'can_edit' => 'required|boolean',
            'can_delete' => 'required|boolean',
        ]);

        // Check if permission already exists for this role and feature
        $exists = UserPermission::where('role_id', $validated['role_id'])
            ->where('feature_id', $validated['feature_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Permission for this role and feature already exists.')
                ->withInput();
        }

        UserPermission::create($validated);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified permission.
     */
    public function show(UserPermission $permission)
    {
        $permission->load(['role', 'feature']);
        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(UserPermission $permission)
    {
        $roles = UserRole::all();
        $features = PlatformFeature::all();
        return view('admin.permissions.edit', compact('permission', 'roles', 'features'));
    }

    /**
     * Update the specified permission in storage.
     */
    public function update(Request $request, UserPermission $permission)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:user_roles,id',
            'feature_id' => 'required|exists:platform_features,id',
            'can_create' => 'required|boolean',
            'can_edit' => 'required|boolean',
            'can_delete' => 'required|boolean',
        ]);

        // Check if this would create a duplicate
        $exists = UserPermission::where('role_id', $validated['role_id'])
            ->where('feature_id', $validated['feature_id'])
            ->where('id', '!=', $permission->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Permission for this role and feature already exists.')
                ->withInput();
        }

        $permission->update($validated);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(UserPermission $permission)
    {
        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
