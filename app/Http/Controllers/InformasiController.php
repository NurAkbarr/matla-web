<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class InformasiController extends Controller
{
    public function programStudi()
    {
        $programStudis = ProgramStudi::active()->get();
        return view('informasi.program-studi', compact('programStudis'));
    }

    public function karyaMahasiswa()
    {
        return view('informasi.karya-mahasiswa');
    }

    public function karyaDosen()
    {
        return view('informasi.karya-dosen');
    }

    public function stafPengajar()
    {
        return view('informasi.staf-pengajar');
    }

    public function galeri()
    {
        return view('informasi.galeri');
    }
}
