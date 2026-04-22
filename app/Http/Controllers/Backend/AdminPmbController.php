<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PmbRegistration;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminPmbController extends Controller
{
    public function index(Request $request)
    {
        $query = PmbRegistration::query();

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', trim($request->status));
        }

        // Filter by Program Studi
        if ($request->filled('prodi')) {
            $query->where('study_program', $request->prodi);
        }

        $registrations = $query->latest()->paginate(10)->withQueryString();
        $activePrograms = ProgramStudi::where('is_active', 1)->orderBy('urutan')->get();

        return view('backend.admin.pmb.index', compact('registrations', 'activePrograms'));
    }

    public function export(Request $request)
    {
        $query = PmbRegistration::query();

        if ($request->filled('status')) {
            $query->where('status', trim($request->status));
        }

        if ($request->filled('prodi')) {
            $query->where('study_program', $request->prodi);
        }

        $query->orderBy('created_at', 'desc');

        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=Pendaftar_PMB_' . date('Y-m-d_H-i') . '.csv',
            'Expires' => '0',
            'Pragma' => 'public',
        ];

        $callback = function() use ($query) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'No. Registrasi',
                'Nama Lengkap',
                'Email',
                'WhatsApp',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Prodi',
                'Sekolah Asal',
                'Tahun Lulus',
                'Status',
                'Tanggal Daftar'
            ]);

            $query->chunk(100, function($registrations) use ($file) {
                foreach ($registrations as $reg) {
                    fputcsv($file, [
                        $reg->registration_code,
                        $reg->full_name,
                        $reg->email,
                        $reg->whatsapp_number,
                        $reg->birth_place,
                        $reg->birth_date,
                        $reg->study_program,
                        $reg->school_name,
                        $reg->graduation_year,
                        ucfirst($reg->status),
                        $reg->created_at->format('d/m/Y H:i')
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show(PmbRegistration $registration)
    {
        return view('backend.admin.pmb.show', compact('registration'));
    }

    public function updateStatus(Request $request, PmbRegistration $registration)
    {
        $request->validate([
            'status' => 'required|in:pending,verified,rejected,accepted',
            'admin_notes' => 'nullable|string'
        ]);

        $registration->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        return redirect()->back()->with('success', 'Status pendaftar berhasil diperbarui.');
    }

    public function generateStudent(PmbRegistration $registration)
    {
        if ($registration->status !== 'accepted') {
            return redirect()->back()->with('error', 'Akun mahasiswa hanya bisa dibuat untuk pendaftar yang sudah Diterima.');
        }

        // Check if user already exists
        $userExists = User::where('email', $registration->email)->exists();
        if ($userExists) {
            return redirect()->back()->with('error', 'Gagal! Email ini sudah terdaftar sebagai pengguna/mahasiswa di sistem.');
        }

        try {
            DB::beginTransaction();

            // Create User Account
            $user = User::create([
                'name' => $registration->full_name,
                'email' => $registration->email,
                'password' => Hash::make('matla123'), // Default Password
                'role' => 'mahasiswa',
                'status' => 'AKTIF',
                'phone' => $registration->whatsapp_number,
                'address' => $registration->address,
                'angkatan' => date('Y'),
                'semester' => 1,
            ]);

            // Track that this registration has been migrated (optional, but good for UI)
            // For now we just use the email check, but we could add a column if needed.

            DB::commit();

            return redirect()->back()->with('success', 'Akun Mahasiswa berhasil dibuat! Mahasiswa bisa login dengan email mereka dan password default: matla123');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat akun: ' . $e->getMessage());
        }
    }

    public function destroy(PmbRegistration $registration)
    {
        // Only super_admin can delete
        if (auth()->user()->role !== 'super_admin') {
            return redirect()->back()->with('error', 'Akses ditolak! Hanya Super Admin yang diizinkan untuk menghapus data pendaftaran.');
        }

        try {
            $registration->delete(); // This will perform a soft delete

            return redirect()->route('backend.admin.pmb.registrations.index')
                           ->with('success', 'Data pendaftaran ' . $registration->registration_code . ' berhasil dipindahkan ke tempat sampah (Soft Delete).');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        if (auth()->user()->role !== 'super_admin') {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $registrations = PmbRegistration::onlyTrashed()->latest()->paginate(10);
        return view('backend.admin.pmb.trash', compact('registrations'));
    }

    public function restore($id)
    {
        if (auth()->user()->role !== 'super_admin') {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        try {
            $registration = PmbRegistration::withTrashed()->findOrFail($id);
            $registration->restore();

            return redirect()->route('backend.admin.pmb.registrations.trash')
                           ->with('success', 'Data pendaftaran ' . $registration->registration_code . ' berhasil dipulihkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memulihkan data: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        if (auth()->user()->role !== 'super_admin') {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        try {
            $registration = PmbRegistration::withTrashed()->findOrFail($id);
            $registrationCode = $registration->registration_code;
            $registration->forceDelete();

            return redirect()->route('backend.admin.pmb.registrations.trash')
                           ->with('success', 'Data pendaftaran ' . $registrationCode . ' telah dihapus secara permanen dari sistem.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus permanen data: ' . $e->getMessage());
        }
    }
}
