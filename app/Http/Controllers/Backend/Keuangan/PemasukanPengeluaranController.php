<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\MasterKategoriTransaksi;
use App\Models\TransaksiKeuangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PemasukanPengeluaranController extends Controller
{
    /**
     * Tampilkan formulir pencatatan kas baru (Uang Masuk / Uang Keluar)
     * dan ringkasan transaksi kas yang baru diinput.
     */
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        // Ringkasan Arus Kas Hari Ini
        $today = Carbon::today();
        $todayMasuk = (float) TransaksiKeuangan::where('status', 'verified')
            ->where('jenis_transaksi', 'masuk')
            ->whereDate('tanggal_transaksi', $today)
            ->sum('nominal');

        $todayKeluar = (float) TransaksiKeuangan::where('status', 'verified')
            ->where('jenis_transaksi', 'keluar')
            ->whereDate('tanggal_transaksi', $today)
            ->sum('nominal');

        $todayNet = $todayMasuk - $todayKeluar;
        $countToday = TransaksiKeuangan::where('status', 'verified')
            ->whereDate('tanggal_transaksi', $today)
            ->count();

        // Master Kategori Transaksi
        $masterKategoris = MasterKategoriTransaksi::orderByRaw("CASE WHEN tipe = 'masuk' THEN 1 WHEN tipe = 'keluar' THEN 2 ELSE 3 END")
            ->orderBy('nama_kategori', 'asc')
            ->get();

        // Query riwayat transaksi singkat (dapat difilter pencarian dan jenis)
        $query = TransaksiKeuangan::with(['user'])->where('status', 'verified');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%{$search}%")
                  ->orWhere('subjek', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('no_referensi', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('admin_nama', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis') && in_array(strtolower($request->input('jenis')), ['masuk', 'keluar'])) {
            $query->where('jenis_transaksi', strtolower($request->input('jenis')));
        }

        // Tampilkan 15 transaksi kas terbaru
        $recentTransaksis = $query->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('backend.keuangan.pemasukan-pengeluaran.index', compact(
            'masterKategoris',
            'recentTransaksis',
            'todayMasuk',
            'todayKeluar',
            'todayNet',
            'countToday'
        ));
    }

    /**
     * Simpan pencatatan transaksi kas baru (Uang Masuk / Uang Keluar).
     */
    public function store(Request $request)
    {
        // 1. Normalisasi nama field input form (alias support)
        if (!$request->filled('jenis_transaksi') && $request->filled('jenis_arus_kas')) {
            $request->merge(['jenis_transaksi' => strtolower($request->input('jenis_arus_kas'))]);
        }
        if (!$request->filled('subjek') && $request->filled('pemberi_dana')) {
            $request->merge(['subjek' => $request->input('pemberi_dana')]);
        }
        if (!$request->filled('deskripsi') && $request->filled('keterangan')) {
            $request->merge(['deskripsi' => $request->input('keterangan')]);
        }
        if (!$request->filled('tanggal') && $request->filled('tanggal_transaksi')) {
            $val = $request->input('tanggal_transaksi');
            $request->merge(['tanggal' => is_string($val) && strlen($val) > 10 ? substr($val, 0, 10) : $val]);
        }
        if (!$request->filled('jam') && $request->filled('waktu_presisi')) {
            $request->merge(['jam' => $request->input('waktu_presisi')]);
        }
        if (!$request->filled('kategori') && $request->filled('kategori_id')) {
            $kat = MasterKategoriTransaksi::find($request->input('kategori_id'));
            if ($kat) {
                $request->merge(['kategori' => $kat->nama_kategori]);
            }
        }

        // Pembersihan format nominal jika mengandung "Rp", titik, atau spasi
        if ($request->filled('nominal') && is_string($request->input('nominal'))) {
            $cleanedNominal = str_replace(['Rp', 'rp', '.', ' '], '', $request->input('nominal'));
            $cleanedNominal = str_replace(',', '.', $cleanedNominal);
            if (is_numeric($cleanedNominal)) {
                $request->merge(['nominal' => $cleanedNominal]);
            }
        }

        // 2. Validasi Input Form
        $request->validate([
            'jenis_transaksi' => 'required|in:masuk,keluar',
            'kategori' => 'required|string|max:100',
            'nominal' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'jam' => 'nullable|string',
            'metode_pembayaran' => 'required|string|max:100',
            'subjek' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'no_referensi' => 'nullable|string|max:100',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'jenis_transaksi.required' => 'Pilihan jenis arus kas (Uang Masuk / Keluar) wajib ditentukan.',
            'jenis_transaksi.in' => 'Pilihan jenis arus kas harus berupa masuk atau keluar.',
            'kategori.required' => 'Kategori transaksi wajib dipilih.',
            'nominal.required' => 'Nominal transaksi wajib diisi.',
            'nominal.numeric' => 'Nominal harus berupa angka yang valid.',
            'nominal.min' => 'Nominal transaksi minimal Rp 1.',
            'tanggal.required' => 'Tanggal transaksi wajib diisi.',
            'tanggal.date' => 'Format tanggal transaksi tidak valid.',
            'subjek.required' => 'Pihak terkait / sumber dana / vendor wajib diisi.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'bukti.max' => 'Ukuran file lampiran bukti maksimal 5MB.',
            'bukti.mimes' => 'Format file bukti harus berupa JPG, PNG, atau PDF.',
        ]);

        try {
            DB::beginTransaction();

            // Konstruksi waktu presisi
            $tanggal = $request->input('tanggal');
            $jam = $request->input('jam');
            if (empty($jam)) {
                $jam = date('H:i:s');
            } elseif (strlen($jam) === 5) {
                $jam .= ':00';
            }
            $datetime = Carbon::parse("{$tanggal} {$jam}");

            // Generate kode transaksi unik
            $kode = TransaksiKeuangan::generateKode($request->input('jenis_transaksi'), $datetime);

            // Upload file bukti (opsional)
            $buktiPath = null;
            if ($request->hasFile('bukti')) {
                $file = $request->file('bukti');
                $filename = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $buktiPath = $file->storeAs('bukti_transaksi', $filename, 'public');
            }

            $admin = auth()->user();

            // Kategori ID jika ada
            $kategoriId = $request->input('kategori_id');
            if (!$kategoriId && $request->filled('kategori')) {
                $kat = MasterKategoriTransaksi::where('nama_kategori', $request->input('kategori'))->first();
                if ($kat) {
                    $kategoriId = $kat->id;
                }
            }

            $userId = $request->input('user_id', $admin ? $admin->id : null);

            $transaksi = TransaksiKeuangan::create([
                'kode_transaksi' => $kode,
                'jenis_transaksi' => $request->input('jenis_transaksi'),
                'jenis_arus_kas' => $request->input('jenis_transaksi'),
                'kategori' => $request->input('kategori'),
                'kategori_id' => $kategoriId,
                'nominal' => (float) $request->input('nominal'),
                'tanggal_transaksi' => $datetime,
                'waktu_presisi' => $jam,
                'metode_pembayaran' => $request->input('metode_pembayaran'),
                'subjek' => trim($request->input('subjek')),
                'pemberi_dana' => trim($request->input('subjek')),
                'user_id' => $userId,
                'admin_id' => $admin ? $admin->id : null,
                'admin_nama' => $admin ? $admin->name : 'Admin Keuangan',
                'deskripsi' => $request->input('deskripsi'),
                'keterangan' => $request->input('deskripsi'),
                'no_referensi' => $request->input('no_referensi'),
                'bukti' => $buktiPath,
                'status' => 'verified',
            ]);

            DB::commit();

            $tipeLabel = $request->input('jenis_transaksi') === 'masuk' ? 'Uang Masuk' : 'Uang Keluar';
            $nominalFormatted = number_format($request->input('nominal'), 0, ',', '.');
            $msg = "Transaksi kas {$tipeLabel} [{$kode}] sebesar Rp {$nominalFormatted} berhasil disimpan ke database.";

            session()->flash('success', $msg);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $msg,
                    'data' => $transaksi,
                    'redirect' => route('backend.keuangan.pemasukan-pengeluaran.index'),
                ]);
            }

            return redirect()->route('backend.keuangan.pemasukan-pengeluaran.index')
                ->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan transaksi kas: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan transaksi kas: ' . $e->getMessage(),
                ], 422);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan transaksi kas ke database: ' . $e->getMessage());
        }
    }

    /**
     * Perbarui data transaksi kas jika terdapat kesalahan input.
     */
    public function update(Request $request, $id)
    {
        $transaksi = TransaksiKeuangan::findOrFail($id);

        if (!$request->filled('jenis_transaksi') && $request->filled('jenis_arus_kas')) {
            $request->merge(['jenis_transaksi' => strtolower($request->input('jenis_arus_kas'))]);
        }
        if (!$request->filled('subjek') && $request->filled('pemberi_dana')) {
            $request->merge(['subjek' => $request->input('pemberi_dana')]);
        }
        if (!$request->filled('deskripsi') && $request->filled('keterangan')) {
            $request->merge(['deskripsi' => $request->input('keterangan')]);
        }
        if (!$request->filled('tanggal') && $request->filled('tanggal_transaksi')) {
            $val = $request->input('tanggal_transaksi');
            $request->merge(['tanggal' => is_string($val) && strlen($val) > 10 ? substr($val, 0, 10) : $val]);
        }
        if (!$request->filled('jam') && $request->filled('waktu_presisi')) {
            $request->merge(['jam' => $request->input('waktu_presisi')]);
        }
        if (!$request->filled('kategori') && $request->filled('kategori_id')) {
            $kat = MasterKategoriTransaksi::find($request->input('kategori_id'));
            if ($kat) {
                $request->merge(['kategori' => $kat->nama_kategori]);
            }
        }

        if ($request->filled('nominal') && is_string($request->input('nominal'))) {
            $cleanedNominal = str_replace(['Rp', 'rp', '.', ' '], '', $request->input('nominal'));
            $cleanedNominal = str_replace(',', '.', $cleanedNominal);
            if (is_numeric($cleanedNominal)) {
                $request->merge(['nominal' => $cleanedNominal]);
            }
        }

        $request->validate([
            'jenis_transaksi' => 'required|in:masuk,keluar',
            'kategori' => 'required|string|max:100',
            'nominal' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'jam' => 'nullable|string',
            'metode_pembayaran' => 'required|string|max:100',
            'subjek' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'no_referensi' => 'nullable|string|max:100',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'jenis_transaksi.required' => 'Jenis arus kas wajib dipilih.',
            'kategori.required' => 'Kategori transaksi wajib dipilih.',
            'nominal.required' => 'Nominal transaksi wajib diisi.',
            'subjek.required' => 'Pihak terkait / vendor wajib diisi.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'bukti.max' => 'Ukuran file lampiran bukti maksimal 5MB.',
        ]);

        try {
            DB::beginTransaction();

            $tanggal = $request->input('tanggal');
            $jam = $request->input('jam');
            if (empty($jam)) {
                $jam = $transaksi->tanggal_transaksi ? $transaksi->tanggal_transaksi->format('H:i:s') : date('H:i:s');
            } elseif (strlen($jam) === 5) {
                $jam .= ':00';
            }
            $datetime = Carbon::parse("{$tanggal} {$jam}");

            // Ganti file bukti jika ada upload baru
            $buktiPath = $transaksi->bukti;
            if ($request->hasFile('bukti')) {
                if ($transaksi->bukti && Storage::disk('public')->exists($transaksi->bukti)) {
                    Storage::disk('public')->delete($transaksi->bukti);
                }
                $file = $request->file('bukti');
                $filename = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $buktiPath = $file->storeAs('bukti_transaksi', $filename, 'public');
            }

            $kategoriId = $request->input('kategori_id');
            if (!$kategoriId && $request->filled('kategori')) {
                $kat = MasterKategoriTransaksi::where('nama_kategori', $request->input('kategori'))->first();
                if ($kat) {
                    $kategoriId = $kat->id;
                }
            }

            $transaksi->update([
                'jenis_transaksi' => $request->input('jenis_transaksi'),
                'jenis_arus_kas' => $request->input('jenis_transaksi'),
                'kategori' => $request->input('kategori'),
                'kategori_id' => $kategoriId ?: $transaksi->kategori_id,
                'nominal' => (float) $request->input('nominal'),
                'tanggal_transaksi' => $datetime,
                'waktu_presisi' => $jam,
                'metode_pembayaran' => $request->input('metode_pembayaran'),
                'subjek' => trim($request->input('subjek')),
                'pemberi_dana' => trim($request->input('subjek')),
                'deskripsi' => $request->input('deskripsi'),
                'keterangan' => $request->input('deskripsi'),
                'no_referensi' => $request->input('no_referensi'),
                'bukti' => $buktiPath,
            ]);

            DB::commit();

            $msg = "Transaksi [{$transaksi->kode_transaksi}] berhasil diperbarui di database.";
            session()->flash('success', $msg);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $msg,
                    'data' => $transaksi,
                    'redirect' => route('backend.keuangan.pemasukan-pengeluaran.index'),
                ]);
            }

            return redirect()->route('backend.keuangan.pemasukan-pengeluaran.index')
                ->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal update transaksi kas: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui transaksi kas: ' . $e->getMessage(),
                ], 422);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Hapus transaksi kas dari sistem.
     */
    public function destroy($id)
    {
        $transaksi = TransaksiKeuangan::with(['pembayaran.tagihan'])->findOrFail($id);

        if ($transaksi->bukti && Storage::disk('public')->exists($transaksi->bukti)) {
            Storage::disk('public')->delete($transaksi->bukti);
        }

        // Jika transaksi ini berkaitan dengan pembayaran mahasiswa yang diverifikasi
        if ($transaksi->pembayaran_id && $transaksi->pembayaran) {
            $pembayaran = $transaksi->pembayaran;
            $pembayaran->status = 'rejected';
            if ($pembayaran->tagihan) {
                $tagihan = $pembayaran->tagihan;
                $tagihan->sisa_tagihan += $pembayaran->nominal;
                if ($tagihan->sisa_tagihan > 0) {
                    $tagihan->status = 'belum lunas';
                }
                $tagihan->save();
            }
            $pembayaran->save();
        }

        $kode = $transaksi->kode_transaksi;
        $transaksi->delete();

        $msg = "Transaksi [{$kode}] berhasil dihapus dari pencatatan kas.";
        session()->flash('success', $msg);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'redirect' => route('backend.keuangan.pemasukan-pengeluaran.index'),
            ]);
        }

        return redirect()->route('backend.keuangan.pemasukan-pengeluaran.index')
            ->with('success', $msg);
    }

    /**
     * Cetak kwitansi transaksi kas.
     */
    public function cetakKwitansi($id)
    {
        Carbon::setLocale('id');
        $transaksi = TransaksiKeuangan::with(['user', 'pembayaran.tagihan'])->findOrFail($id);

        return view('backend.keuangan.riwayat-transaksi.kwitansi', compact('transaksi'));
    }

    /**
     * Tambah Kategori Baru ke Master Kategori Transaksi.
     */
    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:master_kategori_transaksis,nama_kategori',
            'tipe' => 'required|in:masuk,keluar,umum',
        ], [
            'nama_kategori.required' => 'Nama kategori transaksi wajib diisi.',
            'nama_kategori.unique' => 'Kategori tersebut sudah ada dalam master data.',
            'tipe.required' => 'Tipe arus kas wajib ditentukan.',
        ]);

        MasterKategoriTransaksi::create([
            'nama_kategori' => trim($request->input('nama_kategori')),
            'tipe' => $request->input('tipe'),
        ]);

        return back()->with('success', "Kategori '{$request->input('nama_kategori')}' berhasil ditambahkan ke Master Kategori.");
    }

    /**
     * Perbarui Kategori Transaksi.
     */
    public function updateKategori(Request $request, $id)
    {
        $kategori = MasterKategoriTransaksi::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:master_kategori_transaksis,nama_kategori,' . $id,
            'tipe' => 'required|in:masuk,keluar,umum',
        ]);

        $oldName = $kategori->nama_kategori;
        $newName = trim($request->input('nama_kategori'));

        $kategori->update([
            'nama_kategori' => $newName,
            'tipe' => $request->input('tipe'),
        ]);

        if ($oldName !== $newName) {
            TransaksiKeuangan::where('kategori', $oldName)->update(['kategori' => $newName]);
        }

        return back()->with('success', "Kategori '{$newName}' berhasil diperbarui.");
    }

    /**
     * Hapus Kategori Transaksi.
     */
    public function destroyKategori($id)
    {
        $kategori = MasterKategoriTransaksi::findOrFail($id);

        $countUsage = TransaksiKeuangan::where('kategori', $kategori->nama_kategori)->count();
        if ($countUsage > 0) {
            return back()->with('error', "Kategori '{$kategori->nama_kategori}' tidak dapat dihapus karena digunakan pada {$countUsage} transaksi.");
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();

        return back()->with('success', "Kategori '{$nama}' berhasil dihapus.");
    }
}
