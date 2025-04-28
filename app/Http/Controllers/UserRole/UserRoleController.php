<?php

namespace App\Http\Controllers\UserRole;

use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Models\UserPermission;
use App\Models\PlatformFeature;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UserRoleController extends Controller
{
   /**
     * Display a listing of the roles.
     */
    public function index()
    {
        $roles = UserRole::withCount('users')->paginate(15);
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $features = PlatformFeature::all();
        return view('admin.roles.create', compact('features'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|string|max:255|unique:user_roles',
            'permissions' => 'required|array',
            'permissions.*.feature_id' => 'required|exists:platform_features,id',
            'permissions.*.can_create' => 'boolean',
            'permissions.*.can_edit' => 'boolean',
            'permissions.*.can_delete' => 'boolean',
        ]);

        $role = UserRole::create([
            'role' => $validated['role']
        ]);

        foreach ($validated['permissions'] as $permission) {
            UserPermission::create([
                'role_id' => $role->id,
                'feature_id' => $permission['feature_id'],
                'can_create' => $permission['can_create'] ?? false,
                'can_edit' => $permission['can_edit'] ?? false,
                'can_delete' => $permission['can_delete'] ?? false,
            ]);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role.
     */
    public function show(UserRole $role)
    {
        $role->load('userPermissions.feature');
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(UserRole $role)
    {
        $features = PlatformFeature::all();
        $role->load('userPermissions');
        return view('admin.roles.edit', compact('role', 'features'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, UserRole $role)
    {
        $validated = $request->validate([
            'role' => ['required','string','max:255',
                Rule::unique('user_roles')->ignore($role->id),
            ],
            'permissions' => 'required|array',
            'permissions.*.feature_id' => 'required|exists:platform_features,id',
            'permissions.*.can_create' => 'boolean',
            'permissions.*.can_edit' => 'boolean',
            'permissions.*.can_delete' => 'boolean',
        ]);

        $role->update([
            'role' => $validated['role']
        ]);

        // Delete existing permissions
        UserPermission::where('role_id', $role->id)->delete();

        // Create new permissions
        foreach ($validated['permissions'] as $permission) {
            UserPermission::create([
                'role_id' => $role->id,
                'feature_id' => $permission['feature_id'],
                'can_create' => $permission['can_create'] ?? false,
                'can_edit' => $permission['can_edit'] ?? false,
                'can_delete' => $permission['can_delete'] ?? false,
            ]);
        }

        $notification = array(
            'message' => 'Role updated successfully',
            'alert-type' => 'success'
        );


        return redirect()->route('roles.index')
            ->with($notification);
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(UserRole $role)
    {
        // Check if role has users
        if ($role->users()->count() > 0) {

            $notification = array(
                'message' => 'Cannot delete role with assigned users',
                'alert-type' => 'error'
            );
            
            return redirect()->route('roles.index')
                ->with($notification);
        }

        // Delete permissions
        UserPermission::where('role_id', $role->id)->delete();
        
        // Delete role
        $role->delete();

        $notification = array(
            'message' => 'Role deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('roles.index')
            ->with($notification);
    }
}
