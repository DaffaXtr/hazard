<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class WebAuthController extends Controller
{
    /**
     * Show the web login form. Redirects to landing page with login param.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'dosen') {
                return redirect()->intended('/dashboard');
            }
            return redirect('/dashboard.html');
        }
        return redirect('/?login=1');
    }

    /**
     * Handle authentication attempt for both Dosen and Mahasiswa.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $request->session()->regenerate();
            
            // Generate token for client-side API requests
            $token = $user->createToken('auth_token')->plainTextToken;

            // Determine redirect URL based on user role
            $redirectUrl = $user->role === 'dosen' ? '/dashboard' : '/dashboard.html';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil.',
                    'user' => $user,
                    'token' => $token,
                    'redirect' => $redirectUrl
                ]);
            }

            return redirect()->intended($redirectUrl)
                ->with('success', 'Selamat datang kembali, ' . $user->name);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 422);
        }

        throw ValidationException::withMessages([
            'email' => ['Kredensial login yang Anda masukkan salah.'],
        ]);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar sistem.');
    }
}
