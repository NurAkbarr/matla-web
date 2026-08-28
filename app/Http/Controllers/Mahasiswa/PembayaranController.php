<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranController extends Controller
{
    public function index()
    {
        return view('mahasiswa.pembayaran.index');
    }

    public function create()
    {
        return view('mahasiswa.pembayaran.create');
    }

    public function tagihan()
    {
        $tagihans = \App\Models\Tagihan::with(['pembayarans' => function($q) {
            $q->where('status', '!=', 'rejected')->orderBy('created_at', 'asc');
        }])->where('user_id', auth()->id())->latest()->get();
        return view('mahasiswa.pembayaran.tagihan', compact('tagihans'));
    }

    public function konfirmasiForm($id)
    {
        $tagihan = \App\Models\Tagihan::where('user_id', auth()->id())->findOrFail($id);
        return view('mahasiswa.pembayaran.konfirmasi', compact('tagihan'));
    }

    public function submitKonfirmasi(Request $request, $id)
    {
        $tagihan = \App\Models\Tagihan::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'jenis_pembayaran' => 'required|in:lunas,cicilan',
            'nominal' => 'required|numeric|min:1',
            'bukti_tf' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'berkas_pendukung' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'catatan' => 'nullable|string'
        ]);

        if ($tagihan->jenis_tagihan === 'lunas' && $request->jenis_pembayaran === 'cicilan') {
            return back()->withErrors(['jenis_pembayaran' => 'Tagihan ini wajib lunas (sekali bayar) dan tidak dapat dicicil.'])->withInput();
        }

        $nominal = $request->nominal;
        // Strict validation: if Lunas, force the nominal to match remaining bill exactly
        if ($request->jenis_pembayaran === 'lunas') {
            $nominal = $tagihan->sisa_tagihan;
        }

        $user = auth()->user();
        $angkatan = $user->angkatan ?? 'Tanpa Angkatan';
        $nama = preg_replace('/[^A-Za-z0-9 ]/', '', $user->name ?? 'Unknown');
        
        // Membentuk struktur folder bersarang (Angkatan / Nama Mahasiswa)
        $folderName = "{$angkatan}/{$nama}";

        try {
            $buktiPath = $request->file('bukti_tf')->store($folderName, 'google_keuangan');
            $buktiTfUrl = \Illuminate\Support\Facades\Storage::disk('google_keuangan')->url($buktiPath);
        } catch (\Exception $e) {
            // Fallback to local public disk if Google Drive Keuangan is not yet configured
            $buktiPath = $request->file('bukti_tf')->store('pembayaran', 'public');
            $buktiTfUrl = $buktiPath;
        }
        
        $berkasPendukungUrl = null;
        if ($request->hasFile('berkas_pendukung')) {
            try {
                $berkasPath = $request->file('berkas_pendukung')->store($folderName, 'google_keuangan');
                $berkasPendukungUrl = \Illuminate\Support\Facades\Storage::disk('google_keuangan')->url($berkasPath);
            } catch (\Exception $e) {
                $berkasPath = $request->file('berkas_pendukung')->store('pembayaran/pendukung', 'public');
                $berkasPendukungUrl = $berkasPath;
            }
        }

        $pembayaran = \App\Models\Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'user_id' => auth()->id(),
            'jenis_pembayaran' => $request->jenis_pembayaran,
            'nominal' => $nominal,
            'bukti_tf' => $buktiTfUrl,
            'berkas_pendukung' => $berkasPendukungUrl,
            'catatan' => $request->catatan,
            'status' => 'pending'
        ]);

        try {
            // Get first finance admin email, fallback to a default email if none exists
            $adminKeuangan = \App\Models\User::where('role', 'keuangan')->first();
            $adminEmail = $adminKeuangan ? $adminKeuangan->email : 'finance@matla.id';
            
            \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\PaymentSubmittedMail($pembayaran, auth()->user()));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send payment submitted email: ' . $e->getMessage());
        }

        return redirect()->route('backend.mahasiswa.pembayaran.tagihan')->with('success', 'Konfirmasi pembayaran berhasil dikirim dan menunggu verifikasi Admin.');
    }

    public function riwayat()
    {
        // Get tagihans that have payments, ordered by newest payments
        $tagihans = \App\Models\Tagihan::where('user_id', auth()->id())
            ->whereHas('pembayarans')
            ->with(['pembayarans' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.pembayaran.riwayat', compact('tagihans'));
    }

    public function kwitansi($id)
    {
        $pembayaran = \App\Models\Pembayaran::where('user_id', auth()->id())
            ->where('status', 'verified')
            ->whereNotNull('kwitansi')
            ->with('tagihan')
            ->findOrFail($id);

        $pdf = Pdf::loadView('mahasiswa.pembayaran.kwitansi', compact('pembayaran'));
        $safeKwitansiNo = str_replace('/', '-', $pembayaran->kwitansi);
        return $pdf->download('Kwitansi_Pembayaran_' . $safeKwitansiNo . '.pdf');
    }
}
