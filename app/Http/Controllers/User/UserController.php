<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
  public function index(Request $request)
{
    $employeeRoleId = UserRole::where('role', 'Employee')->value('id');

    $query = User::where('role_id', '!=', $employeeRoleId)->with('role');

    // 🔍 Add search
    if ($request->has('search') && $request->search !== '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('surname', 'LIKE', "%{$search}%")
              ->orWhere('first_name', 'LIKE', "%{$search}%")
              ->orWhere('other_names', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%");
        });
    }

    $users = $query->paginate(5)->appends($request->only('search'));

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

        $user = User::create($validated);

        // Log user creation
        Log::info('User created', [
            'created_user_id' => $user->id,
            'created_user_email' => $user->email,
            'created_by' => Auth::id(),
            'status' => $user->status
        ]);
        
        $notification = array(
            'message' => 'User created successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('users.index')->with($notification);
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
        // Prevent users from changing their own status to avoid lockout
        if ($user->id === Auth::id() && $request->has('status') && $request->status !== $user->status) {
            $notification = array(
                'message' => 'You cannot change your own status.',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

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

        $oldStatus = $user->status;
        $user->update($validated);

        // Log status change if status was modified
        if ($oldStatus !== $validated['status']) {
            Log::info('User status changed', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
                'changed_by' => Auth::id()
            ]);
        }

        $notification = array(
            'message' => 'User updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('users.index')->with($notification);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent users from deleting themselves
        if ($user->id === Auth::id()) {
            $notification = array(
                'message' => 'You cannot delete your own account.',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        Log::info('User deleted', [
            'deleted_user_id' => $user->id,
            'deleted_user_email' => $user->email,
            'deleted_by' => Auth::id()
        ]);

        $user->delete();

        $notification = array(
            'message' => 'User deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('users.index')->with($notification);
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
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        $notification = array(
            'message' => 'Profile updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('profile')->with($notification);
    }

    /**
     * Quick status change method
     */
    public function changeStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,suspended,banned'
        ]);

        // Prevent users from changing their own status
        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'You cannot change your own status.'], 403);
        }

        $oldStatus = $user->status;
        $user->update(['status' => $request->status]);

        Log::info('User status changed via quick action', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'changed_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => "User status changed to {$request->status}",
            'new_status' => $request->status
        ]);
    }
}