<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        $userCheck = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$userCheck) {
            return back()->withErrors([
                'email' => 'Email belum terdaftar.',
            ])->with('error', 'Email belum terdaftar.')->onlyInput('email');
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Redirect based on role
            return redirect()->intended($this->getRedirectPath($user->role));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->with('error', 'Email atau password salah.')->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function getRedirectPath($role)
    {
        return match ($role) {
            'super_admin', 'admin' => '/backend/admin/dashboard',
            'dosen' => '/backend/dosen/dashboard',
            'mahasiswa' => '/backend/mahasiswa/dashboard',
            default => '/',
        };
    }
}
