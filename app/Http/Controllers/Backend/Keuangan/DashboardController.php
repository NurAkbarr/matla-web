<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Saldo Akun (Total Pembayaran Verified)
        $saldoAkun = \App\Models\Pembayaran::has('tagihan')->where('status', 'verified')->sum('nominal');

        // 2. Total Tagihan (Sisa tagihan belum lunas)
        $tagihanBelumLunas = \App\Models\Tagihan::where('status', '!=', 'lunas');
        $totalTagihan = $tagihanBelumLunas->sum('sisa_tagihan');
        $jumlahTagihan = $tagihanBelumLunas->count();

        // 3. Pembayaran Bulan Ini
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        $pembayaranBulanIni = \App\Models\Pembayaran::has('tagihan')->where('status', 'verified')
            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth]);
            
        $totalPembayaranBulanIni = $pembayaranBulanIni->sum('nominal');
        $pembayaranTerakhir = $pembayaranBulanIni->latest('updated_at')->first();
        $tanggalTerakhir = $pembayaranTerakhir ? $pembayaranTerakhir->updated_at->format('d M Y') : '-';

        // 4. Riwayat Transaksi (Semua tercatat)
        $totalTransaksi = \App\Models\Pembayaran::has('tagihan')->count();

        // 5. Total Pengeluaran (Dummy/0 for now)
        $totalPengeluaran = 0;

        // 6. Riwayat Transaksi Terkini
        $riwayatTerkini = \App\Models\Pembayaran::with(['user', 'tagihan'])
            ->has('tagihan')
            ->where('status', 'verified')
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('backend.keuangan.dashboard', compact(
            'saldoAkun',
            'totalTagihan',
            'jumlahTagihan',
            'totalPembayaranBulanIni',
            'tanggalTerakhir',
            'totalTransaksi',
            'totalPengeluaran',
            'riwayatTerkini'
        ));
    }
}
