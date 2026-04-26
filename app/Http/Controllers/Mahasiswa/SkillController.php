<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\SkillMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_skill' => 'required|string|max:100',
            'level'      => 'required|in:dasar,menengah,mahir',
        ]);

        $user = Auth::user();

        if ($user->skills()->count() >= 10) {
            return back()->with('error', 'Maksimal 10 skill diperbolehkan.');
        }

        $exists = SkillMahasiswa::where('user_id', $user->id)
            ->where('nama_skill', $request->nama_skill)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Skill sudah pernah ditambahkan.');
        }

        SkillMahasiswa::create([
            'user_id'    => $user->id,
            'nama_skill' => $request->nama_skill,
            'level'      => $request->level,
        ]);

        return back()->with('success', 'Skill berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $skill = SkillMahasiswa::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $skill->delete();

        return back()->with('success', 'Skill berhasil dihapus.');
    }
}
