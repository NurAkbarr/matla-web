<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Exports\RekapSppSemesterExport;
use App\Http\Controllers\Controller;
use App\Models\CatatanRekapSpp;
use App\Models\MasterSemester;
use App\Models\MasterTahunAkademik;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\TransaksiKeuangan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class RekapSppController extends Controller
{
    /**
     * Tampilkan halaman Rekap SPP per Semester
     */
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        $data = $this->prepareRekapData($request);

        return view('backend.keuangan.rekap-spp.index', $data);
    }

    /**
     * Export Rekap SPP ke Excel
     */
    public function exportExcel(Request $request)
    {
        $data = $this->prepareRekapData($request);
        $semesterLabel = strtoupper($data['tipeSemester']);
        $tahunSlug = str_replace('/', '-', $data['tahunAkademik']);
        $filename = "Rekap_SPP_Semester_{$semesterLabel}_{$tahunSlug}.xlsx";

        return Excel::download(new RekapSppSemesterExport($data), $filename);
    }

    /**
     * Export Rekap SPP ke PDF (Format Lanskap Kop Resmi MATLA)
     */
    public function exportPdf(Request $request)
    {
        Carbon::setLocale('id');
        $data = $this->prepareRekapData($request);
        $semesterLabel = strtoupper($data['tipeSemester']);
        $tahunSlug = str_replace('/', '-', $data['tahunAkademik']);
        $filename = "Rekap_SPP_Semester_{$semesterLabel}_{$tahunSlug}.pdf";

        $pdf = Pdf::loadView('backend.keuangan.rekap-spp.export-pdf', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    /**
     * Toggle Checkbox Pembayaran Historis (Masa Lalu)
     * Otomatis sinkronisasi ke tabel transaksi_keuangans (Buku Kas) dengan tanggal historis akurat.
     */
    public function toggleHistoris(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month_key' => 'required|regex:/^\d{4}-\d{2}$/',
            'action' => 'required|in:check,uncheck',
            'nominal' => 'nullable|numeric|min:0',
            'tahun_akademik' => 'nullable|string',
            'tipe_semester' => 'nullable|string',
        ]);

        $userId = (int)$request->input('user_id');
        $monthKey = $request->input('month_key'); // format: 'YYYY-MM'
        $action = $request->input('action');
        $tahunAkademik = $request->input('tahun_akademik');
        $tipeSemester = strtolower($request->input('tipe_semester', ''));

        $student = User::where('role', 'mahasiswa')->findOrFail($userId);
        $nominalSpp = (float)($student->nominal_spp ?: 300000);

        // Parsing tahun dan nomor bulan
        $parts = explode('-', $monthKey);
        $year = (int)$parts[0];
        $monthNum = (int)$parts[1];

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $bNama = $monthNames[$monthNum] ?? "Bulan {$monthNum}";
        $tagihanNama = "SPP " . strtoupper($bNama) . " {$year}";
        $tanggalKas = Carbon::createFromDate($year, $monthNum, 1)->setTime(10, 0, 0);

        $kwitansiNo = null;
        $kwitansiUrl = null;
        $pembayaranId = null;
        $isPartial = false;
        $inputNominal = $nominalSpp;
        $sisaTagihan = 0;

        if ($action === 'check') {
            // Hitung nominal yang dibayarkan (mendukung pembayaran sebagian / cicilan)
            $inputNominal = $request->filled('nominal') ? (float)$request->input('nominal') : $nominalSpp;
            if ($inputNominal <= 0) {
                $inputNominal = $nominalSpp;
            }
            $sisaTagihan = max(0, $nominalSpp - $inputNominal);
            $isPartial = ($sisaTagihan > 0);

            $deskripsiTransaksi = $isPartial
                ? "Pembayaran SPP Sebagian Bulan {$bNama} {$year} (Catatan Historis: Rp " . number_format($inputNominal, 0, ',', '.') . " dari Rp " . number_format($nominalSpp, 0, ',', '.') . ")"
                : "Pelunasan SPP Bulan {$bNama} {$year} (Catatan Historis)";

            // 1. Temukan atau buat Tagihan untuk bulan historis ini
            $studentTagihans = Tagihan::where('user_id', $student->id)->get();
            $tagihan = $studentTagihans->first(function ($t) use ($monthNum, $year, $bNama) {
                return Tagihan::isMatchingSppMonth($t, $monthNum, $year, $bNama);
            });

            if (!$tagihan) {
                $tagihan = new Tagihan();
                $tagihan->user_id = $student->id;
                $tagihan->nama_tagihan = $tagihanNama;
                $tagihan->nominal_total = $nominalSpp;
                $tagihan->sisa_tagihan = $sisaTagihan;
                $tagihan->status = $isPartial ? 'sebagian' : 'lunas';
                $tagihan->jenis_tagihan = $isPartial ? 'cicilan' : 'lunas';
                $tagihan->keterangan = "Pencatatan Tagihan SPP Historis Bulan {$bNama} {$year}";
                $tagihan->jatuh_tempo = "{$year}-" . sprintf('%02d', $monthNum) . "-10";
                $tagihan->created_at = $tanggalKas;
                $tagihan->updated_at = $tanggalKas;
                $tagihan->save();
            } else {
                $tagihan->sisa_tagihan = $sisaTagihan;
                $tagihan->status = $isPartial ? 'sebagian' : 'lunas';
                $tagihan->jenis_tagihan = $isPartial ? 'cicilan' : 'lunas';
                $tagihan->save();
            }

            // 2. Terbitkan / Sinkron Kuitansi Pembayaran Historis
            $kwitansiNo = 'INV/' . sprintf('%04d%02d', $year, $monthNum) . '/HIST-' . str_pad($student->id, 3, '0', STR_PAD_LEFT) . strtoupper(Str::random(3));

            $existingPay = Pembayaran::where('user_id', $student->id)
                ->where('status', 'verified')
                ->where(function ($q) use ($monthKey, $tagihan) {
                    $q->whereJsonContains('bulan_alokasi', $monthKey)
                      ->orWhere('tagihan_id', $tagihan->id);
                })->first();

            if (!$existingPay) {
                $pembayaran = new Pembayaran();
                $pembayaran->tagihan_id = $tagihan->id;
                $pembayaran->user_id = $student->id;
                $pembayaran->jenis_pembayaran = $isPartial ? 'cicilan' : 'lunas';
                $pembayaran->nominal = $inputNominal;
                $pembayaran->bukti_tf = 'historis';
                $pembayaran->kwitansi = $kwitansiNo;
                $pembayaran->status = 'verified';
                $pembayaran->bulan_alokasi = [$monthKey];
                $pembayaran->catatan = $deskripsiTransaksi;
                $pembayaran->catatan_alokasi = $deskripsiTransaksi;
                $pembayaran->created_at = $tanggalKas;
                $pembayaran->updated_at = $tanggalKas;
                $pembayaran->save();
            } else {
                $pembayaran = $existingPay;
                if (!empty($pembayaran->kwitansi)) {
                    $kwitansiNo = $pembayaran->kwitansi;
                } else {
                    $pembayaran->kwitansi = $kwitansiNo;
                }
                $pembayaran->nominal = $inputNominal;
                $pembayaran->jenis_pembayaran = $isPartial ? 'cicilan' : 'lunas';
                $pembayaran->status = 'verified';
                $pembayaran->bulan_alokasi = [$monthKey];
                $pembayaran->catatan = $deskripsiTransaksi;
                $pembayaran->catatan_alokasi = $deskripsiTransaksi;
                $pembayaran->created_at = $tanggalKas;
                $pembayaran->updated_at = $tanggalKas;
                $pembayaran->save();
            }

            $pembayaranId = $pembayaran->id;
            $kwitansiUrl = route('backend.keuangan.verifikasi.kwitansi-drive', $pembayaran->id);

            // 3. Otomatis Catat Transaksi Kas ke Buku Kas (transaksi_keuangans)
            $prodi = $student->education['program_studi'] ?? ($student->prodi ?? null);
            $subjekParts = [$student->name];
            if (!empty($student->nim)) {
                $subjekParts[] = "(NIM: {$student->nim})";
            }
            $subjek = implode(' ', $subjekParts);
            if (!empty($prodi)) {
                $subjek .= " • {$prodi}";
            }

            $adminUser = auth()->user();
            $kodeTransaksi = 'TRX-SPP-' . $pembayaran->id . '-' . $tanggalKas->format('Ymd') . '-' . strtoupper(Str::random(4));

            $transaksi = TransaksiKeuangan::where('pembayaran_id', $pembayaran->id)->first();
            if (!$transaksi) {
                $transaksi = TransaksiKeuangan::where('tagihan_id', $tagihan->id)
                    ->where('user_id', $student->id)
                    ->first();
            }

            if (!$transaksi) {
                $transaksi = new TransaksiKeuangan();
                $transaksi->kode_transaksi = $kodeTransaksi;
                $transaksi->pembayaran_id = $pembayaran->id;
            }

            $transaksi->jenis_transaksi = 'masuk';
            $transaksi->kategori = 'Pembayaran SPP';
            $transaksi->nominal = $inputNominal;
            $transaksi->tanggal_transaksi = $tanggalKas;
            $transaksi->metode_pembayaran = 'Kas / Tunai';
            $transaksi->subjek = $subjek;
            $transaksi->user_id = $student->id;
            $transaksi->tagihan_id = $tagihan->id;
            $transaksi->pembayaran_id = $pembayaran->id;
            $transaksi->admin_id = $adminUser ? $adminUser->id : null;
            $transaksi->admin_nama = $adminUser ? $adminUser->name : 'Admin Keuangan';
            $transaksi->deskripsi = $deskripsiTransaksi;
            $transaksi->bukti = 'historis';
            $transaksi->no_referensi = $kwitansiNo;
            $transaksi->status = 'verified';
            $transaksi->created_at = $tanggalKas;
            $transaksi->updated_at = $tanggalKas;
            $transaksi->save();

        } else {
            // UNCHECK: Membatalkan pencatatan historis dan menghapus mutasi kas terkait
            $historicalPay = Pembayaran::where('user_id', $student->id)
                ->where(function ($q) use ($monthKey) {
                    $q->whereJsonContains('bulan_alokasi', $monthKey)
                      ->orWhere('catatan_alokasi', 'like', "%{$monthKey}%")
                      ->orWhere('catatan', 'like', "%{$monthKey}%");
                })
                ->first();

            if ($historicalPay) {
                $tagihan = $historicalPay->tagihan;
                // Hapus Transaksi Keuangan (Buku Kas)
                TransaksiKeuangan::where('pembayaran_id', $historicalPay->id)->delete();
                if ($tagihan) {
                    TransaksiKeuangan::where('tagihan_id', $tagihan->id)->delete();
                }
                // Hapus record pembayaran
                $historicalPay->delete();

                if ($tagihan) {
                    $tagihan->update([
                        'status' => 'belum lunas',
                        'jenis_tagihan' => 'lunas',
                        'sisa_tagihan' => $tagihan->nominal_total ?: $nominalSpp,
                    ]);
                }
            } else {
                // Fallback jika pembayaran tidak ditemukan via query di atas tapi tagihannya ada
                $studentTagihans = Tagihan::where('user_id', $student->id)->get();
                $tagihan = $studentTagihans->first(function ($t) use ($monthNum, $year, $bNama) {
                    return Tagihan::isMatchingSppMonth($t, $monthNum, $year, $bNama);
                });
                if ($tagihan) {
                    TransaksiKeuangan::where('tagihan_id', $tagihan->id)->delete();
                    $pembayarans = Pembayaran::where('tagihan_id', $tagihan->id)->get();
                    foreach ($pembayarans as $p) {
                        TransaksiKeuangan::where('pembayaran_id', $p->id)->delete();
                        $p->delete();
                    }
                    $tagihan->update([
                        'status' => 'belum lunas',
                        'jenis_tagihan' => 'lunas',
                        'sisa_tagihan' => $tagihan->nominal_total ?: $nominalSpp,
                    ]);
                }
            }
        }

        // Ambil konfigurasi daftar 6 bulan semester berjalan untuk menghitung ulang baris mahasiswa
        $daftarBulan = [];
        if ($tahunAkademik && in_array($tipeSemester, ['ganjil', 'genap'])) {
            $ta = MasterTahunAkademik::with('semesters')->where('tahun', $tahunAkademik)->first();
            $sem = $ta ? $ta->semesters->firstWhere('tipe', $tipeSemester) : null;
            if ($sem) {
                $daftarBulan = $sem->getDaftarBulan();
            }
        }
        if (empty($daftarBulan)) {
            $activeSem = MasterSemester::with('tahunAkademik')->where('is_active', true)->first();
            if ($activeSem) {
                $daftarBulan = $activeSem->getDaftarBulan();
            }
        }
        if (empty($daftarBulan)) {
            $nowYear = (int)date('Y');
            if ($tipeSemester === 'ganjil') {
                $daftarBulan = [
                    ['nama' => 'Juli', 'short' => 'Jul', 'num' => 7, 'year' => $nowYear, 'key' => sprintf('%04d-07', $nowYear)],
                    ['nama' => 'Agustus', 'short' => 'Ags', 'num' => 8, 'year' => $nowYear, 'key' => sprintf('%04d-08', $nowYear)],
                    ['nama' => 'September', 'short' => 'Sep', 'num' => 9, 'year' => $nowYear, 'key' => sprintf('%04d-09', $nowYear)],
                    ['nama' => 'Oktober', 'short' => 'Okt', 'num' => 10, 'year' => $nowYear, 'key' => sprintf('%04d-10', $nowYear)],
                    ['nama' => 'November', 'short' => 'Nov', 'num' => 11, 'year' => $nowYear, 'key' => sprintf('%04d-11', $nowYear)],
                    ['nama' => 'Desember', 'short' => 'Des', 'num' => 12, 'year' => $nowYear, 'key' => sprintf('%04d-12', $nowYear)],
                ];
            } else {
                $daftarBulan = [
                    ['nama' => 'Januari', 'short' => 'Jan', 'num' => 1, 'year' => $nowYear, 'key' => sprintf('%04d-01', $nowYear)],
                    ['nama' => 'Februari', 'short' => 'Feb', 'num' => 2, 'year' => $nowYear, 'key' => sprintf('%04d-02', $nowYear)],
                    ['nama' => 'Maret', 'short' => 'Mar', 'num' => 3, 'year' => $nowYear, 'key' => sprintf('%04d-03', $nowYear)],
                    ['nama' => 'April', 'short' => 'Apr', 'num' => 4, 'year' => $nowYear, 'key' => sprintf('%04d-04', $nowYear)],
                    ['nama' => 'Mei', 'short' => 'Mei', 'num' => 5, 'year' => $nowYear, 'key' => sprintf('%04d-05', $nowYear)],
                    ['nama' => 'Juni', 'short' => 'Jun', 'num' => 6, 'year' => $nowYear, 'key' => sprintf('%04d-06', $nowYear)],
                ];
            }
        }

        // Kalkulasi real-time status kelunasan dan tunggakan mahasiswa ini secara presisi
        $verifiedPayments = Pembayaran::where('user_id', $student->id)
            ->where('status', 'verified')
            ->get();
        $studentTagihans = Tagihan::where('user_id', $student->id)->get();

        $bulanLunasCount = 0;
        $totalSudahDibayar = 0;
        $totalTagihanMhs = 0;
        $totalSisaTunggakan = 0;

        foreach ($daftarBulan as $bInfo) {
            $mKey = $bInfo['key'] ?? sprintf('%04d-%02d', $bInfo['year'], $bInfo['num']);
            $bN = $bInfo['nama'];
            $bNum = $bInfo['num'];
            $bY = $bInfo['year'];

            $matchingTagihan = $studentTagihans->first(function ($t) use ($bN, $bNum, $bY) {
                return Tagihan::isMatchingSppMonth($t, $bNum, $bY, $bN);
            });

            $mPayments = $verifiedPayments->filter(function ($p) use ($mKey, $bNum, $bN, $matchingTagihan) {
                if ($matchingTagihan && $p->tagihan_id == $matchingTagihan->id) {
                    return true;
                }
                $alokasi = $p->bulan_alokasi;
                if (!empty($alokasi) && is_array($alokasi)) {
                    if (in_array($mKey, $alokasi) || in_array($bNum, $alokasi) || in_array((string)$bNum, $alokasi)) {
                        return true;
                    }
                    if (in_array(strtolower($bN), array_map('strtolower', $alokasi))) {
                        return true;
                    }
                }
                return false;
            });

            $bNominalTagihan = $matchingTagihan ? (float)$matchingTagihan->nominal_total : $nominalSpp;
            $bTerbayar = (float)$mPayments->sum('nominal');

            $bLunas = false;
            if ($matchingTagihan && (strtolower($matchingTagihan->status) === 'lunas' || (float)$matchingTagihan->sisa_tagihan <= 0)) {
                $bLunas = true;
                $bTerbayar = max($bTerbayar, $bNominalTagihan);
            } elseif ($bTerbayar >= $bNominalTagihan && $bNominalTagihan > 0) {
                $bLunas = true;
            }

            $bSisa = $bLunas ? 0 : max(0, $bNominalTagihan - $bTerbayar);

            if ($bLunas) {
                $bulanLunasCount++;
            }

            $totalSudahDibayar += $bTerbayar;
            $totalTagihanMhs += $bNominalTagihan;
            $totalSisaTunggakan += $bSisa;
        }

        $totalBulan = count($daftarBulan);
        $bulanMenunggakCount = max(0, $totalBulan - $bulanLunasCount);
        $statusKelunasan = ($bulanMenunggakCount === 0 && $totalSisaTunggakan <= 0)
            ? 'Lunas'
            : ($totalSudahDibayar > 0 ? 'Sebagian' : 'Menunggak');

        return response()->json([
            'success' => true,
            'is_lunas' => ($action === 'check' && !$isPartial),
            'is_sebagian' => ($action === 'check' && $isPartial),
            'nominal_bayar' => ($action === 'check' ? $inputNominal : 0),
            'sisa_bulan' => ($action === 'check' ? $sisaTagihan : $nominalSpp),
            'message' => $action === 'check'
                ? ($isPartial 
                    ? "Pembayaran SPP {$bNama} {$year} untuk {$student->name} sebesar Rp " . number_format($inputNominal, 0, ',', '.') . " berhasil dicatat (Sebagian, Sisa: Rp " . number_format($sisaTagihan, 0, ',', '.') . ")."
                    : "SPP {$bNama} {$year} untuk {$student->name} berhasil ditandai LUNAS (Historis).")
                : "Status SPP {$bNama} {$year} untuk {$student->name} dikembalikan ke status TUNGGAKAN.",
            'kwitansi_no' => $kwitansiNo,
            'kwitansi_url' => $kwitansiUrl,
            'payment_id' => $pembayaranId,
            'row' => [
                'user_id' => $student->id,
                'nominal_spp' => $nominalSpp,
                'total_bulan' => $totalBulan,
                'bulan_lunas' => $bulanLunasCount,
                'bulan_menunggak' => $bulanMenunggakCount,
                'tagihan' => $totalSisaTunggakan,
                'total_dibayar' => $totalSudahDibayar,
                'sisa_tunggakan' => $totalSisaTunggakan,
                'status' => $statusKelunasan,
                'tagihan_formatted' => 'Rp ' . number_format($totalSisaTunggakan, 0, ',', '.'),
                'dibayar_formatted' => 'Rp ' . number_format($totalSudahDibayar, 0, ',', '.'),
                'sisa_formatted' => 'Rp ' . number_format($totalSisaTunggakan, 0, ',', '.'),
            ],
            'cell' => [
                'month_key' => $monthKey,
                'is_lunas' => ($action === 'check' && !$isPartial),
                'is_sebagian' => ($action === 'check' && $isPartial),
                'nominal_bayar' => ($action === 'check' ? $inputNominal : 0),
                'nominal_bayar_formatted' => 'Rp ' . number_format($action === 'check' ? $inputNominal : 0, 0, ',', '.'),
                'sisa_bulan' => ($action === 'check' ? $sisaTagihan : $nominalSpp),
                'sisa_bulan_formatted' => 'Rp ' . number_format($action === 'check' ? $sisaTagihan : $nominalSpp, 0, ',', '.'),
                'kwitansi_url' => $kwitansiUrl,
                'payment_id' => $pembayaranId,
            ]
        ]);
    }

    /**
     * Simpan / Perbarui / Hapus Catatan Khusus Rekap SPP Mahasiswa
     */
    public function saveCatatan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tahun_akademik' => 'required|string',
            'tipe_semester' => 'required|in:ganjil,genap',
            'catatan' => 'nullable|string|max:500',
        ]);

        $userId = (int)$request->input('user_id');
        $tahunAkademik = trim($request->input('tahun_akademik'));
        $tipeSemester = strtolower(trim($request->input('tipe_semester')));
        $catatan = trim($request->input('catatan', ''));

        if ($catatan === '') {
            CatatanRekapSpp::where('user_id', $userId)
                ->where('tahun_akademik', $tahunAkademik)
                ->where('tipe_semester', $tipeSemester)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Catatan khusus mahasiswa berhasil dihapus.',
                'catatan' => '',
                'has_catatan' => false,
            ]);
        }

        $record = CatatanRekapSpp::updateOrCreate(
            [
                'user_id' => $userId,
                'tahun_akademik' => $tahunAkademik,
                'tipe_semester' => $tipeSemester,
            ],
            [
                'catatan' => $catatan,
                'admin_id' => auth()->id(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Catatan khusus mahasiswa berhasil disimpan.',
            'catatan' => $record->catatan,
            'has_catatan' => true,
        ]);
    }

    /**
     * Sinkronisasi Tagihan Tunggakan SPP ke Portal Mahasiswa
     * Memastikan bulan-bulan semester aktif yang belum lunas otomatis terbit sebagai tagihan aktif.
     */
    public function syncTagihanTunggakan(Request $request)
    {
        // 1. Ambil semester target (berdasarkan parameter rekap atau master aktif)
        $activeSemester = null;
        if ($request->filled('tahun_akademik') && $request->filled('tipe_semester')) {
            $ta = MasterTahunAkademik::with('semesters')->where('tahun', $request->tahun_akademik)->first();
            if ($ta) {
                $activeSemester = $ta->semesters->firstWhere('tipe', strtolower($request->tipe_semester));
            }
        }

        if (!$activeSemester) {
            $activeSemester = MasterSemester::with('tahunAkademik')->where('is_active', true)->first();
        }

        if (!$activeSemester) {
            $activeMasterTa = MasterTahunAkademik::with('semesters')->where('is_active', true)->first()
                ?: MasterTahunAkademik::with('semesters')->orderBy('tahun', 'desc')->first();
            if ($activeMasterTa && $activeMasterTa->semesters->isNotEmpty()) {
                $activeSemester = $activeMasterTa->semesters->first();
            }
        }

        if (!$activeSemester) {
            return back()->with('error', 'Tidak ada semester yang dipilih atau semester aktif yang terdaftar.');
        }

        $daftarBulan = $activeSemester->getDaftarBulan();
        $students = User::where('role', 'mahasiswa')->with('tagihans')->get();

        $countCreated = 0;
        $countSkipped = 0;

        foreach ($students as $student) {
            $nominalSpp = (float)($student->nominal_spp ?: 300000);
            $verifiedPayments = Pembayaran::where('user_id', $student->id)->where('status', 'verified')->get();

            foreach ($daftarBulan as $b) {
                $monthKey = $b['key'];
                $bNama = $b['nama'];
                $bNum = $b['num'];
                $bYear = $b['year'];

                $isPaid = $verifiedPayments->first(function ($p) use ($monthKey, $bNum, $bNama) {
                    $alokasi = $p->bulan_alokasi;
                    if (!empty($alokasi) && is_array($alokasi)) {
                        if (in_array($monthKey, $alokasi) || in_array($bNum, $alokasi) || in_array((string)$bNum, $alokasi)) {
                            return true;
                        }
                        if (in_array(strtolower($bNama), array_map('strtolower', $alokasi))) {
                            return true;
                        }
                    }
                    return false;
                }) !== null;

                // Cek apakah tagihan untuk bulan/periode ini sudah ada (anti-double billing)
                $existingTagihan = $student->tagihans->first(function ($t) use ($bNum, $bYear, $bNama) {
                    return Tagihan::isMatchingSppMonth($t, $bNum, $bYear, $bNama);
                });

                if ($isPaid) {
                    if ($existingTagihan && $existingTagihan->status !== 'lunas') {
                        $existingTagihan->update(['status' => 'lunas', 'sisa_tagihan' => 0]);
                    }
                    $countSkipped++;
                } else {
                    if (!$existingTagihan) {
                        Tagihan::create([
                            'user_id' => $student->id,
                            'nama_tagihan' => "SPP " . strtoupper($bNama) . " {$bYear}",
                            'nominal_total' => $nominalSpp,
                            'sisa_tagihan' => $nominalSpp,
                            'status' => 'belum lunas',
                            'jenis_tagihan' => 'lunas',
                            'keterangan' => "Tagihan SPP Semester " . ($activeSemester->nama_semester ?? '') . " - Bulan {$bNama} {$bYear}",
                            'jatuh_tempo' => "{$bYear}-" . sprintf('%02d', $bNum) . "-10",
                        ]);
                        $countCreated++;
                    } else {
                        // Tagihan sudah pernah dibuat di database (dilewati agar tidak duplikat)
                        $countSkipped++;
                    }
                }
            }
        }

        if ($countCreated > 0 && $countSkipped > 0) {
            $msg = "Sinkronisasi berhasil! {$countCreated} tagihan tunggakan baru telah diterbitkan ke portal mahasiswa. (Beberapa tagihan dilewati ({$countSkipped} tagihan) karena sudah pernah digenerate sebelumnya untuk periode yang sama).";
        } elseif ($countCreated > 0) {
            $msg = "Sinkronisasi berhasil! {$countCreated} tagihan tunggakan baru telah diterbitkan ke portal mahasiswa.";
        } else {
            $msg = "Semua tagihan untuk periode ini sudah terbit sebelumnya. Beberapa tagihan dilewati ({$countSkipped} tagihan) karena sudah pernah digenerate sebelumnya untuk periode yang sama.";
        }

        return back()->with('success', $msg);
    }

    /**
     * Siapkan seluruh data rekap matriks 6 bulan semester
     */
    private function prepareRekapData(Request $request): array
    {
        $now = Carbon::now();
        $currentYear = (int)$now->format('Y');
        $currentMonth = (int)$now->format('n');

        // 1. Ambil data Master Tahun Akademik & Master Semester dari Database
        $masterTahunList = MasterTahunAkademik::with('semesters')->orderBy('tahun', 'desc')->get();
        $activeMasterTa = $masterTahunList->firstWhere('is_active', true) ?: $masterTahunList->first();
        $activeMasterSemester = MasterSemester::with('tahunAkademik')->where('is_active', true)->first();

        // Tentukan default Tahun Akademik berdasarkan database aktif
        $defaultTahunAkademik = $activeMasterTa
            ? $activeMasterTa->tahun
            : (($currentMonth >= 7) ? "{$currentYear}/" . ($currentYear + 1) : ($currentYear - 1) . "/{$currentYear}");

        $tahunAkademik = $request->get('tahun_akademik', $defaultTahunAkademik);
        $currentMasterTa = $masterTahunList->firstWhere('tahun', $tahunAkademik) ?: $activeMasterTa;

        $partsTahun = explode('/', $tahunAkademik);
        $startYear = isset($partsTahun[0]) && is_numeric($partsTahun[0]) ? (int)$partsTahun[0] : $currentYear;
        $endYear = isset($partsTahun[1]) && is_numeric($partsTahun[1]) ? (int)$partsTahun[1] : ($startYear + 1);

        // 2. Tipe Semester (Ganjil vs Genap)
        $defaultTipeSemester = ($activeMasterSemester && ($activeMasterSemester->tahun_akademik_id == ($currentMasterTa->id ?? 0)))
            ? $activeMasterSemester->tipe
            : (($currentMonth >= 7) ? 'ganjil' : 'genap');

        $tipeSemester = strtolower($request->get('tipe_semester', $defaultTipeSemester));
        if (!in_array($tipeSemester, ['ganjil', 'genap'])) {
            $tipeSemester = 'ganjil';
        }

        // 3. Konfigurasi 6 Bulan Semester (Menggunakan konfigurasi MasterSemester jika tersedia)
        $matchedSemester = $currentMasterTa ? $currentMasterTa->semesters->firstWhere('tipe', $tipeSemester) : null;
        if ($matchedSemester) {
            $daftarBulan = $matchedSemester->getDaftarBulan();
            $semNama = $matchedSemester->nama_semester;
            $periodeLabel = "Semester {$semNama} TA {$tahunAkademik}";
        } else {
            if ($tipeSemester === 'ganjil') {
                $daftarBulan = [
                    ['nama' => 'Juli', 'short' => 'Jul', 'num' => 7, 'year' => $startYear, 'key' => sprintf('%04d-07', $startYear)],
                    ['nama' => 'Agustus', 'short' => 'Ags', 'num' => 8, 'year' => $startYear, 'key' => sprintf('%04d-08', $startYear)],
                    ['nama' => 'September', 'short' => 'Sep', 'num' => 9, 'year' => $startYear, 'key' => sprintf('%04d-09', $startYear)],
                    ['nama' => 'Oktober', 'short' => 'Okt', 'num' => 10, 'year' => $startYear, 'key' => sprintf('%04d-10', $startYear)],
                    ['nama' => 'November', 'short' => 'Nov', 'num' => 11, 'year' => $startYear, 'key' => sprintf('%04d-11', $startYear)],
                    ['nama' => 'Desember', 'short' => 'Des', 'num' => 12, 'year' => $startYear, 'key' => sprintf('%04d-12', $startYear)],
                ];
                $periodeLabel = "Semester Ganjil TA {$tahunAkademik} (Juli - Desember {$startYear})";
            } else {
                $daftarBulan = [
                    ['nama' => 'Januari', 'short' => 'Jan', 'num' => 1, 'year' => $endYear, 'key' => sprintf('%04d-01', $endYear)],
                    ['nama' => 'Februari', 'short' => 'Feb', 'num' => 2, 'year' => $endYear, 'key' => sprintf('%04d-02', $endYear)],
                    ['nama' => 'Maret', 'short' => 'Mar', 'num' => 3, 'year' => $endYear, 'key' => sprintf('%04d-03', $endYear)],
                    ['nama' => 'April', 'short' => 'Apr', 'num' => 4, 'year' => $endYear, 'key' => sprintf('%04d-04', $endYear)],
                    ['nama' => 'Mei', 'short' => 'Mei', 'num' => 5, 'year' => $endYear, 'key' => sprintf('%04d-05', $endYear)],
                    ['nama' => 'Juni', 'short' => 'Jun', 'num' => 6, 'year' => $endYear, 'key' => sprintf('%04d-06', $endYear)],
                ];
                $periodeLabel = "Semester Genap TA {$tahunAkademik} (Januari - Juni {$endYear})";
            }
        }

        // 4. Query Mahasiswa dengan Filter
        $query = User::where('role', 'mahasiswa')
            ->with(['tagihans.pembayarans']);

        // Filter Status Akademik (Default: AKTIF)
        $statusAkademik = $request->get('status_akademik', 'AKTIF');
        if ($statusAkademik && strtoupper($statusAkademik) !== 'SEMUA') {
            $query->whereRaw("UPPER(COALESCE(status, 'AKTIF')) = ?", [strtoupper($statusAkademik)]);
        }

        // Filter Program Studi
        $selectedProdi = $request->get('prodi');
        if (!empty($selectedProdi) && $selectedProdi !== 'semua') {
            $query->where(function ($q) use ($selectedProdi) {
                $q->where('education->program_studi', $selectedProdi)
                  ->orWhere('prodi', $selectedProdi);
            });
        }

        // Filter Semester Mahasiswa (1-8)
        $selectedSemester = $request->get('semester');
        if (!empty($selectedSemester) && $selectedSemester !== 'semua') {
            $query->where('semester', (int)$selectedSemester);
        }

        // Filter Pencarian (Nama / NIM)
        $search = trim($request->get('search', ''));
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        // Ambil mahasiswa diurutkan berdasarkan NIM / Nama
        $mahasiswaList = $query->orderBy('nim')->orderBy('name')->get();
        $mahasiswaIds = $mahasiswaList->pluck('id')->all();

        // Eager-load pembayaran mahasiswa (baik verified maupun pending)
        $allPembayarans = Pembayaran::whereIn('user_id', $mahasiswaIds)
            ->whereIn('status', ['verified', 'pending'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('user_id');

        // Eager-load catatan khusus mahasiswa untuk tahun akademik & tipe semester ini
        $allCatatan = CatatanRekapSpp::whereIn('user_id', $mahasiswaIds)
            ->where('tahun_akademik', $tahunAkademik)
            ->where('tipe_semester', $tipeSemester)
            ->pluck('catatan', 'user_id');

        // 5. Olah Matriks Rekapitulasi per Mahasiswa
        $rekapItems = [];
        $totalPotensiSemua = 0;
        $totalTerkumpulSemua = 0;
        $totalTunggakanSemua = 0;
        $totalMahasiswaLunas = 0;
        $totalMahasiswaMenunggak = 0;

        foreach ($mahasiswaList as $mhs) {
            $nominalSpp = (float)($mhs->nominal_spp ?? 300000);
            if ($nominalSpp <= 0) {
                $nominalSpp = 300000;
            }

            $mhsPayments = $allPembayarans->get($mhs->id, collect());
            $mhsVerified = $mhsPayments->where('status', 'verified');
            $mhsPending = $mhsPayments->where('status', 'pending');

            $bulanMatriks = [];
            $bulanLunasCount = 0;
            $totalSudahDibayarMhs = 0;
            $metodeTerakhir = 'Belum Ada Pembayaran';

            foreach ($daftarBulan as $bInfo) {
                $bNama = $bInfo['nama'];
                $bNum = $bInfo['num'];
                $bYear = $bInfo['year'];
                $monthKey = $bInfo['key'] ?? sprintf('%04d-%02d', $bYear, $bNum);

                // --- 1. Temukan Tagihan Spesifik Bulan Ini jika ada ---
                $matchingTagihan = $mhs->tagihans->first(function ($t) use ($bNama, $bNum, $bYear) {
                    return Tagihan::isMatchingSppMonth($t, $bNum, $bYear, $bNama);
                });

                // Target nominal SPP bulan ini
                $nominalBulanTarget = $matchingTagihan && (float)$matchingTagihan->nominal_total > 0
                    ? (float)$matchingTagihan->nominal_total
                    : $nominalSpp;

                // --- 2. Kumpulkan Semua Pembayaran Terverifikasi untuk Bulan Ini ---
                $monthVerifiedPayments = collect();
                if ($matchingTagihan) {
                    foreach ($matchingTagihan->pembayarans->where('status', 'verified') as $p) {
                        $monthVerifiedPayments->put($p->id, $p);
                    }
                }
                foreach ($mhsVerified as $p) {
                    $alokasi = $p->bulan_alokasi;
                    if (!empty($alokasi) && is_array($alokasi)) {
                        if (in_array($monthKey, $alokasi) || in_array($bNum, $alokasi) || in_array((string)$bNum, $alokasi) || in_array(strtolower($bNama), array_map('strtolower', $alokasi))) {
                            $monthVerifiedPayments->put($p->id, $p);
                        }
                    }
                }

                $totalTerbayarBulan = (float)$monthVerifiedPayments->sum('nominal');
                $lastVerifiedPay = $monthVerifiedPayments->last();
                if ($lastVerifiedPay) {
                    $metodeTerakhir = $lastVerifiedPay->jenis_pembayaran ?: 'Transfer Bank';
                }

                $isHistoris = false;
                if ($lastVerifiedPay) {
                    $isHistoris = ($lastVerifiedPay->bukti_tf === 'historis' || str_contains($lastVerifiedPay->catatan ?? '', 'Historis'));
                }

                // Cek status Lunas vs Sebagian
                $isLunas = false;
                $isSebagian = false;

                if ($matchingTagihan && (strtolower($matchingTagihan->status) === 'lunas' || (float)$matchingTagihan->sisa_tagihan <= 0)) {
                    $isLunas = true;
                    $totalTerbayarBulan = max($totalTerbayarBulan, $nominalBulanTarget);
                } elseif ($totalTerbayarBulan >= $nominalBulanTarget && $nominalBulanTarget > 0) {
                    $isLunas = true;
                } elseif ($totalTerbayarBulan > 0 || ($matchingTagihan && strtolower($matchingTagihan->status) === 'sebagian')) {
                    $isSebagian = true;
                }

                if ($isLunas) {
                    $bulanLunasCount++;
                    $nominalBayar = max($totalTerbayarBulan, $nominalBulanTarget);
                    $nominalTunggakan = 0;

                    $bulanMatriks[] = [
                        'nama' => $bNama,
                        'num' => $bNum,
                        'year' => $bYear,
                        'key' => $monthKey,
                        'ada_tagihan' => true,
                        'is_lunas' => true,
                        'is_sebagian' => false,
                        'is_pending' => false,
                        'is_historis' => $isHistoris,
                        'is_alokasi_lintas' => ($lastVerifiedPay && empty($matchingTagihan)),
                        'payment_id' => $lastVerifiedPay ? $lastVerifiedPay->id : null,
                        'kwitansi' => $lastVerifiedPay ? $lastVerifiedPay->kwitansi : null,
                        'alokasi_tanggal' => $lastVerifiedPay && $lastVerifiedPay->created_at ? $lastVerifiedPay->created_at->format('d/m/Y') : null,
                        'nominal_tagihan' => $nominalBulanTarget,
                        'nominal_bayar' => $nominalBayar,
                        'nominal_tunggakan' => 0,
                        'tagihan_id' => $matchingTagihan ? $matchingTagihan->id : ($lastVerifiedPay ? $lastVerifiedPay->tagihan_id : null),
                    ];
                    continue;
                }

                if ($isSebagian) {
                    $nominalBayar = $totalTerbayarBulan;
                    $nominalTunggakan = max(0, $nominalBulanTarget - $totalTerbayarBulan);

                    $bulanMatriks[] = [
                        'nama' => $bNama,
                        'num' => $bNum,
                        'year' => $bYear,
                        'key' => $monthKey,
                        'ada_tagihan' => true,
                        'is_lunas' => false,
                        'is_sebagian' => true,
                        'is_pending' => false,
                        'is_historis' => $isHistoris,
                        'is_alokasi_lintas' => ($lastVerifiedPay && empty($matchingTagihan)),
                        'payment_id' => $lastVerifiedPay ? $lastVerifiedPay->id : null,
                        'kwitansi' => $lastVerifiedPay ? $lastVerifiedPay->kwitansi : null,
                        'alokasi_tanggal' => $lastVerifiedPay && $lastVerifiedPay->created_at ? $lastVerifiedPay->created_at->format('d/m/Y') : null,
                        'nominal_tagihan' => $nominalBulanTarget,
                        'nominal_bayar' => $nominalBayar,
                        'nominal_tunggakan' => $nominalTunggakan,
                        'tagihan_id' => $matchingTagihan ? $matchingTagihan->id : ($lastVerifiedPay ? $lastVerifiedPay->tagihan_id : null),
                    ];
                    continue;
                }

                // --- 3. Cek apakah ada pembayaran Menunggu Verifikasi (Pending) ---
                $monthPendingPayments = collect();
                if ($matchingTagihan) {
                    foreach ($matchingTagihan->pembayarans->where('status', 'pending') as $p) {
                        $monthPendingPayments->put($p->id, $p);
                    }
                }
                foreach ($mhsPending as $p) {
                    $alokasi = $p->bulan_alokasi;
                    if (!empty($alokasi) && is_array($alokasi)) {
                        if (in_array($monthKey, $alokasi) || in_array($bNum, $alokasi) || in_array((string)$bNum, $alokasi) || in_array(strtolower($bNama), array_map('strtolower', $alokasi))) {
                            $monthPendingPayments->put($p->id, $p);
                        }
                    }
                }

                if ($monthPendingPayments->isNotEmpty()) {
                    $pendingPay = $monthPendingPayments->first();
                    $bulanMatriks[] = [
                        'nama' => $bNama,
                        'num' => $bNum,
                        'year' => $bYear,
                        'key' => $monthKey,
                        'ada_tagihan' => (bool)$matchingTagihan,
                        'is_lunas' => false,
                        'is_sebagian' => false,
                        'is_pending' => true,
                        'is_historis' => false,
                        'is_alokasi_lintas' => false,
                        'pending_payment_id' => $pendingPay->id,
                        'payment_id' => null,
                        'kwitansi' => null,
                        'nominal_tagihan' => $nominalBulanTarget,
                        'nominal_bayar' => 0,
                        'nominal_tunggakan' => $nominalBulanTarget,
                        'tagihan_id' => $matchingTagihan ? $matchingTagihan->id : null,
                    ];
                    continue;
                }

                // --- 4. DEFAULT: Belum Lunas (Tunggakan Aktif) ---
                $bulanMatriks[] = [
                    'nama' => $bNama,
                    'num' => $bNum,
                    'year' => $bYear,
                    'key' => $monthKey,
                    'ada_tagihan' => (bool)$matchingTagihan,
                    'is_lunas' => false,
                    'is_sebagian' => false,
                    'is_pending' => false,
                    'is_historis' => false,
                    'is_alokasi_lintas' => false,
                    'payment_id' => null,
                    'kwitansi' => null,
                    'nominal_tagihan' => $nominalBulanTarget,
                    'nominal_bayar' => 0,
                    'nominal_tunggakan' => $nominalBulanTarget,
                    'tagihan_id' => $matchingTagihan ? $matchingTagihan->id : null,
                ];
            }

            $bulanMenunggakCount = count(array_filter($bulanMatriks, fn($b) => !$b['is_lunas']));
            $totalSudahDibayarMhs = array_sum(array_column($bulanMatriks, 'nominal_bayar'));
            $sisaTunggakanMhs = array_sum(array_column($bulanMatriks, 'nominal_tunggakan'));
            $totalTagihanSemesterMhs = array_sum(array_column($bulanMatriks, 'nominal_tagihan'));
            $tagihanMenunggak = $sisaTunggakanMhs;

            if ($sisaTunggakanMhs <= 0 && $bulanLunasCount === count($daftarBulan)) {
                $statusKelunasan = 'Lunas';
                $totalMahasiswaLunas++;
            } elseif ($totalSudahDibayarMhs > 0) {
                $statusKelunasan = 'Sebagian';
                $totalMahasiswaMenunggak++;
            } else {
                $statusKelunasan = 'Menunggak';
                $totalMahasiswaMenunggak++;
            }

            $totalPotensiSemua += $totalTagihanSemesterMhs;
            $totalTerkumpulSemua += $totalSudahDibayarMhs;
            $totalTunggakanSemua += $sisaTunggakanMhs;

            $prodiNama = $mhs->education['program_studi'] ?? ($mhs->prodi ?? '-');
            $customCatatan = $allCatatan[$mhs->id] ?? null;
            $keteranganTampil = !empty($customCatatan) ? $customCatatan : $metodeTerakhir;

            $rekapItems[] = [
                'id' => $mhs->id,
                'nim' => $mhs->nim ?: '-',
                'nama' => $mhs->name,
                'prodi' => $prodiNama,
                'semester_mhs' => $mhs->semester ?: 1,
                'status_akademik' => strtoupper($mhs->status ?? 'AKTIF'),
                'nominal_spp' => $nominalSpp,
                'matriks_bulan' => $bulanMatriks,
                'bulan_lunas' => $bulanLunasCount,
                'bulan_menunggak' => $bulanMenunggakCount,
                'tagihan' => $tagihanMenunggak, // Sisa Tunggakan Riil
                'total_tagihan' => $totalTagihanSemesterMhs, // Total Potensi Semester
                'total_dibayar' => $totalSudahDibayarMhs,
                'sisa_tunggakan' => $sisaTunggakanMhs,
                'status' => $statusKelunasan,
                'catatan' => $customCatatan,
                'metode' => $keteranganTampil,
                'metode_asli' => $metodeTerakhir,
            ];
        }

        // 6. Daftar Opsi Filter Dinamis dari Database
        $allStudents = User::where('role', 'mahasiswa')->get();
        $daftarProdi = $allStudents->map(function ($u) {
            return $u->education['program_studi'] ?? ($u->prodi ?? null);
        })->filter()->unique()->values()->all();

        $daftarSemesterMhs = $allStudents->pluck('semester')->filter()->unique()->sort()->values()->all();
        if (empty($daftarSemesterMhs)) {
            $daftarSemesterMhs = [1, 2, 3, 4, 5, 6, 7, 8];
        }

        // Ambil daftar tahun akademik dari master_tahun_akademik jika ada
        $daftarTahunAkademik = $masterTahunList->pluck('tahun')->all();
        if (empty($daftarTahunAkademik)) {
            $daftarTahunAkademik = [
                ($currentYear - 1) . "/{$currentYear}",
                "{$currentYear}/" . ($currentYear + 1),
                ($currentYear + 1) . "/" . ($currentYear + 2),
            ];
        }

        $persentaseLunas = $totalPotensiSemua > 0
            ? round(($totalTerkumpulSemua / $totalPotensiSemua) * 100, 1)
            : 0;

        return [
            'rekapItems' => $rekapItems,
            'daftarBulan' => $daftarBulan,
            'periodeLabel' => $periodeLabel,
            'tipeSemester' => $tipeSemester,
            'tahunAkademik' => $tahunAkademik,
            'selectedProdi' => $selectedProdi,
            'selectedSemester' => $selectedSemester,
            'statusAkademik' => $statusAkademik,
            'search' => $search,
            'daftarProdi' => $daftarProdi,
            'daftarSemesterMhs' => $daftarSemesterMhs,
            'daftarTahunAkademik' => $daftarTahunAkademik,
            'masterTahunList' => $masterTahunList,
            'activeMasterTa' => $activeMasterTa,
            'activeMasterSemester' => $activeMasterSemester,
            'currentMasterTa' => $currentMasterTa,
            'summary' => [
                'total_mahasiswa' => count($rekapItems),
                'total_potensi' => $totalPotensiSemua,
                'total_terkumpul' => $totalTerkumpulSemua,
                'total_tunggakan' => $totalTunggakanSemua,
                'persentase_lunas' => $persentaseLunas,
                'mahasiswa_lunas' => $totalMahasiswaLunas,
                'mahasiswa_menunggak' => $totalMahasiswaMenunggak,
            ],
        ];
    }
}
