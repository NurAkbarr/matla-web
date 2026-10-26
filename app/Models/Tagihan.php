<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $fillable = [
        'user_id',
        'nama_tagihan',
        'nominal_total',
        'sisa_tagihan',
        'status',
        'jatuh_tempo',
        'keterangan',
        'jenis_tagihan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function transaksis()
    {
        return $this->hasMany(TransaksiKeuangan::class);
    }

    /**
     * Peta nomor bulan dan alias nama (Indonesia & Inggris)
     */
    public static function getMonthMap(): array
    {
        return [
            1 => ['januari', 'january', 'jan'],
            2 => ['februari', 'february', 'feb'],
            3 => ['maret', 'march', 'mar'],
            4 => ['april', 'apr'],
            5 => ['mei', 'may'],
            6 => ['juni', 'june', 'jun'],
            7 => ['juli', 'july', 'jul'],
            8 => ['agustus', 'august', 'ags', 'aug'],
            9 => ['september', 'sept', 'sep'],
            10 => ['oktober', 'october', 'okt', 'oct'],
            11 => ['november', 'nov'],
            12 => ['desember', 'december', 'des', 'dec'],
        ];
    }

    /**
     * Ekstraksi nomor bulan, tahun, dan penanda SPP dari nama tagihan atau jatuh tempo
     */
    public static function extractMonthAndYear(?string $namaTagihan, $jatuhTempo = null): array
    {
        $namaLower = strtolower(trim($namaTagihan ?? ''));
        $isSpp = str_contains($namaLower, 'spp');
        
        $detectedMonth = null;
        $detectedYear = null;

        // 1. Deteksi tahun kalender (4 digit 20xx)
        if (preg_match('/\b(20\d{2})\b/', $namaLower, $mYear)) {
            $detectedYear = (int)$mYear[1];
        }

        // 2. Deteksi bulan dari nama tagihan
        $monthMap = self::getMonthMap();
        foreach ($monthMap as $mNum => $aliases) {
            foreach ($aliases as $alias) {
                if (preg_match('/\b' . preg_quote($alias, '/') . '\b/i', $namaLower)) {
                    $detectedMonth = $mNum;
                    break 2;
                }
            }
        }

        // 3. Fallback jika bulan/tahun belum lengkap: gunakan jatuh_tempo
        if ($jatuhTempo) {
            try {
                $jt = Carbon::parse($jatuhTempo);
                if ($detectedMonth === null) {
                    $detectedMonth = (int)$jt->month;
                }
                if ($detectedYear === null) {
                    $detectedYear = (int)$jt->year;
                }
            } catch (\Throwable $e) {}
        }

        return [
            'month' => $detectedMonth,
            'year' => $detectedYear,
            'is_spp' => $isSpp,
        ];
    }

    /**
     * Cek apakah suatu tagihan cocok dengan bulan dan tahun SPP tertentu
     */
    public static function isMatchingSppMonth($tagihan, int $targetMonth, int $targetYear, ?string $targetMonthName = null): bool
    {
        if (!$tagihan) {
            return false;
        }

        $namaLower = strtolower(trim($tagihan->nama_tagihan ?? ''));
        $ketLower = strtolower(trim($tagihan->keterangan ?? ''));

        // Tagihan harus relevan dengan SPP
        $isSpp = str_contains($namaLower, 'spp') 
              || str_contains($ketLower, 'spp')
              || ($tagihan->jenis_tagihan === 'lunas' && empty($tagihan->jenis_tagihan_id));

        $extracted = self::extractMonthAndYear($tagihan->nama_tagihan, $tagihan->jatuh_tempo);

        // Jika bulan belum terdeteksi dari nama/jatuh tempo, periksa keterangan
        if ($extracted['month'] === null && $ketLower !== '') {
            $fromKet = self::extractMonthAndYear($tagihan->keterangan, $tagihan->jatuh_tempo);
            if ($fromKet['month'] !== null) {
                $extracted['month'] = $fromKet['month'];
            }
            if ($extracted['year'] === null && $fromKet['year'] !== null) {
                $extracted['year'] = $fromKet['year'];
            }
        }

        // Jika masih belum terdeteksi, cek created_at untuk SPP
        if ($extracted['month'] === null && $tagihan->created_at && str_contains($namaLower, 'spp')) {
            $extracted['month'] = (int)$tagihan->created_at->month;
            if ($extracted['year'] === null) {
                $extracted['year'] = (int)$tagihan->created_at->year;
            }
        }

        if ($extracted['month'] === $targetMonth) {
            // Jika tahun tagihan diketahui, harus cocok dengan targetYear
            if ($extracted['year'] !== null) {
                return $extracted['year'] === $targetYear;
            }
            // Jika tahun tidak tertera, bandingkan dengan created_at jika ada
            if ($tagihan->created_at) {
                return (int)$tagihan->created_at->year === $targetYear;
            }
            return true;
        }

        // Fallback pencocokan nama bulan eksplisit jika targetMonthName disediakan
        if ($targetMonthName) {
            $mNameLower = strtolower($targetMonthName);
            $mShort = strtolower(substr($targetMonthName, 0, 3));
            if (str_contains($namaLower, $mNameLower) || (strlen($mShort) >= 3 && str_contains($namaLower, $mShort))) {
                if ($extracted['year'] !== null) {
                    return $extracted['year'] === $targetYear;
                }
                if ($tagihan->created_at) {
                    return (int)$tagihan->created_at->year === $targetYear;
                }
                return true;
            }
        }

        return false;
    }

    /**
     * Cek apakah mahasiswa sudah memiliki pembayaran terverifikasi untuk bulan tersebut
     */
    public static function hasVerifiedPaymentForMonth($userId, int $targetMonth, ?int $targetYear = null): bool
    {
        $year = $targetYear ?: (int)date('Y');
        $monthKey = sprintf('%04d-%02d', $year, $targetMonth);
        $monthMap = self::getMonthMap();
        $aliases = $monthMap[$targetMonth] ?? [];

        return Pembayaran::where('user_id', $userId)
            ->where('status', 'verified')
            ->where(function ($q) use ($monthKey, $targetMonth, $aliases) {
                $q->whereJsonContains('bulan_alokasi', $monthKey)
                  ->orWhereJsonContains('bulan_alokasi', $targetMonth)
                  ->orWhereJsonContains('bulan_alokasi', (string)$targetMonth);
                foreach ($aliases as $alias) {
                    $q->orWhereJsonContains('bulan_alokasi', $alias)
                      ->orWhere('catatan_alokasi', 'like', "%{$alias}%");
                }
            })
            ->exists();
    }

    /**
     * Cari apakah mahasiswa sudah memiliki tagihan aktif / terdaftar dengan jenis dan periode yang sama (Anti-Double Billing)
     */
    public static function findDuplicate($userId, $namaTagihan, $jatuhTempo = null, ?int $excludeTagihanId = null): ?self
    {
        $query = self::where('user_id', $userId);
        if ($excludeTagihanId) {
            $query->where('id', '!=', $excludeTagihanId);
        }
        $existingBills = $query->get();
        if ($existingBills->isEmpty()) {
            return null;
        }

        $namaTrim = trim($namaTagihan ?? '');
        $namaLower = strtolower($namaTrim);
        $cleanNew = preg_replace('/[^a-z0-9]/', '', $namaLower);

        $newInfo = self::extractMonthAndYear($namaTrim, $jatuhTempo);

        foreach ($existingBills as $exist) {
            $existNama = trim($exist->nama_tagihan ?? '');
            $existLower = strtolower($existNama);

            // 1. Exact match (case-insensitive & trimmed)
            if (strcasecmp($existNama, $namaTrim) === 0) {
                return $exist;
            }

            // 2. Normalized alphanumeric match
            $cleanExist = preg_replace('/[^a-z0-9]/', '', $existLower);
            if ($cleanNew !== '' && $cleanNew === $cleanExist) {
                return $exist;
            }

            // 3. Periode SPP / Bulanan yang sama
            if ($newInfo['is_spp'] || str_contains($existLower, 'spp')) {
                $existInfo = self::extractMonthAndYear($existNama, $exist->jatuh_tempo);

                if ($existInfo['month'] === null && !empty($exist->keterangan)) {
                    $fromKet = self::extractMonthAndYear($exist->keterangan, $exist->jatuh_tempo);
                    if ($fromKet['month'] !== null) {
                        $existInfo['month'] = $fromKet['month'];
                    }
                    if ($existInfo['year'] === null && $fromKet['year'] !== null) {
                        $existInfo['year'] = $fromKet['year'];
                    }
                }

                if ($newInfo['month'] !== null && $existInfo['month'] !== null) {
                    if ($newInfo['month'] === $existInfo['month']) {
                        $yearNew = $newInfo['year'];
                        $yearExist = $existInfo['year'];

                        if ($yearNew !== null && $yearExist !== null) {
                            if ($yearNew === $yearExist) {
                                return $exist;
                            }
                        } else {
                            $cYearExist = $exist->created_at ? (int)$exist->created_at->year : null;
                            if ($yearNew && $cYearExist && $yearNew !== $cYearExist) {
                                continue;
                            }
                            return $exist;
                        }
                    }
                }
            }
        }

        return null;
    }

    protected static function booted()
    {
        static::deleting(function ($tagihan) {
            $pembayaranIds = \App\Models\Pembayaran::where('tagihan_id', $tagihan->id)->pluck('id')->toArray();
            \App\Models\TransaksiKeuangan::where('tagihan_id', $tagihan->id)
                ->orWhereIn('pembayaran_id', $pembayaranIds)
                ->delete();
            \App\Models\Pembayaran::where('tagihan_id', $tagihan->id)->delete();
        });
    }
}
