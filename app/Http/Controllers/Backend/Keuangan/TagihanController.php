<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\User;
use App\Models\MasterJenisTagihan;
use App\Exports\TagihanExport;
use App\Mail\TagihanReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function spreadsheet()
    {
        $sheetId = env('GOOGLE_SHEET_ID');
        $credentialsPath = storage_path('app/google-credentials.json');

        if (!$sheetId || !file_exists($credentialsPath)) {
            return back()->with('error', 'Konfigurasi Google Sheets (GOOGLE_SHEET_ID) atau file kredensial belum tersedia.');
        }

        try {
            $client = new \Google_Client();
            $client->setApplicationName('Matla University Tagihan');
            $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
            $client->setAuthConfig($credentialsPath);
            $client->setAccessType('offline');

            $service = new \Google_Service_Sheets($client);

            // Get Data
            $tagihans = Tagihan::with('user')->get()->sortBy(function($t) {
                return $t->user->nim ?? '';
            });

            $values = [
                ['Username (NIM)', 'Nama Lengkap', 'Item Tagihan', 'Nominal', 'Status'] // Header
            ];

            foreach ($tagihans as $t) {
                $values[] = [
                    $t->user->nim ?? '-',
                    $t->user->name ?? 'Unknown',
                    $t->nama_tagihan,
                    $t->nominal_total,
                    strtoupper($t->status)
                ];
            }

            $body = new \Google_Service_Sheets_ValueRange([
                'values' => $values
            ]);
            $params = [
                'valueInputOption' => 'USER_ENTERED'
            ];

            // 1. Clear existing data
            $clearRequest = new \Google_Service_Sheets_ClearValuesRequest();
            $service->spreadsheets_values->clear($sheetId, 'Sheet1!A:E', $clearRequest);

            // 2. Append new data
            $service->spreadsheets_values->update($sheetId, 'Sheet1!A1', $body, $params);

            // 3. Format the Sheet (Bold Header, Colors, Borders)
            $requests = [
                // Header Format (Green Background, White Bold Text)
                new \Google_Service_Sheets_Request([
                    'repeatCell' => [
                        'range' => [
                            'sheetId' => 0, // Assuming first sheet has ID 0
                            'startRowIndex' => 0,
                            'endRowIndex' => 1,
                            'startColumnIndex' => 0,
                            'endColumnIndex' => 5
                        ],
                        'cell' => [
                            'userEnteredFormat' => [
                                'backgroundColor' => [
                                    'red' => 0.0,
                                    'green' => 0.44, // #00703C approx
                                    'blue' => 0.23,
                                ],
                                'textFormat' => [
                                    'foregroundColor' => [
                                        'red' => 1.0,
                                        'green' => 1.0,
                                        'blue' => 1.0,
                                    ],
                                    'bold' => true,
                                ],
                                'horizontalAlignment' => 'CENTER'
                            ]
                        ],
                        'fields' => 'userEnteredFormat(backgroundColor,textFormat,horizontalAlignment)'
                    ]
                ]),
                // Borders for all data
                new \Google_Service_Sheets_Request([
                    'updateBorders' => [
                        'range' => [
                            'sheetId' => 0,
                            'startRowIndex' => 0,
                            'endRowIndex' => count($values),
                            'startColumnIndex' => 0,
                            'endColumnIndex' => 5
                        ],
                        'top' => ['style' => 'SOLID', 'width' => 1, 'color' => ['red' => 0, 'green' => 0, 'blue' => 0]],
                        'bottom' => ['style' => 'SOLID', 'width' => 1, 'color' => ['red' => 0, 'green' => 0, 'blue' => 0]],
                        'left' => ['style' => 'SOLID', 'width' => 1, 'color' => ['red' => 0, 'green' => 0, 'blue' => 0]],
                        'right' => ['style' => 'SOLID', 'width' => 1, 'color' => ['red' => 0, 'green' => 0, 'blue' => 0]],
                        'innerHorizontal' => ['style' => 'SOLID', 'width' => 1, 'color' => ['red' => 0, 'green' => 0, 'blue' => 0]],
                        'innerVertical' => ['style' => 'SOLID', 'width' => 1, 'color' => ['red' => 0, 'green' => 0, 'blue' => 0]],
                    ]
                ])
            ];

            $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
                'requests' => $requests
            ]);
            $service->spreadsheets->batchUpdate($sheetId, $batchUpdateRequest);

            // Redirect to the actual Google Sheet
            return redirect("https://docs.google.com/spreadsheets/d/{$sheetId}/edit");

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyinkronkan data ke Google Sheets: ' . $e->getMessage());
        }
    }

    public function index()
    {
        // For mass billing filters (hanya mahasiswa aktif)
        $mahasiswas = User::where('role', 'mahasiswa')
            ->where(function($q) {
                $q->whereNull('status')->orWhereRaw("LOWER(status) = 'aktif'");
            })
            ->orderBy('name', 'asc')
            ->get();

        $programStudis = $mahasiswas->map(function($mhs) {
            return $mhs->education['program_studi'] ?? null;
        })->filter()->unique()->sort()->values();

        $angkatans = $mahasiswas->pluck('angkatan')->filter()->unique()->sort()->values();

        // Master Jenis Tagihan
        $masterJenisTagihans = MasterJenisTagihan::orderByRaw("CASE WHEN UPPER(nama_tagihan) = 'SPP' THEN 0 ELSE 1 END")
            ->orderBy('nama_tagihan', 'asc')
            ->get();

        // Get recent bills for display
        $recentTagihans = Tagihan::with('user')->latest()->take(10)->get();

        // Get Massal groups
        $massalGroups = Tagihan::selectRaw('nama_tagihan, sum(nominal_total) as total_nominal, count(id) as total_mhs, min(jatuh_tempo) as jatuh_tempo, max(jenis_tagihan) as jenis_tagihan')
            ->groupBy('nama_tagihan')
            ->orderByRaw('MAX(created_at) DESC')
            ->get();

        return view('backend.keuangan.tagihan.index', compact('programStudis', 'angkatans', 'mahasiswas', 'recentTagihans', 'massalGroups', 'masterJenisTagihans'));
    }

    public function storeMass(Request $request)
    {
        $masterJenis = MasterJenisTagihan::where('nama_tagihan', $request->nama_tagihan)->first();
        $isSpp = ($masterJenis && $masterJenis->tipe_nominal === 'otomatis_spp')
            || strtoupper(trim($request->nama_tagihan)) === 'SPP'
            || stripos($request->nama_tagihan, 'spp') !== false;

        $request->validate([
            'nama_tagihan' => 'required|string|max:255',
            'nominal_total' => $isSpp ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'jatuh_tempo' => 'nullable|date',
            'angkatan' => 'nullable|string',
            'program_studi' => 'nullable|string',
            'jenis_tagihan' => 'required|in:lunas,cicilan',
        ], [
            'nama_tagihan.required' => 'Nama tagihan wajib dipilih atau diisi.',
            'nominal_total.required' => 'Nominal tagihan wajib diisi untuk jenis tagihan non-SPP.',
            'nominal_total.numeric' => 'Nominal tagihan harus berupa angka.',
            'nominal_total.min' => 'Nominal tagihan tidak boleh bernilai negatif.',
        ]);

        // HANYA mahasiswa aktif yang diikutsertakan dalam pembuatan tagihan massal
        $query = User::where('role', 'mahasiswa')
            ->where(function($q) {
                $q->whereNull('status')->orWhereRaw("LOWER(status) = 'aktif'");
            });

        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }

        if ($request->filled('program_studi')) {
            $prodi = $request->program_studi;
            $query->where(function($q) use ($prodi) {
                $q->where('education->program_studi', $prodi);
            });
        }

        $mahasiswas = $query->get();
        $count = 0;
        $skipped = 0;
        $emailSentCount = 0;
        $shouldSendEmail = $request->boolean('send_email');
        $emailSentCount = 0;
        $emailLastError = null;
        $isLogDriver = config('mail.default') === 'log';

        foreach ($mahasiswas as $mhs) {
            $exists = Tagihan::where('user_id', $mhs->id)
                             ->where('nama_tagihan', $request->nama_tagihan)
                             ->exists();

            if (!$exists) {
                // Untuk tagihan SPP, otomatis gunakan nominal_spp masing-masing mahasiswa (fallback Rp 300.000)
                if ($isSpp) {
                    $nominal = ($mhs->nominal_spp !== null && (float)$mhs->nominal_spp > 0)
                        ? (float)$mhs->nominal_spp
                        : 300000;
                } else {
                    $nominal = (float)$request->nominal_total;
                }

                $tagihan = Tagihan::create([
                    'user_id' => $mhs->id,
                    'nama_tagihan' => $request->nama_tagihan,
                    'nominal_total' => $nominal,
                    'sisa_tagihan' => $nominal,
                    'status' => 'belum lunas',
                    'jatuh_tempo' => $request->jatuh_tempo,
                    'keterangan' => 'Tagihan Massal',
                    'jenis_tagihan' => $request->jenis_tagihan,
                ]);
                $count++;

                // Kirim notifikasi email reminder secara langsung (synchronous) jika dicentang dan email mahasiswa valid
                if ($shouldSendEmail && !empty($mhs->email) && filter_var($mhs->email, FILTER_VALIDATE_EMAIL)) {
                    try {
                        Mail::to($mhs->email)->send(new TagihanReminderMail($tagihan, $mhs));
                        $emailSentCount++;
                    } catch (\Throwable $e) {
                        $emailLastError = $e->getMessage();
                        Log::warning("Gagal mengirim email reminder tagihan massal ke {$mhs->email}: " . $e->getMessage());
                    }
                }
            } else {
                $skipped++;
            }
        }

        $msg = "Berhasil membuat tagihan massal untuk {$count} mahasiswa.";
        if ($isSpp) {
            $msg .= " (Nominal SPP otomatis mengikuti tarif masing-masing mahasiswa).";
        }
        if ($shouldSendEmail && $count > 0) {
            if ($isLogDriver) {
                $msg .= " [PERHATIAN]: Email hanya dicatat ke file log server (storage/logs/laravel.log) karena MAIL_MAILER di .env masih bernilai 'log'. Email belum terkirim ke Gmail mahasiswa sampai konfigurasi SMTP diaktifkan.";
            } elseif ($emailSentCount > 0) {
                $msg .= " Sebanyak {$emailSentCount} email notifikasi reminder berhasil dikirim.";
                if ($emailLastError) {
                    $msg .= " (Namun ada beberapa email gagal: {$emailLastError}).";
                }
            } else {
                $msg .= " Namun email gagal terkirim" . ($emailLastError ? ": " . $emailLastError : " (periksa koneksi SMTP di file .env).");
            }
        }
        if ($skipped > 0) {
            $msg .= " ({$skipped} dilewati karena tagihan dengan nama ini sudah ada).";
        }

        return back()->with('success', $msg);
    }

    public function storePersonal(Request $request)
    {
        $masterJenis = MasterJenisTagihan::where('nama_tagihan', $request->nama_tagihan)->first();
        $isSpp = ($masterJenis && $masterJenis->tipe_nominal === 'otomatis_spp')
            || strtoupper(trim($request->nama_tagihan)) === 'SPP'
            || stripos($request->nama_tagihan, 'spp') !== false;

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama_tagihan' => 'required|string|max:255',
            'nominal_total' => $isSpp ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'jatuh_tempo' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'jenis_tagihan' => 'required|in:lunas,cicilan',
        ], [
            'user_id.required' => 'Mahasiswa wajib dipilih.',
            'nama_tagihan.required' => 'Nama tagihan wajib dipilih atau diisi.',
            'nominal_total.required' => 'Nominal tagihan wajib diisi untuk jenis tagihan non-SPP.',
        ]);

        $user = User::findOrFail($request->user_id);

        $exists = Tagihan::where('user_id', $request->user_id)
                         ->where('nama_tagihan', $request->nama_tagihan)
                         ->exists();

        if ($exists) {
            return back()->with('error', "Gagal: Mahasiswa ini sudah memiliki tagihan dengan nama '{$request->nama_tagihan}'.");
        }

        if ($isSpp) {
            $nominal = ($user->nominal_spp !== null && (float)$user->nominal_spp > 0)
                ? (float)$user->nominal_spp
                : ((float)$request->nominal_total > 0 ? (float)$request->nominal_total : 300000);
        } else {
            $nominal = (float)$request->nominal_total;
        }

        $tagihan = Tagihan::create([
            'user_id' => $request->user_id,
            'nama_tagihan' => $request->nama_tagihan,
            'nominal_total' => $nominal,
            'sisa_tagihan' => $nominal,
            'status' => 'belum lunas',
            'jatuh_tempo' => $request->jatuh_tempo,
            'keterangan' => $request->keterangan ?? 'Tagihan Personal',
            'jenis_tagihan' => $request->jenis_tagihan,
        ]);

        $msg = 'Berhasil membuat tagihan personal.';
        if ($request->boolean('send_email')) {
            if (empty($user->email) || !filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                $msg .= " (Peringatan: Mahasiswa ini belum memiliki alamat email yang valid, sehingga email reminder tidak dikirim).";
            } else {
                $isLogDriver = config('mail.default') === 'log';
                try {
                    Mail::to($user->email)->send(new TagihanReminderMail($tagihan, $user));
                    if ($isLogDriver) {
                        $msg .= " [PERHATIAN]: Email reminder hanya tersimpan di log server (storage/logs/laravel.log) karena MAIL_MAILER di .env masih 'log'. Atur SMTP di file .env agar terkirim ke Gmail.";
                    } else {
                        $msg .= " Email notifikasi reminder berhasil dikirim ke {$user->email}.";
                    }
                } catch (\Throwable $e) {
                    Log::warning("Gagal mengirim email reminder tagihan personal ke {$user->email}: " . $e->getMessage());
                    $msg .= " (Namun gagal mengirim email reminder ke {$user->email}: " . $e->getMessage() . ").";
                }
            }
        }

        return back()->with('success', $msg);
    }

    public function resendReminder($id)
    {
        $tagihan = Tagihan::with('user')->findOrFail($id);
        $user = $tagihan->user;

        if (!$user || empty($user->email) || !filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            return back()->with('error', "Gagal: Mahasiswa tidak memiliki alamat email yang valid.");
        }

        $isLogDriver = config('mail.default') === 'log';

        try {
            Mail::to($user->email)->send(new TagihanReminderMail($tagihan, $user));
            if ($isLogDriver) {
                return back()->with('warning', "Email reminder tagihan '{$tagihan->nama_tagihan}' berhasil dicatat ke log server (Catatan: Pengiriman nyata ke Gmail mahasiswa memerlukan konfigurasi SMTP di file .env. Saat ini MAIL_MAILER masih bernilai 'log').");
            }
            return back()->with('success', "Email reminder tagihan '{$tagihan->nama_tagihan}' berhasil dikirim ulang ke {$user->email}.");
        } catch (\Throwable $e) {
            Log::warning("Gagal mengirim ulang email reminder tagihan ke {$user->email}: " . $e->getMessage());
            return back()->with('error', "Gagal mengirim email reminder: " . $e->getMessage());
        }
    }

    public function updatePersonal(Request $request, $id)
    {
        $request->validate([
            'nama_tagihan' => 'required|string|max:255',
            'nominal_total' => 'required|numeric|min:0',
            'jatuh_tempo' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'jenis_tagihan' => 'required|in:lunas,cicilan',
        ]);

        $tagihan = Tagihan::findOrFail($id);
        
        // Cek jika tagihan sudah dibayar (sisa_tagihan < nominal_total)
        // Kita mungkin tidak ingin admin mengubah nominal secara drastis,
        // tapi jika admin tetap mau mengubah, kita sesuaikan sisa_tagihan
        $selisih = $request->nominal_total - $tagihan->nominal_total;
        $sisaBaru = $tagihan->sisa_tagihan + $selisih;
        
        // Cegah sisa_tagihan minus
        if ($sisaBaru < 0) $sisaBaru = 0;

        $tagihan->update([
            'nama_tagihan' => $request->nama_tagihan,
            'nominal_total' => $request->nominal_total,
            'sisa_tagihan' => $sisaBaru,
            'jatuh_tempo' => $request->jatuh_tempo,
            'keterangan' => $request->keterangan,
            'jenis_tagihan' => $request->jenis_tagihan,
        ]);

        return back()->with('success', 'Berhasil mengubah tagihan personal.');
    }

    public function updateMassal(Request $request)
    {
        $request->validate([
            'old_nama_tagihan' => 'required|string',
            'nama_tagihan' => 'required|string|max:255',
            'nominal_total' => 'required|numeric|min:0',
            'jatuh_tempo' => 'nullable|date',
            'jenis_tagihan' => 'required|in:lunas,cicilan',
        ]);

        $tagihans = Tagihan::where('nama_tagihan', $request->old_nama_tagihan)->get();
        $updated = 0;

        foreach ($tagihans as $tagihan) {
            $selisih = $request->nominal_total - $tagihan->nominal_total;
            $sisaBaru = $tagihan->sisa_tagihan + $selisih;
            if ($sisaBaru < 0) $sisaBaru = 0;

            $tagihan->update([
                'nama_tagihan' => $request->nama_tagihan,
                'nominal_total' => $request->nominal_total,
                'sisa_tagihan' => $sisaBaru,
                'jatuh_tempo' => $request->jatuh_tempo,
                'jenis_tagihan' => $request->jenis_tagihan,
            ]);
            $updated++;
        }

        return back()->with('success', "Berhasil mengubah {$updated} data tagihan massal.");
    }

    public function destroyMassal(Request $request)
    {
        $request->validate([
            'nama_tagihan' => 'required|string'
        ]);

        // Hapus hanya tagihan yang namanya sesuai (boleh filter belum lunas jika perlu)
        $deleted = Tagihan::where('nama_tagihan', $request->nama_tagihan)->delete();

        return back()->with('success', "Berhasil menghapus {$deleted} data tagihan massal.");
    }

    public function destroy($id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $tagihan->delete();
        
        return back()->with('success', 'Tagihan berhasil dihapus.');
    }

    // =========================================================================
    // MASTER CRUD: JENIS TAGIHAN
    // =========================================================================

    public function storeJenis(Request $request)
    {
        $request->validate([
            'nama_tagihan' => 'required|string|max:100|unique:master_jenis_tagihan,nama_tagihan',
            'tipe_nominal' => 'nullable|in:otomatis_spp,manual',
            'nominal_default' => 'nullable|numeric|min:0',
        ], [
            'nama_tagihan.required' => 'Nama tagihan wajib diisi.',
            'nama_tagihan.unique' => 'Nama tagihan tersebut sudah terdaftar.',
            'nominal_default.numeric' => 'Nominal default harus berupa angka.',
            'nominal_default.min' => 'Nominal default tidak boleh negatif.',
        ]);

        $tipe = $request->input('tipe_nominal', 'manual');
        $nominal = ($tipe === 'otomatis_spp') ? null : $request->nominal_default;

        MasterJenisTagihan::create([
            'nama_tagihan' => trim($request->nama_tagihan),
            'tipe_nominal' => $tipe,
            'nominal_default' => $nominal,
        ]);

        return back()->with('success', 'Jenis tagihan baru berhasil ditambahkan.');
    }

    public function updateJenis(Request $request, $id)
    {
        $jenis = MasterJenisTagihan::findOrFail($id);

        $request->validate([
            'nama_tagihan' => 'required|string|max:100|unique:master_jenis_tagihan,nama_tagihan,' . $id,
            'tipe_nominal' => 'nullable|in:otomatis_spp,manual',
            'nominal_default' => 'nullable|numeric|min:0',
        ], [
            'nama_tagihan.required' => 'Nama tagihan wajib diisi.',
            'nama_tagihan.unique' => 'Nama tagihan tersebut sudah terdaftar.',
            'nominal_default.numeric' => 'Nominal default harus berupa angka.',
            'nominal_default.min' => 'Nominal default tidak boleh negatif.',
        ]);

        $isSpp = strtoupper(trim($jenis->nama_tagihan)) === 'SPP' || $jenis->tipe_nominal === 'otomatis_spp';
        $tipe = $isSpp ? 'otomatis_spp' : $request->input('tipe_nominal', 'manual');
        $nominal = ($tipe === 'otomatis_spp') ? null : $request->nominal_default;

        $jenis->update([
            'nama_tagihan' => trim($request->nama_tagihan),
            'tipe_nominal' => $tipe,
            'nominal_default' => $nominal,
        ]);

        return back()->with('success', 'Jenis tagihan berhasil diperbarui.');
    }

    public function destroyJenis($id)
    {
        $jenis = MasterJenisTagihan::findOrFail($id);

        if (strtoupper(trim($jenis->nama_tagihan)) === 'SPP' || $jenis->tipe_nominal === 'otomatis_spp') {
            return back()->with('error', 'Jenis tagihan SPP adalah komponen utama sistem dan dilindungi sehingga tidak dapat dihapus.');
        }

        $jenis->delete();

        return back()->with('success', "Jenis tagihan '{$jenis->nama_tagihan}' berhasil dihapus.");
    }
}
