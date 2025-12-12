<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UserController extends Controller
{
    public function employee(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data'   => $request->employee()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
        ]);

        $user = $request->user();
        $user->update($request->only('name', 'phone', 'email'));

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated',
            'data' => $user,
        ]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:5120',
        ]);

        $uploadedFile = Cloudinary::upload($request->file('avatar')->getRealPath());
        $url = $uploadedFile->getSecurePath();

        $user = $request->user();
        $user->avatar = $url;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Image uploaded',
            'avatar' => $url,
        ]);
    }
}