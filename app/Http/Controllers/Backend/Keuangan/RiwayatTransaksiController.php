<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\MasterKategoriTransaksi;
use App\Models\Pembayaran;
use App\Models\TransaksiKeuangan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RiwayatTransaksiController extends Controller
{
    /**
     * Display a listing of financial transactions (Buku Kas / Riwayat Transaksi).
     */
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        // Auto cleanup orphaned payments & transactions (pembayaran atau transaksi yang tagihannya telah dihapus)
        Pembayaran::whereNull('tagihan_id')->orWhereDoesntHave('tagihan')->delete();

        TransaksiKeuangan::where(function($q) {
            $q->whereNotNull('tagihan_id')->whereDoesntHave('tagihan')
              ->orWhereNotNull('pembayaran_id')->whereDoesntHave('pembayaran')
              ->orWhere(function($sub) {
                  $sub->whereNull('tagihan_id')
                      ->whereNull('pembayaran_id')
                      ->where(function($k) {
                          $k->where('kode_transaksi', 'like', 'TRX-SPP-%')
                            ->orWhere('kode_transaksi', 'like', 'TRX-IN-%');
                      });
              });
        })->delete();

        // Build base query according to active filters
        $query = $this->buildFilterQuery($request);

        // Summary Bar calculation for currently filtered query
        $totalMasuk = (float) (clone $query)->where('jenis_transaksi', 'masuk')->where('status', 'verified')->sum('nominal');
        $countMasuk = (clone $query)->where('jenis_transaksi', 'masuk')->where('status', 'verified')->count();

        $totalKeluar = (float) (clone $query)->where('jenis_transaksi', 'keluar')->where('status', 'verified')->sum('nominal');
        $countKeluar = (clone $query)->where('jenis_transaksi', 'keluar')->where('status', 'verified')->count();

        $netCashflow = $totalMasuk - $totalKeluar;

        // Paginate results (50 per page, latest first)
        $transaksis = $query->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(50)
            ->withQueryString();

        // Master Kategori Transaksi
        $masterKategoris = MasterKategoriTransaksi::orderByRaw("CASE WHEN tipe = 'masuk' THEN 1 WHEN tipe = 'keluar' THEN 2 ELSE 3 END")
            ->orderBy('nama_kategori', 'asc')
            ->get();

        // Get distinct categories from existing database records for filter dropdown
        $existingCategories = TransaksiKeuangan::select('kategori')
            ->distinct()
            ->pluck('kategori')
            ->filter()
            ->values()
            ->toArray();

        $masterCategoryNames = $masterKategoris->pluck('nama_kategori')->toArray();

        $defaultCategories = [
            'Pembayaran SPP',
            'Tagihan Mahasiswa',
            'Pemasukan Lain Yayasan',
            'Pemasukan Hibah & Kerjasama',
            'Pemasukan Pendaftaran PMB',
            'Pengeluaran Operasional',
            'Gaji & Honorarium',
            'Sarana & Prasarana Kampus',
            'Listrik, Air & Internet',
            'Kegiatan & Kemahasiswaan',
            'Lain-lain',
        ];

        $kategoris = collect(array_merge($masterCategoryNames, $defaultCategories, $existingCategories))->unique()->values();

        $periodeLabel = $this->getPeriodeLabel($request);

        $currentYear = (int) date('Y');
        $tahunList = range($currentYear + 1, $currentYear - 3);
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('backend.keuangan.riwayat-transaksi.index', compact(
            'transaksis',
            'totalMasuk',
            'countMasuk',
            'totalKeluar',
            'countKeluar',
            'netCashflow',
            'kategoris',
            'masterKategoris',
            'periodeLabel',
            'tahunList',
            'bulanList'
        ));
    }

    /**
     * Store a new internal cash transaction (Pemasukan Lain / Pengeluaran Operasional).
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
            'deskripsi' => 'nullable|string',
            'no_referensi' => 'nullable|string|max:100',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'jenis_transaksi.required' => 'Jenis arus kas (Uang Masuk / Keluar) wajib dipilih.',
            'kategori.required' => 'Kategori transaksi wajib diisi.',
            'nominal.required' => 'Nominal transaksi wajib diisi.',
            'tanggal.required' => 'Tanggal transaksi wajib diisi.',
            'subjek.required' => 'Pihak terkait / sumber / tujuan transaksi wajib diisi.',
            'metode_pembayaran.required' => 'Metode pembayaran wajib dipilih.',
            'bukti.max' => 'Ukuran file bukti maksimal 5MB.',
        ]);

        // Construct precise datetime
        $tanggal = $request->input('tanggal');
        $jam = $request->input('jam');
        if (empty($jam)) {
            $jam = date('H:i:s');
        } elseif (strlen($jam) === 5) {
            $jam .= ':00';
        }
        $datetime = Carbon::parse("{$tanggal} {$jam}");

        // Generate unique transaction code
        $kode = TransaksiKeuangan::generateKode($request->input('jenis_transaksi'), $datetime);

        // Handle file upload
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
            'subjek' => $request->input('subjek'),
            'admin_id' => $admin ? $admin->id : null,
            'admin_nama' => $admin ? $admin->name : 'Admin Keuangan',
            'deskripsi' => $request->input('deskripsi'),
            'no_referensi' => $request->input('no_referensi'),
            'bukti' => $buktiPath,
            'status' => 'verified',
        ]);

        $tipeLabel = $request->input('jenis_transaksi') === 'masuk' ? 'Pemasukan' : 'Pengeluaran';
        return back()->with('success', "Berhasil mencatat transaksi kas {$tipeLabel} [{$kode}].");
    }

    /**
     * Delete a transaction from Buku Kas.
     */
    public function destroy($id)
    {
        $transaksi = TransaksiKeuangan::with(['pembayaran.tagihan'])->findOrFail($id);

        if ($transaksi->bukti && Storage::disk('public')->exists($transaksi->bukti)) {
            Storage::disk('public')->delete($transaksi->bukti);
        }

        // Jika transaksi ini berasal dari pembayaran mahasiswa, sesuaikan status pembayaran & kembalikan tagihan
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

        return back()->with('success', "Transaksi [{$kode}] berhasil dihapus dari Buku Kas.");
    }

    /**
     * Printable Kwitansi / Bukti Transaksi Kas.
     */
    public function cetakKwitansi($id)
    {
        Carbon::setLocale('id');
        $transaksi = TransaksiKeuangan::with(['user', 'pembayaran.tagihan'])->findOrFail($id);

        return view('backend.keuangan.riwayat-transaksi.kwitansi', compact('transaksi'));
    }

    /**
     * Export Riwayat Transaksi to Excel.
     */
    public function exportExcel(Request $request)
    {
        Carbon::setLocale('id');
        $filename = "Laporan_Mutasi_Kas_MATLA_" . date('Ymd_His') . ".xls";

        $transaksis = $this->buildFilterQuery($request)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $headers = [
            "Content-Type"        => "application/vnd.ms-excel; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"{$filename}\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $totalMasuk = (float) $transaksis->where('jenis_transaksi', 'masuk')->sum('nominal');
        $totalKeluar = (float) $transaksis->where('jenis_transaksi', 'keluar')->sum('nominal');
        $netCashflow = $totalMasuk - $totalKeluar;
        $periodeLabel = $this->getPeriodeLabel($request);

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head>';
        $html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $html .= '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Mutasi Kas</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
        $html .= '<style>';
        $html .= 'table { border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; }';
        $html .= 'th { background-color: #00703C; color: #FFFFFF; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; height: 38px; padding: 6px 10px; font-size: 10pt; }';
        $html .= 'td { border: 1px solid #000000; vertical-align: middle; color: #000000; padding: 6px 8px; font-size: 9.5pt; }';
        $html .= '.bg-masuk { background-color: #E8F5E9; color: #1B5E20; font-weight: bold; text-align: right; }';
        $html .= '.bg-keluar { background-color: #FFEBEE; color: #B71C1C; font-weight: bold; text-align: right; }';
        $html .= '</style>';
        $html .= '</head>';
        $html .= '<body>';

        // Title and summary box in Excel with official MATLA Kop
        $html .= '<table border="0" cellpadding="4" cellspacing="0" style="margin-bottom: 12px; font-family: Arial, sans-serif;">';
        $html .= '<tr><td colspan="11" style="font-size: 14pt; font-weight: bold; color: #00703C; border: none; text-align: center;">KAMPUS MAHAD TA\'LIM LUGHOH AROBIYYAH (MATLA)</td></tr>';
        $html .= '<tr><td colspan="11" style="font-size: 11pt; font-weight: bold; color: #1e293b; border: none; text-align: center;">LAPORAN BUKU KAS & MUTASI TRANSAKSI KEUANGAN</td></tr>';
        $html .= '<tr><td colspan="11" style="font-size: 9pt; color: #555555; border: none; text-align: center;">Jl. H. Basir Pondok Kacang Barat, Gg. Mushola, Pondok Kacang Barat, Kec. Pondok Aren, Kota Tangerang Selatan, Banten</td></tr>';
        $html .= '<tr><td colspan="11" style="font-size: 9pt; color: #555555; border: none; text-align: center;">No. Hp. 081273510994, Email: matlaislamicuniversity@gmail.com</td></tr>';
        $html .= '<tr><td colspan="11" style="border: none;"></td></tr>';
        $html .= '<tr><td colspan="11" style="font-size: 9.5pt; color: #333333; font-weight: bold; border: none;">Periode: ' . htmlspecialchars($periodeLabel, ENT_QUOTES, 'UTF-8') . '  |  Tanggal Unduh: ' . Carbon::now()->translatedFormat('d F Y, H:i') . ' WIB</td></tr>';
        $html .= '<tr><td colspan="11" style="font-size: 9.5pt; color: #333333; font-weight: bold; border: none;">Total Masuk: Rp ' . number_format($totalMasuk, 0, ',', '.') . '  |  Total Keluar: Rp ' . number_format($totalKeluar, 0, ',', '.') . '  |  Arus Kas Bersih: Rp ' . number_format($netCashflow, 0, ',', '.') . '</td></tr>';
        $html .= '<tr><td colspan="11" style="border: none;"></td></tr>';
        $html .= '</table>';

        $html .= '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; border: 1px solid #000000; font-family: Arial, sans-serif;">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th style="background-color: #00703C; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000; width: 45px;">NO</th>';
        $html .= '<th style="background-color: #00703C; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000; width: 175px;">KODE TRANSAKSI</th>';
        $html .= '<th style="background-color: #00703C; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000; width: 165px;">WAKTU (TGL & JAM)</th>';
        $html .= '<th style="background-color: #00703C; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000; width: 120px;">JENIS ARUS KAS</th>';
        $html .= '<th style="background-color: #00703C; color: #FFFFFF; font-weight: bold; text-align: left; border: 1px solid #000000; min-width: 150px;">KATEGORI</th>';
        $html .= '<th style="background-color: #00703C; color: #FFFFFF; font-weight: bold; text-align: left; border: 1px solid #000000; min-width: 200px;">SUBJEK / PIHAK TERKAIT</th>';
        $html .= '<th style="background-color: #00703C; color: #FFFFFF; font-weight: bold; text-align: left; border: 1px solid #000000; min-width: 240px;">KETERANGAN / RINCIAN</th>';
        $html .= '<th style="background-color: #00703C; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000; width: 120px;">METODE</th>';
        $html .= '<th style="background-color: #00703C; color: #FFFFFF; font-weight: bold; text-align: right; border: 1px solid #000000; min-width: 140px;">NOMINAL (RP)</th>';
        $html .= '<th style="background-color: #00703C; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000; width: 130px;">NO. REFERENSI</th>';
        $html .= '<th style="background-color: #00703C; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #000000; width: 130px;">PETUGAS</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        $no = 1;
        foreach ($transaksis as $tx) {
            $isMasuk = $tx->isMasuk();
            $nominalFormatted = ($isMasuk ? '+ Rp ' : '- Rp ') . number_format($tx->nominal, 0, ',', '.');
            $nominalClass = $isMasuk ? 'bg-masuk' : 'bg-keluar';
            $waktu = $tx->tanggal_transaksi ? $tx->tanggal_transaksi->translatedFormat('d/m/Y H:i:s') . ' WIB' : '-';
            $jenisLabel = $isMasuk ? 'Uang Masuk' : 'Uang Keluar';
            $ref = $tx->no_referensi ?: '-';
            $admin = $tx->admin_nama ?: 'Admin Keuangan';

            $html .= '<tr style="background-color: #FFFFFF; height: 28px;">';
            $html .= '<td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">' . $no++ . '</td>';
            $html .= '<td style="border: 1px solid #000000; text-align: center; vertical-align: middle; mso-number-format:\'@\'; font-weight: bold;">' . htmlspecialchars($tx->kode_transaksi, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td style="border: 1px solid #000000; text-align: center; vertical-align: middle; mso-number-format:\'@\';">' . htmlspecialchars($waktu, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td style="border: 1px solid #000000; text-align: center; vertical-align: middle; font-weight: bold; color: ' . ($isMasuk ? '#1B5E20' : '#B71C1C') . ';">' . htmlspecialchars($jenisLabel, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td style="border: 1px solid #000000; text-align: left; vertical-align: middle;">' . htmlspecialchars($tx->kategori, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td style="border: 1px solid #000000; text-align: left; vertical-align: middle; font-weight: 500;">' . htmlspecialchars($tx->subjek ?: '-', ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td style="border: 1px solid #000000; text-align: left; vertical-align: middle;">' . htmlspecialchars($tx->deskripsi ?: '-', ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">' . htmlspecialchars($tx->metode_pembayaran ?: 'Transfer Bank', ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td class="' . $nominalClass . '" style="border: 1px solid #000000; vertical-align: middle; mso-number-format:\'@\'; text-align: right;">' . $nominalFormatted . '</td>';
            $html .= '<td style="border: 1px solid #000000; text-align: center; vertical-align: middle; mso-number-format:\'@\';">' . htmlspecialchars($ref, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">' . htmlspecialchars($admin, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '</tr>';
        }

        // Summary footer row
        $html .= '<tr style="background-color: #F1F5F9; font-weight: bold; height: 32px;">';
        $html .= '<td colspan="8" style="border: 1px solid #000000; text-align: right; padding-right: 12px; font-weight: bold;">TOTAL TRANSAKSI MASUK:</td>';
        $html .= '<td class="bg-masuk" style="border: 1px solid #000000; mso-number-format:\'@\'; text-align: right;">+ Rp ' . number_format($totalMasuk, 0, ',', '.') . '</td>';
        $html .= '<td colspan="2" style="border: 1px solid #000000;"></td>';
        $html .= '</tr>';

        $html .= '<tr style="background-color: #F1F5F9; font-weight: bold; height: 32px;">';
        $html .= '<td colspan="8" style="border: 1px solid #000000; text-align: right; padding-right: 12px; font-weight: bold;">TOTAL TRANSAKSI KELUAR:</td>';
        $html .= '<td class="bg-keluar" style="border: 1px solid #000000; mso-number-format:\'@\'; text-align: right;">- Rp ' . number_format($totalKeluar, 0, ',', '.') . '</td>';
        $html .= '<td colspan="2" style="border: 1px solid #000000;"></td>';
        $html .= '</tr>';

        $html .= '<tr style="background-color: #E2E8F0; font-weight: bold; height: 34px;">';
        $html .= '<td colspan="8" style="border: 1px solid #000000; text-align: right; padding-right: 12px; font-size: 10.5pt; font-weight: bold;">ARUS KAS BERSIH (NET CASHFLOW):</td>';
        $html .= '<td style="border: 1px solid #000000; text-align: right; font-size: 10.5pt; font-weight: bold; mso-number-format:\'@\'; color: ' . ($netCashflow >= 0 ? '#1B5E20' : '#B71C1C') . ';">Rp ' . number_format($netCashflow, 0, ',', '.') . '</td>';
        $html .= '<td colspan="2" style="border: 1px solid #000000;"></td>';
        $html .= '</tr>';

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</body>';
        $html .= '</html>';

        return response($html, 200, $headers);
    }

    /**
     * Export Riwayat Transaksi to PDF.
     */
    public function exportPdf(Request $request)
    {
        Carbon::setLocale('id');

        $transaksis = $this->buildFilterQuery($request)
            ->orderBy('tanggal_transaksi', 'desc')
            ->get();

        $totalMasuk = (float) $transaksis->where('jenis_transaksi', 'masuk')->sum('nominal');
        $totalKeluar = (float) $transaksis->where('jenis_transaksi', 'keluar')->sum('nominal');
        $netCashflow = $totalMasuk - $totalKeluar;
        $periodeLabel = $this->getPeriodeLabel($request);

        $pdf = Pdf::loadView('backend.keuangan.riwayat-transaksi.export-pdf', compact(
            'transaksis',
            'totalMasuk',
            'totalKeluar',
            'netCashflow',
            'periodeLabel'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Mutasi_Kas_MATLA_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Build standard filter query based on request parameters.
     */
    private function buildFilterQuery(Request $request)
    {
        $query = TransaksiKeuangan::query()->where('status', 'verified');

        // Search by kode_transaksi, subjek, deskripsi, no_referensi, or user name/nim
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%{$search}%")
                  ->orWhere('subjek', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('no_referensi', 'like', "%{$search}%")
                  ->orWhere('admin_nama', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Jenis Transaksi (masuk / keluar)
        if ($request->filled('jenis') && in_array(strtolower($request->input('jenis')), ['masuk', 'keluar'])) {
            $query->where('jenis_transaksi', strtolower($request->input('jenis')));
        }

        // Filter Kategori
        if ($request->filled('kategori') && $request->input('kategori') !== 'semua') {
            $kategori = trim($request->input('kategori'));
            if ($kategori === 'spp') {
                $query->whereRaw('LOWER(kategori) LIKE ?', ['%spp%']);
            } elseif ($kategori === 'non-spp') {
                $query->whereRaw('LOWER(kategori) NOT LIKE ?', ['%spp%'])
                      ->where('jenis_transaksi', 'masuk');
            } else {
                $query->where('kategori', $kategori);
            }
        }

        // Filter Preset (hari_ini, bulan_ini, tahun_ini)
        if ($request->filled('preset')) {
            $preset = $request->input('preset');
            if ($preset === 'hari_ini') {
                $query->whereDate('tanggal_transaksi', Carbon::today());
            } elseif ($preset === 'bulan_ini') {
                $query->whereBetween('tanggal_transaksi', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            } elseif ($preset === 'tahun_ini') {
                $query->whereBetween('tanggal_transaksi', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()]);
            }
        }

        // Filter Date Range (start_date & end_date) - overrides preset if provided
        if ($request->filled('start_date')) {
            $start = Carbon::parse($request->input('start_date'))->startOfDay();
            $query->where('tanggal_transaksi', '>=', $start);
        }
        if ($request->filled('end_date')) {
            $end = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->where('tanggal_transaksi', '<=', $end);
        }

        // Filter Month & Year / Periode (if start_date / end_date not explicitly filled and preset not filled)
        if (!$request->filled('start_date') && !$request->filled('end_date') && !$request->filled('preset')) {
            if ($request->filled('periode') && preg_match('/^\d{4}-\d{2}$/', $request->input('periode'))) {
                $parts = explode('-', $request->input('periode'));
                $tahun = (int) $parts[0];
                $bulan = (int) $parts[1];
                $startMonth = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
                $endMonth = $startMonth->copy()->endOfMonth();
                $query->whereBetween('tanggal_transaksi', [$startMonth, $endMonth]);
            } elseif ($request->filled('bulan') && $request->filled('tahun')) {
                $bulan = (int) $request->input('bulan');
                $tahun = (int) $request->input('tahun');
                $startMonth = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
                $endMonth = $startMonth->copy()->endOfMonth();
                $query->whereBetween('tanggal_transaksi', [$startMonth, $endMonth]);
            } elseif ($request->filled('bulan')) {
                $bulan = (int) $request->input('bulan');
                $tahun = (int) date('Y');
                $startMonth = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
                $endMonth = $startMonth->copy()->endOfMonth();
                $query->whereBetween('tanggal_transaksi', [$startMonth, $endMonth]);
            } elseif ($request->filled('tahun')) {
                $tahun = (int) $request->input('tahun');
                $startYear = Carbon::createFromDate($tahun, 1, 1)->startOfYear();
                $endYear = $startYear->copy()->endOfYear();
                $query->whereBetween('tanggal_transaksi', [$startYear, $endYear]);
            }
        }

        return $query;
    }

    /**
     * Get human-readable period label for exports & UI.
     */
    private function getPeriodeLabel(Request $request): string
    {
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->input('start_date'))->translatedFormat('d M Y');
            $end = Carbon::parse($request->input('end_date'))->translatedFormat('d M Y');
            return "{$start} s/d {$end}";
        } elseif ($request->filled('start_date')) {
            return 'Mulai ' . Carbon::parse($request->input('start_date'))->translatedFormat('d M Y');
        } elseif ($request->filled('end_date')) {
            return 'Sampai ' . Carbon::parse($request->input('end_date'))->translatedFormat('d M Y');
        }

        if ($request->filled('preset')) {
            switch ($request->input('preset')) {
                case 'hari_ini':
                    return 'Hari Ini (' . Carbon::today()->translatedFormat('d F Y') . ')';
                case 'bulan_ini':
                    return 'Bulan Ini (' . Carbon::now()->translatedFormat('F Y') . ')';
                case 'tahun_ini':
                    return 'Tahun Ini (' . Carbon::now()->format('Y') . ')';
            }
        }

        if ($request->filled('periode') && preg_match('/^\d{4}-\d{2}$/', $request->input('periode'))) {
            $parts = explode('-', $request->input('periode'));
            $bulanName = Carbon::createFromDate((int)$parts[0], (int)$parts[1], 1)->translatedFormat('F');
            return "Bulan {$bulanName} " . $parts[0];
        }

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $bulanName = Carbon::createFromDate((int)$request->input('tahun'), (int)$request->input('bulan'), 1)->translatedFormat('F');
            return "Bulan {$bulanName} " . $request->input('tahun');
        } elseif ($request->filled('bulan')) {
            $bulanName = Carbon::createFromDate((int)date('Y'), (int)$request->input('bulan'), 1)->translatedFormat('F');
            return "Bulan {$bulanName} " . date('Y');
        } elseif ($request->filled('tahun')) {
            return 'Tahun ' . $request->input('tahun');
        }

        return 'Semua Periode Mutasi Kas';
    }

    /**
     * Store a new Master Kategori Transaksi.
     */
    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:master_kategori_transaksis,nama_kategori',
            'tipe' => 'required|in:masuk,keluar,umum',
        ], [
            'nama_kategori.required' => 'Nama kategori transaksi wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori transaksi tersebut sudah ada.',
            'tipe.required' => 'Tipe arus kas kategori wajib dipilih.',
        ]);

        MasterKategoriTransaksi::create([
            'nama_kategori' => trim($request->input('nama_kategori')),
            'tipe' => $request->input('tipe'),
        ]);

        return back()->with('success', "Kategori '{$request->input('nama_kategori')}' berhasil ditambahkan ke Master Kategori.");
    }

    /**
     * Update an existing Master Kategori Transaksi.
     */
    public function updateKategori(Request $request, $id)
    {
        $kategori = MasterKategoriTransaksi::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:master_kategori_transaksis,nama_kategori,' . $id,
            'tipe' => 'required|in:masuk,keluar,umum',
        ], [
            'nama_kategori.required' => 'Nama kategori transaksi wajib diisi.',
            'nama_kategori.unique' => 'Nama kategori transaksi tersebut sudah digunakan.',
            'tipe.required' => 'Tipe arus kas kategori wajib dipilih.',
        ]);

        $oldName = $kategori->nama_kategori;
        $newName = trim($request->input('nama_kategori'));

        $kategori->update([
            'nama_kategori' => $newName,
            'tipe' => $request->input('tipe'),
        ]);

        // Sync existing transactions with the new category name if it was renamed
        if ($oldName !== $newName) {
            TransaksiKeuangan::where('kategori', $oldName)->update(['kategori' => $newName]);
        }

        return back()->with('success', "Kategori '{$newName}' berhasil diperbarui.");
    }

    /**
     * Delete a Master Kategori Transaksi.
     */
    public function destroyKategori($id)
    {
        $kategori = MasterKategoriTransaksi::findOrFail($id);

        // Check if category is used in TransaksiKeuangan
        $countUsage = TransaksiKeuangan::where('kategori', $kategori->nama_kategori)->count();
        if ($countUsage > 0) {
            return back()->with('error', "Kategori '{$kategori->nama_kategori}' tidak dapat dihapus karena digunakan pada {$countUsage} riwayat transaksi.");
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();

        return back()->with('success', "Kategori '{$nama}' berhasil dihapus dari Master Kategori.");
    }
}
