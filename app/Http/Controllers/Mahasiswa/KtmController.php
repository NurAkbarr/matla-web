<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KtmController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load(['profil', 'skills', 'portofolio']);
        return view('mahasiswa.ktm.show', compact('user'));
    }
}
