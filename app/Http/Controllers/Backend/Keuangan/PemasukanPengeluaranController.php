<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\MasterKategoriTransaksi;
use App\Models\TransaksiKeuangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            'kategori.required' => 'Kategori transaksi wajib dipilih.',
            'nominal.required' => 'Nominal transaksi wajib diisi.',
            'nominal.numeric' => 'Nominal harus berupa angka yang valid.',
            'nominal.min' => 'Nominal transaksi minimal Rp 1.',
            'tanggal.required' => 'Tanggal transaksi wajib diisi.',
            'subjek.required' => 'Pihak terkait / sumber dana / vendor wajib diisi.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'bukti.max' => 'Ukuran file lampiran bukti maksimal 5MB.',
            'bukti.mimes' => 'Format file bukti harus berupa JPG, PNG, atau PDF.',
        ]);

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

        TransaksiKeuangan::create([
            'kode_transaksi' => $kode,
            'jenis_transaksi' => $request->input('jenis_transaksi'),
            'kategori' => $request->input('kategori'),
            'nominal' => (float) $request->input('nominal'),
            'tanggal_transaksi' => $datetime,
            'metode_pembayaran' => $request->input('metode_pembayaran'),
            'subjek' => trim($request->input('subjek')),
            'admin_id' => $admin ? $admin->id : null,
            'admin_nama' => $admin ? $admin->name : 'Admin Keuangan',
            'deskripsi' => $request->input('deskripsi'),
            'no_referensi' => $request->input('no_referensi'),
            'bukti' => $buktiPath,
            'status' => 'verified',
        ]);

        $tipeLabel = $request->input('jenis_transaksi') === 'masuk' ? 'Uang Masuk' : 'Uang Keluar';
        $nominalFormatted = number_format($request->input('nominal'), 0, ',', '.');

        return redirect()->route('backend.keuangan.pemasukan-pengeluaran.index')
            ->with('success', "Transaksi kas {$tipeLabel} [{$kode}] sebesar Rp {$nominalFormatted} berhasil disimpan.");
    }

    /**
     * Perbarui data transaksi kas jika terdapat kesalahan input.
     */
    public function update(Request $request, $id)
    {
        $transaksi = TransaksiKeuangan::findOrFail($id);

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

        $transaksi->update([
            'jenis_transaksi' => $request->input('jenis_transaksi'),
            'kategori' => $request->input('kategori'),
            'nominal' => (float) $request->input('nominal'),
            'tanggal_transaksi' => $datetime,
            'metode_pembayaran' => $request->input('metode_pembayaran'),
            'subjek' => trim($request->input('subjek')),
            'deskripsi' => $request->input('deskripsi'),
            'no_referensi' => $request->input('no_referensi'),
            'bukti' => $buktiPath,
        ]);

        return redirect()->route('backend.keuangan.pemasukan-pengeluaran.index')
            ->with('success', "Transaksi [{$transaksi->kode_transaksi}] berhasil diperbarui.");
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

        return redirect()->route('backend.keuangan.pemasukan-pengeluaran.index')
            ->with('success', "Transaksi [{$kode}] berhasil dihapus dari pencatatan kas.");
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
