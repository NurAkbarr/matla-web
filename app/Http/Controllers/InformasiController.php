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
        try {
            // Get Top 10 Affiliates
            $topDutas = Affiliate::withCount('registrations')
                ->where('is_active', true)
                ->having('registrations_count', '>', 0)
                ->orderBy('registrations_count', 'desc')
                ->limit(10)
                ->get();

            // Get Recent Activities
            $recentActivities = PmbRegistration::with('affiliate')
                ->whereNotNull('affiliate_id')
                ->orderBy('created_at', 'desc')
                ->limit(15)
                ->get();
        } catch (\Exception $e) {
            $topDutas = collect();
            $recentActivities = collect();
        }

        return view('informasi.leaderboard', compact('topDutas', 'recentActivities'));
    }

    public function strukturOrganisasi()
    {
        return view('informasi.struktur-organisasi');
    }
}
