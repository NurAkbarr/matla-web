<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use App\Models\Affiliate;
use App\Models\PmbRegistration;
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

    public function leaderboard()
    {
        // Get Top 10 Affiliates based on confirmed registrations (status approved/accepted)
        // or just all registrations for motivation? Let's use count of all registrations for now
        $topDutas = Affiliate::withCount('registrations')
            ->where('is_active', true)
            ->orderBy('registrations_count', 'desc')
            ->limit(10)
            ->get();

        // Get Recent Activities (last 15)
        $recentActivities = PmbRegistration::with('affiliate')
            ->whereNotNull('affiliate_id')
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        return view('informasi.leaderboard', compact('topDutas', 'recentActivities'));
    }
}
