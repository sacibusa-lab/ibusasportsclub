<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'device_token' => 'required|string',
        ]);

        $ipAddress = $request->ip();
        $deviceToken = $request->device_token;

        // Check for existing device token or IP
        $existingDevice = User::where('device_token', $deviceToken)->first();
        if ($existingDevice) {
            throw ValidationException::withMessages([
                'device_token' => 'This device is already associated with an account.',
            ]);
        }

        // Optional: Check IP (might be too strict if on shared wifi, but user requested device tying)
        // $existingIp = User::where('registration_ip', $ipAddress)->where('created_at', '>', now()->subDay())->first();
        // if ($existingIp) { ... }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'registration_ip' => $ipAddress,
            'device_token' => $deviceToken,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Welcome to the Predictor League!');
    }
}
