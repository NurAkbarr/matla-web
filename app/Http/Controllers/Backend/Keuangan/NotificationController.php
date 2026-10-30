<?php

namespace App\Http\Controllers\Backend\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\KeuanganNotification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Ambil daftar notifikasi terbaru & jumlah belum dibaca (JSON)
     */
    public function index(Request $request): JsonResponse
    {
        Carbon::setLocale('id');

        $notifications = KeuanganNotification::forRole('keuangan')
            ->latestFirst()
            ->limit(20)
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'title' => $notif->title,
                    'message' => $notif->message,
                    'type' => $notif->type,
                    'data_id' => $notif->data_id,
                    'url' => $notif->url ?: route('backend.keuangan.verifikasi.index'),
                    'is_read' => (bool)$notif->is_read,
                    'created_at' => $notif->created_at ? $notif->created_at->toISOString() : null,
                    'time_ago' => $notif->created_at ? $notif->created_at->diffForHumans() : '',
                    'formatted_date' => $notif->created_at ? $notif->created_at->translatedFormat('d M Y, H:i') . ' WIB' : '',
                ];
            });

        $unreadCount = KeuanganNotification::forRole('keuangan')
            ->unread()
            ->count();

        $latestId = $notifications->first() ? $notifications->first()['id'] : 0;

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
            'latest_id' => $latestId,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Ambil jumlah notifikasi belum dibaca secara ringan (untuk fast polling)
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $unreadCount = KeuanganNotification::forRole('keuangan')
            ->unread()
            ->count();

        $latestNotif = KeuanganNotification::forRole('keuangan')
            ->latestFirst()
            ->first();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
            'latest_id' => $latestNotif ? $latestNotif->id : 0,
        ]);
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca
     */
    public function markAsRead(Request $request, $id)
    {
        $notif = KeuanganNotification::find($id);

        if ($notif) {
            $notif->markAsRead();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil ditandai telah dibaca.',
                'unread_count' => KeuanganNotification::forRole('keuangan')->unread()->count(),
            ]);
        }

        $targetUrl = ($notif && !empty($notif->url)) ? $notif->url : route('backend.keuangan.verifikasi.index');
        return redirect($targetUrl);
    }

    /**
     * Tandai semua notifikasi keuangan sebagai sudah dibaca
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        KeuanganNotification::forRole('keuangan')
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => Carbon::now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi berhasil ditandai telah dibaca.',
            'unread_count' => 0,
        ]);
    }
}
