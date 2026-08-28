<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VerifikasiPembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = \App\Models\Pembayaran::with(['user', 'tagihan'])->latest()->get();

        return view('backend.keuangan.verifikasi.index', compact('pembayarans'));
    }

    public function approve($id)
    {
        $pembayaran = \App\Models\Pembayaran::findOrFail($id);
        
        if ($pembayaran->status !== 'verified') {
            // Generate Kwitansi Number (INV/YYYYMM/UserID+Random)
            $kwitansiNo = 'INV/' . date('Ym') . '/' . str_pad($pembayaran->user_id, 3, '0', STR_PAD_LEFT) . strtoupper(Str::random(3));

            $pembayaran->update([
                'status' => 'verified',
                'kwitansi' => $kwitansiNo
            ]);

            // Kurangi sisa tagihan
            $tagihan = $pembayaran->tagihan;
            if ($tagihan) {
                $tagihan->sisa_tagihan -= $pembayaran->nominal;
                if ($tagihan->sisa_tagihan <= 0) {
                    $tagihan->sisa_tagihan = 0;
                    $tagihan->status = 'lunas';
                }
                $tagihan->save();
            }

            // Generate PDF and Upload to Google Drive (Background)
            try {
                $pembayaran->load(['user', 'tagihan']);
                $pdf = Pdf::loadView('mahasiswa.pembayaran.kwitansi', compact('pembayaran'));
                
                $angkatan = $pembayaran->user->angkatan ?? 'Tanpa Angkatan';
                $nama = preg_replace('/[^A-Za-z0-9 ]/', '', $pembayaran->user->name ?? 'Unknown');
                
                // Path di dalam Google Drive: Kwitansi_Pembayaran / 2022 / Mhsasep / Kwitansi-INV-....pdf
                $drivePath = "Kwitansi_Pembayaran/{$angkatan}/{$nama}/Kwitansi_{$kwitansiNo}.pdf";
                
                // Upload ke Google Drive
                Storage::disk('google_keuangan')->put($drivePath, $pdf->output());
            } catch (\Exception $e) {
                // Ignore PDF upload errors so it doesn't break the approval process
                \Illuminate\Support\Facades\Log::error('Gagal upload kwitansi ke Google Drive: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function reject($id)
    {
        $pembayaran = \App\Models\Pembayaran::findOrFail($id);
        
        if ($pembayaran->status !== 'rejected') {
            $pembayaran->update(['status' => 'rejected']);
        }
        
        return back()->with('success', 'Pembayaran telah ditolak.');
    }

    public function kwitansiDrive($id)
    {
        $pembayaran = \App\Models\Pembayaran::with('user')->findOrFail($id);
        
        if (!$pembayaran->kwitansi) {
            return back()->with('error', 'Kwitansi belum terbit.');
        }

        $angkatan = $pembayaran->user->angkatan ?? 'Tanpa Angkatan';
        $nama = preg_replace('/[^A-Za-z0-9 ]/', '', $pembayaran->user->name ?? 'Unknown');
        
        $drivePath = "Kwitansi_Pembayaran/{$angkatan}/{$nama}/Kwitansi_{$pembayaran->kwitansi}.pdf";
        
        try {
            $url = Storage::disk('google_keuangan')->url($drivePath);
            return redirect()->away($url);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mendapatkan tautan dari Google Drive: ' . $e->getMessage());
        }
    }
}
