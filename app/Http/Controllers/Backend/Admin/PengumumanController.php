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
            'sender_name' => 'nullable|string|max:100',
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
            'sender_name' => $validated['sender_name'] ?? 'MATLA University',
            'konten' => $validated['konten'],
            'target_role' => $validated['target_role'],
            'custom_emails' => $validated['custom_emails'] ?? null,
            'status' => $status,
            'scheduled_at' => $scheduledAt,
            'total_penerima' => $totalRecipients,
            'created_by' => Auth::id(),
        ]);

        if ($actionType === 'kirim_sekarang') {
            // Jika jumlah penerima wajar (<= 25 email), proses langsung secara instan
            // agar pengguna tidak perlu menjalankan worker manual di terminal.
            if ($totalRecipients <= 25) {
                SendPengumumanBlastJob::dispatchSync($pengumuman->id);
                $pengumuman->refresh();
                return redirect()
                    ->route('backend.admin.pengumuman.show', $pengumuman)
                    ->with('success', "Pengumuman berhasil dibuat dan langsung terkirim ke {$pengumuman->total_berhasil} penerima!");
            }

            // Jika jumlah penerima banyak (> 25), masukkan ke antrean queue & picu background worker
            SendPengumumanBlastJob::dispatch($pengumuman->id);
            self::triggerQueueWorker();

            return redirect()
                ->route('backend.admin.pengumuman.show', $pengumuman)
                ->with('success', "Pengumuman berhasil dibuat! Antrean pengiriman email ({$totalRecipients} penerima) sedang diproses di sistem.");
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
     * Trigger immediate sending for draft/scheduled/failed/sending announcement.
     */
    public function sendNow(Pengumuman $pengumuman)
    {
        $recipients = $pengumuman->resolveRecipients();
        $totalRecipients = count($recipients);

        $pengumuman->update([
            'status' => 'sending',
            'total_penerima' => $totalRecipients,
        ]);

        if ($totalRecipients <= 25) {
            SendPengumumanBlastJob::dispatchSync($pengumuman->id);
            $pengumuman->refresh();
            return back()->with('success', "Pengiriman email berhasil diproses langsung ke {$pengumuman->total_berhasil} penerima.");
        }

        SendPengumumanBlastJob::dispatch($pengumuman->id);
        self::triggerQueueWorker();

        return back()->with('success', "Perintah pengiriman email ({$totalRecipients} penerima) berhasil diproses ke antrean server.");
    }

    /**
     * Trigger background queue worker asynchronously.
     */
    protected static function triggerQueueWorker(): void
    {
        if (function_exists('exec')) {
            $base = base_path();
            $php = PHP_BINARY ?: 'php';
            @exec("cd {$base} && {$php} artisan queue:work --stop-when-empty > /dev/null 2>&1 &");
        }
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

