<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\MataKuliah::with('programStudi');

        if ($request->filled('prodi')) {
            $query->where('program_studi_id', $request->prodi);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%');
            });
        }

        $mataKuliahs = $query->latest()->paginate(15);
        $programStudis = \App\Models\ProgramStudi::active()->get();

        return view('backend.admin.mata_kuliah.index', compact('mataKuliahs', 'programStudis'));
    }

    public function exportExcel(Request $request)
    {
        $query = \App\Models\MataKuliah::with('programStudi');

        $activeFilters = [];

        if ($request->filled('prodi')) {
            $query->where('program_studi_id', $request->prodi);
            $prodiObj = \App\Models\ProgramStudi::find($request->prodi);
            if ($prodiObj) {
                $activeFilters[] = "Prodi: " . $prodiObj->nama;
            }
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('kode', 'like', '%' . $search . '%');
            });
            $activeFilters[] = "Pencarian: \"" . $search . "\"";
        }

        $mataKuliahs = $query->orderBy('program_studi_id', 'asc')
            ->orderBy('semester', 'asc')
            ->orderBy('kode', 'asc')
            ->get();

        $filename = "Data_Mata_Kuliah_MATLA_" . date('Y-m-d') . ".xls";

        $headers = [
            "Content-Type"        => "application/vnd.ms-excel; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"{$filename}\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $filterText = !empty($activeFilters) ? implode(' | ', $activeFilters) : 'Semua Data';

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head>';
        $html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $html .= '<!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Data Mata Kuliah</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->';
        $html .= '<style>';
        $html .= 'table { border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; }';
        $html .= 'th.table-header { background-color: #065F46; color: #FFFFFF; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; height: 35px; padding: 6px 10px; }';
        $html .= 'td.table-cell { border: 1px solid #000000; vertical-align: middle; padding: 6px 8px; font-size: 10pt; color: #000000; }';
        $html .= '</style>';
        $html .= '</head>';
        $html .= '<body>';
        $html .= '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; border: 1px solid #000000; font-family: Arial, Helvetica, sans-serif;">';
        
        // Judul Dokumen
        $html .= '<thead>';
        $html .= '<tr style="height: 38px;">';
        $html .= '<th colspan="6" style="border: none; font-size: 14pt; font-weight: bold; text-align: center; vertical-align: middle; background-color: #FFFFFF; color: #065F46;">DAFTAR MATA KULIAH - MATLA UNIVERSITY PORTAL</th>';
        $html .= '</tr>';
        $html .= '<tr style="height: 22px;">';
        $html .= '<th colspan="6" style="border: none; font-size: 9pt; font-weight: normal; color: #4B5563; text-align: center; vertical-align: middle; background-color: #FFFFFF;">Waktu Unduh: ' . date('d/m/Y H:i') . ' WIB &nbsp;|&nbsp; Filter: ' . htmlspecialchars($filterText, ENT_QUOTES, 'UTF-8') . ' &nbsp;|&nbsp; Total: ' . $mataKuliahs->count() . ' Mata Kuliah</th>';
        $html .= '</tr>';
        $html .= '<tr style="height: 10px;">';
        $html .= '<th colspan="6" style="border: none; background-color: #FFFFFF;"></th>';
        $html .= '</tr>';

        // Header Kolom
        $html .= '<tr style="background-color: #065F46; height: 35px;">';
        $html .= '<th class="table-header" style="background-color: #065F46; color: #FFFFFF; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; width: 50px;">NO</th>';
        $html .= '<th class="table-header" style="background-color: #065F46; color: #FFFFFF; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; width: 140px;">KODE MATA KULIAH</th>';
        $html .= '<th class="table-header" style="background-color: #065F46; color: #FFFFFF; font-weight: bold; text-align: left; vertical-align: middle; border: 1px solid #000000; min-width: 250px;">NAMA MATA KULIAH</th>';
        $html .= '<th class="table-header" style="background-color: #065F46; color: #FFFFFF; font-weight: bold; text-align: left; vertical-align: middle; border: 1px solid #000000; min-width: 220px;">PROGRAM STUDI</th>';
        $html .= '<th class="table-header" style="background-color: #065F46; color: #FFFFFF; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; width: 100px;">SEMESTER</th>';
        $html .= '<th class="table-header" style="background-color: #065F46; color: #FFFFFF; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; width: 70px;">SKS</th>';
        $html .= '</tr>';
        $html .= '</thead>';

        // Body Data
        $html .= '<tbody>';
        $no = 1;
        foreach ($mataKuliahs as $mk) {
            $bgColor = ($no % 2 === 0) ? '#F9FAFB' : '#FFFFFF';
            $kode = !empty($mk->kode) ? (string) $mk->kode : '-';
            $nama = !empty($mk->nama) ? (string) $mk->nama : '-';
            $prodi = $mk->programStudi ? $mk->programStudi->nama : '-';
            $semester = $mk->semester ? 'SMT ' . $mk->semester : '-';
            $sks = ($mk->sks !== null && $mk->sks > 0) ? (string) $mk->sks : '-';

            $html .= '<tr style="background-color: ' . $bgColor . '; height: 26px;">';
            $html .= '<td class="table-cell" style="border: 1px solid #000000; text-align: center; background-color: ' . $bgColor . ';">' . $no++ . '</td>';
            $html .= '<td class="table-cell" style="border: 1px solid #000000; text-align: center; font-weight: bold; mso-number-format:\'@\'; background-color: ' . $bgColor . ';">' . htmlspecialchars($kode, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td class="table-cell" style="border: 1px solid #000000; text-align: left; font-weight: bold; background-color: ' . $bgColor . ';">' . htmlspecialchars($nama, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td class="table-cell" style="border: 1px solid #000000; text-align: left; background-color: ' . $bgColor . ';">' . htmlspecialchars($prodi, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td class="table-cell" style="border: 1px solid #000000; text-align: center; background-color: ' . $bgColor . ';">' . htmlspecialchars($semester, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '<td class="table-cell" style="border: 1px solid #000000; text-align: center; background-color: ' . $bgColor . ';">' . htmlspecialchars($sks, ENT_QUOTES, 'UTF-8') . '</td>';
            $html .= '</tr>';
        }

        if ($mataKuliahs->isEmpty()) {
            $html .= '<tr><td colspan="6" style="border: 1px solid #000000; text-align: center; padding: 20px; color: #6B7280; font-style: italic;">Tidak ada data mata kuliah yang sesuai filter.</td></tr>';
        }

        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</body>';
        $html .= '</html>';

        return response($html, 200, $headers);
    }

    public function create()
    {
        $programStudis = \App\Models\ProgramStudi::active()->get();
        return view('backend.admin.mata_kuliah.create', compact('programStudis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'kode' => 'required|unique:mata_kuliahs,kode',
            'nama' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:14',
        ]);
        
        $validated['sks'] = 0; // Default since SKS is removed

        \App\Models\MataKuliah::create($validated);

        return redirect()->route('backend.admin.mata-kuliah.index')->with('success', 'Mata Kuliah berhasil ditambahkan.');
    }

    public function edit(\App\Models\MataKuliah $mataKuliah)
    {
        $programStudis = \App\Models\ProgramStudi::active()->get();
        return view('backend.admin.mata_kuliah.edit', compact('mataKuliah', 'programStudis'));
    }

    public function update(Request $request, \App\Models\MataKuliah $mataKuliah)
    {
        $validated = $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'kode' => 'required|unique:mata_kuliahs,kode,' . $mataKuliah->id,
            'nama' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:14',
        ]);

        $validated['sks'] = 0; // Default since SKS is removed

        $mataKuliah->update($validated);

        return redirect()->route('backend.admin.mata-kuliah.index')->with('success', 'Mata Kuliah berhasil diperbarui.');
    }

    public function destroy(\App\Models\MataKuliah $mataKuliah)
    {
        $mataKuliah->delete();
        return redirect()->route('backend.admin.mata-kuliah.index')->with('success', 'Mata Kuliah berhasil dihapus.');
    }
}
