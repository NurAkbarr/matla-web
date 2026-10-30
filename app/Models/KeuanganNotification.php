<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeuanganNotification extends Model
{
    use HasFactory;

    protected $table = 'keuangan_notifications';

    protected $fillable = [
        'user_id',
        'role',
        'title',
        'message',
        'type',
        'data_id',
        'url',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Pembayaran (jika data_id berisi id pembayaran)
     */
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'data_id');
    }

    /**
     * Scope untuk notifikasi yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope untuk notifikasi yang sudah dibaca
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope untuk role tertentu (default keuangan)
     */
    public function scopeForRole($query, $role = 'keuangan')
    {
        return $query->where(function ($q) use ($role) {
            $q->where('role', $role)
              ->orWhereNull('role');
        });
    }

    /**
     * Scope urutkan dari yang terbaru
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Tandai notifikasi ini sebagai sudah dibaca
     */
    public function markAsRead(): bool
    {
        if ($this->is_read) {
            return true;
        }

        $this->is_read = true;
        $this->read_at = Carbon::now();
        return $this->save();
    }

    /**
     * Helper untuk membuat notifikasi pembayaran SPP baru (Pending)
     */
    public static function sendSppPendingNotification(Pembayaran $pembayaran, ?User $student = null): ?self
    {
        // Hindari duplikasi notifikasi untuk pembayaran yang sama
        $existing = self::where('data_id', $pembayaran->id)
            ->where('type', 'spp_pending')
            ->first();

        if ($existing) {
            return $existing;
        }

        if (!$student) {
            $student = $pembayaran->user ?: User::find($pembayaran->user_id);
        }

        $studentName = $student ? $student->name : 'Mahasiswa';
        $studentNim = ($student && !empty($student->nim)) ? $student->nim : '-';
        $nominalFormatted = number_format((float)$pembayaran->nominal, 0, ',', '.');

        $title = 'Pembayaran SPP Baru';
        $message = "Mahasiswa {$studentName} (NIM: {$studentNim}) telah mengunggah bukti pembayaran SPP baru sebesar Rp {$nominalFormatted} yang memerlukan konfirmasi.";

        try {
            $url = route('backend.keuangan.verifikasi.index');
        } catch (\Throwable $e) {
            $url = '/backend/keuangan/verifikasi';
        }

        return self::create([
            'user_id' => $student ? $student->id : null,
            'role' => 'keuangan',
            'title' => $title,
            'message' => $message,
            'type' => 'spp_pending',
            'data_id' => $pembayaran->id,
            'url' => $url,
            'is_read' => false,
            'read_at' => null,
        ]);
    }
}
