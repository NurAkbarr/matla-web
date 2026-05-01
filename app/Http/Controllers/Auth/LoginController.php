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
        $input = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $loginField = filter_var($input['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $credentials = [
            $loginField => $input['login'],
            'password' => $input['password']
        ];

        $remember = $request->has('remember');

        $userCheck = \App\Models\User::where($loginField, $input['login'])->first();

        if (!$userCheck) {
            return back()->withErrors([
                'login' => 'Email atau Nomor Telepon belum terdaftar.',
            ])->with('error', 'Email atau Nomor Telepon belum terdaftar.')->onlyInput('login');
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Redirect based on role
            return redirect()->intended($this->getRedirectPath($user->role));
        }

        return back()->withErrors([
            'login' => 'Email/Nomor Telepon atau password salah.',
        ])->with('error', 'Email/Nomor Telepon atau password salah.')->onlyInput('login');
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
