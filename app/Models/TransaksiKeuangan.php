<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TransaksiKeuangan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_keuangans';

    protected $fillable = [
        'kode_transaksi',
        'jenis_transaksi',
        'kategori',
        'nominal',
        'tanggal_transaksi',
        'metode_pembayaran',
        'subjek',
        'user_id',
        'pembayaran_id',
        'tagihan_id',
        'admin_id',
        'admin_nama',
        'deskripsi',
        'bukti',
        'no_referensi',
        'status',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'nominal' => 'decimal:2',
    ];

    /**
     * Relationship with student user (if payment from student)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship with verified student pembayaran
     */
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaran_id');
    }

    /**
     * Relationship with tagihan
     */
    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }

    /**
     * Relationship with admin who created/verified
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Generate unique transaction code
     * Format: TRX-IN-YYYYMMDD-0001 (Masuk) or TRX-OUT-YYYYMMDD-0001 (Keluar)
     */
    public static function generateKode(string $jenis = 'masuk', $date = null): string
    {
        $prefix = (strtolower($jenis) === 'keluar') ? 'TRX-OUT-' : 'TRX-IN-';
        $carbonDate = $date ? Carbon::parse($date) : Carbon::now();
        $datePart = $carbonDate->format('Ymd');

        $basePattern = "{$prefix}{$datePart}-";
        $countToday = self::where('kode_transaksi', 'LIKE', "{$basePattern}%")->count();
        $nextNumber = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);

        $candidate = "{$basePattern}{$nextNumber}";
        while (self::where('kode_transaksi', $candidate)->exists()) {
            $countToday++;
            $nextNumber = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
            $candidate = "{$basePattern}{$nextNumber}";
        }

        return $candidate;
    }

    public function isMasuk(): bool
    {
        return strtolower($this->jenis_transaksi) === 'masuk';
    }

    public function isKeluar(): bool
    {
        return strtolower($this->jenis_transaksi) === 'keluar';
    }

    public function getFormattedNominalAttribute(): string
    {
        $prefix = $this->isKeluar() ? '- Rp ' : '+ Rp ';
        return $prefix . number_format($this->nominal, 0, ',', '.');
    }

    public function getFormattedTanggalAttribute(): string
    {
        if (!$this->tanggal_transaksi) {
            return '-';
        }
        return $this->tanggal_transaksi->translatedFormat('d M Y, H:i:s') . ' WIB';
    }

    public function getBuktiUrlAttribute(): ?string
    {
        if (empty($this->bukti)) {
            return null;
        }

        if (str_starts_with($this->bukti, 'http://') || str_starts_with($this->bukti, 'https://')) {
            return $this->bukti;
        }

        return Storage::disk('public')->url($this->bukti);
    }
}
