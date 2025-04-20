<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // $users = User::with('role')->paginate(15);
        $employeeRoleId = UserRole::where('role', 'Employee')->value('id');

        $users = User::where('role_id', '!=', $employeeRoleId)
                     ->with('role')
                     ->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = UserRole::all();
        return view('admin.users.modals.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'surname' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'other_names' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:user_roles,id',
            'status' => 'required|in:active,suspended,banned',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);
        
        $notification = array(
            'message' => 'User created successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('users.index')
            ->with($notification);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('admin.users.modals.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = UserRole::all();
        return view('admin.users.modals.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'surname' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'other_names' => 'nullable|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role_id' => 'required|exists:user_roles,id',
            'status' => 'required|in:active,suspended,banned',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        $notification = array(
            'message' => 'User updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('users.index')
            ->with($notification);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        $notification = array(
            'message' => 'User deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('users.index')
            ->with($notification);
    }

    /**
     * Show the profile form for the authenticated user.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }

    /**
     * Update the profile of the authenticated user.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'surname' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'other_names' => 'nullable|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'current_password' => 'nullable|required_with:password|password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('profile')
            ->with('success', 'Profile updated successfully.');
    }
}
