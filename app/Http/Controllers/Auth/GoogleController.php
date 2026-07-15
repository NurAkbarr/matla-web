<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if a user with this email exists in our database
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Check if user is active
                if ($user->status !== 'AKTIF') {
                    return redirect()->route('login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi admin.');
                }

                // Update google_id if it's empty
                if (empty($user->google_id)) {
                    $user->update([
                        'google_id' => $googleUser->id,
                    ]);
                }

                // Log the user in
                Auth::login($user);

                // Redirect based on role
                if ($user->role == 'super_admin' || $user->role == 'admin') {
                    return redirect()->route('backend.dashboard');
                } else if ($user->role == 'mahasiswa') {
                    return redirect()->route('mahasiswa.dashboard');
                } else {
                    return redirect('/');
                }

            } else {
                // User not found in database, reject access
                return redirect()->route('login')->with('error', 'Akses Ditolak! Email Anda tidak terdaftar sebagai Mahasiswa/Staf di Matla University.');
            }

        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }
    }
}
