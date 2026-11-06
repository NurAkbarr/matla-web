<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumumans';

    protected $fillable = [
        'judul',
        'konten',
        'target_role',
        'custom_emails',
        'status',
        'scheduled_at',
        'sent_at',
        'total_penerima',
        'total_berhasil',
        'total_gagal',
        'log_pengiriman',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'log_pengiriman' => 'array',
        'total_penerima' => 'integer',
        'total_berhasil' => 'integer',
        'total_gagal' => 'integer',
    ];

    /**
     * User who created this announcement.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Readable label for target role.
     */
    public function getTargetRoleLabelAttribute(): string
    {
        return match ($this->target_role) {
            'semua' => 'Semua Civitas (Semua User)',
            'mahasiswa' => 'Mahasiswa',
            'dosen' => 'Dosen',
            'admin' => 'Admin & Staff',
            'custom_email' => 'Email Kustom (Spesifik)',
            default => ucfirst($this->target_role),
        };
    }

    /**
     * Greeting label in Indonesian based on target role.
     */
    public function getGreetingLabelAttribute(): string
    {
        return match ($this->target_role) {
            'mahasiswa' => 'Mahasiswa',
            'dosen' => 'Dosen',
            'admin' => 'Admin & Staff',
            'custom_email' => 'Penerima Email',
            default => 'Civitas Akademika MATLA',
        };
    }

    /**
     * Status label in Indonesian.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'scheduled' => 'Dijadwalkan',
            'sending' => 'Sedang Mengirim...',
            'completed' => 'Terkirim Selesai',
            'failed' => 'Gagal Terkirim',
            default => ucfirst($this->status),
        };
    }

    /**
     * Parse and resolve recipients list with deduplication.
     * Returns array of ['email' => string, 'name' => string]
     */
    public function resolveRecipients(): array
    {
        $recipients = [];

        if ($this->target_role === 'custom_email') {
            $rawEmails = preg_split('/[\r\n,;]+/', (string) $this->custom_emails);
            $seen = [];
            foreach ($rawEmails as $email) {
                $trimmed = strtolower(trim($email));
                if ($trimmed !== '' && filter_var($trimmed, FILTER_VALIDATE_EMAIL) && !isset($seen[$trimmed])) {
                    $seen[$trimmed] = true;
                    $recipients[] = [
                        'email' => $trimmed,
                        'name' => explode('@', $trimmed)[0],
                    ];
                }
            }
            return $recipients;
        }

        $query = User::query()->whereNotNull('email')->where('email', '!=', '');

        if ($this->target_role === 'mahasiswa') {
            $query->where('role', 'mahasiswa');
        } elseif ($this->target_role === 'dosen') {
            $query->where('role', 'dosen');
        } elseif ($this->target_role === 'admin') {
            $query->whereIn('role', ['admin', 'super_admin']);
        }
        // If 'semua', query all users with non-empty emails

        $users = $query->get(['id', 'name', 'email']);
        $seen = [];

        foreach ($users as $user) {
            $email = strtolower(trim($user->email));
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) && !isset($seen[$email])) {
                $seen[$email] = true;
                $recipients[] = [
                    'email' => $email,
                    'name' => $user->name ?? explode('@', $email)[0],
                ];
            }
        }

        return $recipients;
    }

    /**
     * Quick static calculation of recipient counts.
     */
    public static function countRecipientsForTarget(string $targetRole, ?string $customEmails = null): int
    {
        if ($targetRole === 'custom_email') {
            if (!$customEmails) return 0;
            $raw = preg_split('/[\r\n,;]+/', $customEmails);
            $valid = [];
            foreach ($raw as $e) {
                $trimmed = strtolower(trim($e));
                if ($trimmed !== '' && filter_var($trimmed, FILTER_VALIDATE_EMAIL)) {
                    $valid[$trimmed] = true;
                }
            }
            return count($valid);
        }

        $query = User::query()->whereNotNull('email')->where('email', '!=', '');
        if ($targetRole === 'mahasiswa') {
            $query->where('role', 'mahasiswa');
        } elseif ($targetRole === 'dosen') {
            $query->where('role', 'dosen');
        } elseif ($targetRole === 'admin') {
            $query->whereIn('role', ['admin', 'super_admin']);
        }

        return $query->distinct('email')->count('email');
    }
}

