<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\ProfilMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profil = $user->profil ?? new ProfilMahasiswa();
        return view('mahasiswa.ktm.profil', compact('user', 'profil'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'tentang_saya' => 'nullable|string|max:1000',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user  = Auth::user();
        $profil = $user->profil ?? $user->profil()->create(['tentang_saya' => '']);

        if ($request->hasFile('foto')) {
            if ($profil->foto && Storage::disk('public')->exists($profil->foto)) {
                Storage::disk('public')->delete($profil->foto);
            }
            $path = $request->file('foto')->store('profil', 'public');
            $profil->foto = $path;
        }

        $profil->tentang_saya = $request->tentang_saya;
        $profil->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
