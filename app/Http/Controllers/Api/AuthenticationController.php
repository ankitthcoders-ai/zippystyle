<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiImageUploader;
use App\Support\RoleAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthenticationController extends Controller
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
    public function store(Request $request)
    {

        $request->validate([
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'phone' => 'required|string|unique:users,phone',
            'gender' => 'required|in:male,female',
            'password' => 'required|string',
            'image' => 'nullable',
            'adharcard' => ['nullable', Rule::when($request->hasFile('adharcard'), ['file', 'mimes:jpeg,png,jpg,pdf', 'max:10240'])],
            'pancard' => ['nullable', Rule::when($request->hasFile('pancard'), ['file', 'mimes:jpeg,png,jpg,pdf', 'max:10240'])],
        ]);

        $data = $request->except(['image', 'adharcard', 'pancard']);
        $data['username'] = $data['email'] ?? $data['username'] ?? null;
        $data['role'] = 'user';

        if ($imagePath = ApiImageUploader::store($request, 'image', 'profile_images')) {
            $data['image'] = $imagePath;
        }

        if ($request->hasFile('adharcard')) {
            $data['adharcard'] = $request->file('adharcard')->store('adhar_cards', 'public');
        }

        if ($request->hasFile('pancard')) {
            $data['pancard'] = $request->file('pancard')->store('pan_cards', 'public');
        }

        $user = User::create($data);

        $token = $user->createToken('API Token')->accessToken;
        $user->setAttribute('image_url', ApiImageUploader::url($user->image));

        return response()->json([
            'message' => 'Registration successful',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'data' => null,
            ], 404);
        }

        if (!RoleAccess::isAdmin() && (int) Auth::id() !== (int) $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'gender' => 'sometimes|required|in:male,female',
            'username' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('users', 'phone')->ignore($user->id)],
            'address' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|nullable|string|max:255',
            'state' => 'sometimes|nullable|string|max:255',
            'zip' => 'sometimes|nullable|string|max:255',
            'country' => 'sometimes|nullable|string|max:255',
            'status' => 'sometimes|nullable|string|max:255',
            'latitude' => 'sometimes|required|string',
            'longitude' => 'sometimes|required|string',
            'password' => 'sometimes|required|string|min:8',
            'image' => 'nullable',
            'adharcard' => ['nullable', Rule::when($request->hasFile('adharcard'), ['file', 'mimes:jpeg,png,jpg,pdf', 'max:10240'])],
            'pancard' => ['nullable', Rule::when($request->hasFile('pancard'), ['file', 'mimes:jpeg,png,jpg,pdf', 'max:10240'])],
        ]);

        if (RoleAccess::isAdmin() && $request->filled('role')) {
            $validated['role'] = $request->input('role');
        }

        if ($imagePath = ApiImageUploader::store($request, 'image', 'profile_images')) {
            $validated['image'] = $imagePath;
        } else {
            unset($validated['image']);
        }

        if ($request->hasFile('adharcard')) {
            $validated['adharcard'] = $request->file('adharcard')->store('adhar_cards', 'public');
        }

        if ($request->hasFile('pancard')) {
            $validated['pancard'] = $request->file('pancard')->store('pan_cards', 'public');
        }

        $user->update($validated);
        $fresh = $user->fresh();
        $fresh->setAttribute('image_url', ApiImageUploader::url($fresh->image));

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $fresh,
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'data' => null,
            ], 404);
        }

        if (!RoleAccess::isAdmin() && (int) Auth::id() !== (int) $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'data' => null,
            ], 404);
        }
        if (!password_verify($request->password, $user->password)) {
            return response()->json([
                'message' => 'Password is incorrect',
                'data' => null,
            ], 401);
        }
        $token = $user->createToken('API Token')->accessToken;
        return response()->json([
            'message' => 'Login successful',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
