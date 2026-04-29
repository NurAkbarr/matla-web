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
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:1000',
            'tanggal_lahir'=> 'nullable|date',
            'jenis_kelamin'=> 'nullable|in:Laki-laki,Perempuan',
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
            $user->avatar = $path;
        }

        $profil->tentang_saya = $request->tentang_saya;
        $profil->save();

        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->bio = $request->tentang_saya;
        $user->tanggal_lahir = $request->tanggal_lahir;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
