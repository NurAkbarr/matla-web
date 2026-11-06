<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendPengumumanBlastJob;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    /**
     * Display a listing of announcements with statistics.
     */
    public function index(Request $request)
    {
        $query = Pengumuman::query()->with('creator')->latest();

        // Filter status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter target_role
        if ($request->filled('target') && $request->target !== 'all') {
            $query->where('target_role', $request->target);
        }

        // Search title
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%");
            });
        }

        $pengumumans = $query->paginate(15)->withQueryString();

        // Metrics for summary cards
        $metrics = [
            'total' => Pengumuman::count(),
            'completed' => Pengumuman::where('status', 'completed')->count(),
            'scheduled' => Pengumuman::where('status', 'scheduled')->count(),
            'draft' => Pengumuman::where('status', 'draft')->count(),
            'total_sent' => (int) Pengumuman::sum('total_berhasil'),
        ];

        return view('backend.admin.pengumuman.index', compact('pengumumans', 'metrics'));
    }

    /**
     * Show form for creating a new announcement.
     */
    public function create()
    {
        $roleCounts = [
            'semua' => Pengumuman::countRecipientsForTarget('semua'),
            'mahasiswa' => Pengumuman::countRecipientsForTarget('mahasiswa'),
            'dosen' => Pengumuman::countRecipientsForTarget('dosen'),
            'admin' => Pengumuman::countRecipientsForTarget('admin'),
        ];

        return view('backend.admin.pengumuman.create', compact('roleCounts'));
    }

    /**
     * Store new announcement and dispatch or schedule it.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'target_role' => 'required|in:semua,mahasiswa,dosen,admin,custom_email',
            'custom_emails' => 'nullable|required_if:target_role,custom_email|string',
            'action_type' => 'required|in:kirim_sekarang,jadwalkan,draft',
            'scheduled_at' => 'nullable|required_if:action_type,jadwalkan|date',
        ], [
            'judul.required' => 'Judul pengumuman wajib diisi.',
            'konten.required' => 'Isi pesan pengumuman wajib diisi.',
            'target_role.required' => 'Pilih target penerima pengumuman.',
            'custom_emails.required_if' => 'Daftar alamat email wajib diisi jika memilih target Email Kustom.',
            'scheduled_at.required_if' => 'Waktu jadwal kirim wajib ditentukan jika memilih Jadwalkan Pengiriman.',
        ]);

        $actionType = $validated['action_type'];
        $status = 'draft';
        $scheduledAt = null;

        if ($actionType === 'kirim_sekarang') {
            $status = 'sending';
        } elseif ($actionType === 'jadwalkan') {
            $status = 'scheduled';
            $scheduledAt = $validated['scheduled_at'];
        }

        $totalRecipients = Pengumuman::countRecipientsForTarget(
            $validated['target_role'],
            $validated['custom_emails'] ?? null
        );

        $pengumuman = Pengumuman::create([
            'judul' => $validated['judul'],
            'konten' => $validated['konten'],
            'target_role' => $validated['target_role'],
            'custom_emails' => $validated['custom_emails'] ?? null,
            'status' => $status,
            'scheduled_at' => $scheduledAt,
            'total_penerima' => $totalRecipients,
            'created_by' => Auth::id(),
        ]);

        if ($actionType === 'kirim_sekarang') {
            SendPengumumanBlastJob::dispatch($pengumuman->id);
            return redirect()
                ->route('backend.admin.pengumuman.show', $pengumuman)
                ->with('success', "Pengumuman berhasil dibuat! Antrean pengiriman email langsung dijalankan untuk {$totalRecipients} penerima.");
        }

        if ($actionType === 'jadwalkan') {
            return redirect()
                ->route('backend.admin.pengumuman.show', $pengumuman)
                ->with('success', "Pengumuman berhasil dijadwalkan untuk dikirim pada {$pengumuman->scheduled_at->format('d M Y, H:i')} WIB.");
        }

        return redirect()
            ->route('backend.admin.pengumuman.index')
            ->with('success', 'Draft pengumuman berhasil disimpan.');
    }

    /**
     * Show announcement details, rendered email preview, and delivery logs.
     */
    public function show(Pengumuman $pengumuman)
    {
        $pengumuman->load('creator');
        return view('backend.admin.pengumuman.show', compact('pengumuman'));
    }

    /**
     * Trigger immediate sending for draft/scheduled/failed announcement.
     */
    public function sendNow(Pengumuman $pengumuman)
    {
        if ($pengumuman->status === 'sending') {
            return back()->with('error', 'Pengumuman saat ini sedang dalam proses pengiriman email.');
        }

        $totalRecipients = $pengumuman->resolveRecipients();
        $pengumuman->update([
            'status' => 'sending',
            'total_penerima' => count($totalRecipients),
        ]);

        SendPengumumanBlastJob::dispatch($pengumuman->id);

        return back()->with('success', 'Perintah pengiriman email berhasil dikirim ke antrean server.');
    }

    /**
     * Delete an announcement.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        if ($pengumuman->status === 'sending') {
            return back()->with('error', 'Tidak dapat menghapus pengumuman yang sedang dalam proses pengiriman.');
        }

        $pengumuman->delete();

        return redirect()
            ->route('backend.admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    /**
     * AJAX endpoint to estimate recipient count and sample recipients.
     */
    public function estimateRecipients(Request $request)
    {
        $targetRole = $request->input('target_role', 'semua');
        $customEmails = $request->input('custom_emails');

        $count = Pengumuman::countRecipientsForTarget($targetRole, $customEmails);

        // Preview sample of recipients
        $dummyPengumuman = new Pengumuman([
            'target_role' => $targetRole,
            'custom_emails' => $customEmails,
        ]);
        $recipients = $dummyPengumuman->resolveRecipients();
        $sample = array_slice($recipients, 0, 5);

        return response()->json([
            'status' => 'success',
            'count' => $count,
            'sample' => $sample,
        ]);
    }
}
