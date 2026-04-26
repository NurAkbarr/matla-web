<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PortofolioMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PortofolioController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'judul'    => ['required', 'string', 'max:255'],
            'deskripsi'=> ['nullable', 'string', 'max:1000'],
            'link'     => 'nullable|url',
            'files.*'  => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = Auth::user();

        if (!$request->hasFile('files') && !$request->filled('link')) {
            return back()->with('error', 'Minimal upload file atau isi link portofolio.');
        }

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('portofolio', 'public');
                PortofolioMahasiswa::create([
                    'user_id'   => $user->id,
                    'judul'     => $request->judul,
                    'deskripsi' => $request->deskripsi,
                    'link'      => $request->link,
                    'file'      => $path,
                ]);
            }
        } else {
            PortofolioMahasiswa::create([
                'user_id'   => $user->id,
                'judul'     => $request->judul,
                'deskripsi' => $request->deskripsi,
                'link'      => $request->link,
                'file'      => null,
            ]);
        }

        return back()->with('success', 'Portofolio berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $porto = PortofolioMahasiswa::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($porto->file) {
            Storage::disk('public')->delete($porto->file);
        }

        $porto->delete();

        return back()->with('success', 'Portofolio berhasil dihapus.');
    }
}
