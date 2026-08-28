<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\User;
use App\Exports\TagihanExport;
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
        // For mass billing filters
        $mahasiswas = User::where('role', 'mahasiswa')->orderBy('name', 'asc')->get();

        $programStudis = $mahasiswas->map(function($mhs) {
            return $mhs->education['program_studi'] ?? null;
        })->filter()->unique()->sort()->values();

        $angkatans = $mahasiswas->pluck('angkatan')->filter()->unique()->sort()->values();

        // Get recent bills for display
        $recentTagihans = Tagihan::with('user')->latest()->take(10)->get();

        // Get Massal groups
        $massalGroups = Tagihan::selectRaw('nama_tagihan, sum(nominal_total) as total_nominal, count(id) as total_mhs, min(jatuh_tempo) as jatuh_tempo, max(jenis_tagihan) as jenis_tagihan')
            ->groupBy('nama_tagihan')
            ->orderByRaw('MAX(created_at) DESC')
            ->get();

        return view('backend.keuangan.tagihan.index', compact('programStudis', 'angkatans', 'mahasiswas', 'recentTagihans', 'massalGroups'));
    }

    public function storeMass(Request $request)
    {
        $request->validate([
            'nama_tagihan' => 'required|string|max:255',
            'nominal_total' => 'required|numeric|min:0',
            'jatuh_tempo' => 'nullable|date',
            'angkatan' => 'nullable|string',
            'program_studi' => 'nullable|string',
            'jenis_tagihan' => 'required|in:lunas,cicilan',
        ]);

        $query = User::where('role', 'mahasiswa');

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

        foreach ($mahasiswas as $mhs) {
            $exists = Tagihan::where('user_id', $mhs->id)
                             ->where('nama_tagihan', $request->nama_tagihan)
                             ->exists();

            if (!$exists) {
                Tagihan::create([
                    'user_id' => $mhs->id,
                    'nama_tagihan' => $request->nama_tagihan,
                    'nominal_total' => $request->nominal_total,
                    'sisa_tagihan' => $request->nominal_total,
                    'status' => 'belum lunas',
                    'jatuh_tempo' => $request->jatuh_tempo,
                    'keterangan' => 'Tagihan Massal',
                    'jenis_tagihan' => $request->jenis_tagihan,
                ]);
                $count++;
            } else {
                $skipped++;
            }
        }

        $msg = "Berhasil membuat tagihan massal untuk {$count} mahasiswa.";
        if ($skipped > 0) {
            $msg .= " ({$skipped} dilewati karena tagihan dengan nama ini sudah ada).";
        }

        return back()->with('success', $msg);
    }

    public function storePersonal(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama_tagihan' => 'required|string|max:255',
            'nominal_total' => 'required|numeric|min:0',
            'jatuh_tempo' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'jenis_tagihan' => 'required|in:lunas,cicilan',
        ]);

        $exists = Tagihan::where('user_id', $request->user_id)
                         ->where('nama_tagihan', $request->nama_tagihan)
                         ->exists();

        if ($exists) {
            return back()->with('error', "Gagal: Mahasiswa ini sudah memiliki tagihan dengan nama '{$request->nama_tagihan}'.");
        }

        Tagihan::create([
            'user_id' => $request->user_id,
            'nama_tagihan' => $request->nama_tagihan,
            'nominal_total' => $request->nominal_total,
            'sisa_tagihan' => $request->nominal_total,
            'status' => 'belum lunas',
            'jatuh_tempo' => $request->jatuh_tempo,
            'keterangan' => $request->keterangan ?? 'Tagihan Personal',
            'jenis_tagihan' => $request->jenis_tagihan,
        ]);

        return back()->with('success', 'Berhasil membuat tagihan personal.');
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
}
