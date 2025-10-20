<?php

namespace App\Http\Controllers\UserPermission;

use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Models\UserPermission;
use App\Models\PlatformFeature;
use App\Http\Controllers\Controller;

class UserPermissionController extends Controller
{
    /**
     * Display a listing of the permissions.
     */
    public function index(Request $request)
    {
        $query = UserPermission::with(['role', 'feature']);

        // Apply search filter
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('role', function ($q) use ($search) {
                $q->where('role', 'LIKE', "%{$search}%");
            });
        }
        $permissions = $query->paginate(10)->appends($request->only('search'));

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

            $notification = array(
                'message' => 'Permission for this role and feature already exists.',
                'alert-type' => 'error'
            );
            return redirect()->back()
                ->with($notification)
                ->withInput();
        }

        UserPermission::create($validated);

        $notification = array(
            'message' => 'Permission created successfully.',
            'alert-type' => 'success'
        );

        return redirect()->route('permissions.index')
            ->with($notification);
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

            $notification = array(
                'message' => 'Permission for this role and feature already exists',
                'alert-type' => 'error'
            );
            return redirect()->back()
                ->with($notification)
                ->withInput();
        }

        $permission->update($validated);

        $notification = array(
            'message' => 'Permission updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('permissions.index')
            ->with($notification);
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(UserPermission $permission)
    {
        $permission->delete();

        $notification = array(
            'message' => 'Permission deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('permissions.index')
            ->with($notification);
    }
}
