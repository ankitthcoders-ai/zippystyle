<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiImageUploader;
use App\Support\RoleAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function get(Request $request)
    {
        $user = User::find($request->user()->id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'data' => null,
            ], 404);
        }
        return response()->json([
            'message' => 'User fetched successfully',
            'data' => $request->user(),
        ]);
    }

    public function createUser(Request $request)
    {
        if (!RoleAccess::isAdmin()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'username' => 'nullable|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:255|unique:users,phone',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $validated['username'] = $validated['username'] ?? $validated['email'];
        $validated['role'] = $validated['role'] ?? 'user';

        if ($imagePath = ApiImageUploader::store($request, 'image', 'profile_images')) {
            $validated['image'] = $imagePath;
        } else {
            unset($validated['image']);
        }

        $user = User::create($validated);
        $user->setAttribute('image_url', ApiImageUploader::url($user->image));

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!RoleAccess::isAdmin() && (int) Auth::id() !== (int) $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'username' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('users', 'phone')->ignore($user->id)],
            'gender' => 'sometimes|required|in:male,female',
            'address' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|nullable|string|max:255',
            'state' => 'sometimes|nullable|string|max:255',
            'zip' => 'sometimes|nullable|string|max:255',
            'country' => 'sometimes|nullable|string|max:255',
            'status' => 'sometimes|nullable|string|max:255',
            'password' => 'sometimes|required|string|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        if (RoleAccess::isAdmin() && $request->filled('role')) {
            $validated['role'] = $request->input('role');
        }

        if ($imagePath = ApiImageUploader::store($request, 'image', 'profile_images')) {
            $validated['image'] = $imagePath;
        } else {
            unset($validated['image']);
        }

        $user->update($validated);
        $fresh = $user->fresh();
        $fresh->setAttribute('image_url', ApiImageUploader::url($fresh->image));

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $fresh,
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!RoleAccess::isAdmin() && (int) Auth::id() !== (int) $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }
}
