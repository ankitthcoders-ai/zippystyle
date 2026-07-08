<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class RegisterBasic extends Controller
{
    public function index()
    {
        return view('content.authentications.auth-register-basic');
    }
    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();

        // Handle file uploads
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('profile_images', 'public');
        }

        if ($request->hasFile('adharcard')) {
            $validated['adharcard'] = $request->file('adharcard')->store('adhar_cards', 'public');
        }

        if ($request->hasFile('pancard')) {
            $validated['pancard'] = $request->file('pancard')->store('pan_cards', 'public');
        }

        // Geocode address to get latitude and longitude
        // $address = $validated['address'];
        // $city = $validated['city'];
        // $state = $validated['state'] ?? '';
        // $zip = $validated['zip'];
        // $country = $validated['country'];

        // $fullAddress = $address;
        // if ($city) $fullAddress .= ', ' . $city;
        // if ($state) $fullAddress .= ', ' . $state;
        // if ($zip) $fullAddress .= ' ' . $zip;
        // if ($country) $fullAddress .= ', ' . $country;

        // try {
        //     $response = Http::withHeaders([
        //         'User-Agent' => 'YourAppName/1.0 (youremail@example.com)'
        //     ])->get('https://nominatim.openstreetmap.org/search', [
        //         'format' => 'json',
        //         'q' => $fullAddress
        //     ]);

        //     if ($response->successful() && count($response->json()) > 0) {
        //         $validated['latitude'] = $response->json()[0]['lat'];
        //         $validated['longitude'] = $response->json()[0]['lon'];
        //     }
        // } catch (\Exception $e) {
        //     // Log error but continue registration
        //     \Log::error('Geocoding failed: ' . $e->getMessage());
        // }

        // Prepare user data
        $userData = $validated;
        $userData['password'] = Hash::make($request->password);
        $userData['username'] = $request->filled('username') ? $request->username : $validated['email'];
        $userData['role'] = $validated['role'] ?? 'user';
        $userData['status'] = $validated['status'] ?? 'active';
        $userData['latitude'] = $validated['latitude'] ?? "7788879";
        $userData['longitude'] = $validated['longitude'] ?? "9079079798";

        // Create user
        $user = User::create($userData);

        Auth::login($user);

        return redirect()->route('dashboard-analytics')->with('success', 'Registration successful');
    }
}
