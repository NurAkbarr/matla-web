<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'nim', 'qr_token', 'email', 'password', 'role', 'angkatan', 'semester', 'status', 'avatar', 'nidn', 'phone', 'address', 'bio', 'education', 'expertise', 'social_links', 'tanggal_lahir', 'jenis_kelamin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            if ($user->role === 'mahasiswa' && !$user->qr_token) {
                $user->qr_token = \Illuminate\Support\Str::random(32);
            }
        });

        static::updating(function ($user) {
            if ($user->role === 'mahasiswa' && !$user->qr_token) {
                $user->qr_token = \Illuminate\Support\Str::random(32);
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'education' => 'array',
            'expertise' => 'array',
            'social_links' => 'array',
        ];
    }

    public function getAvatarUrlAttribute()
    {
        $path = $this->avatar;
        if (!$path && $this->role === 'mahasiswa' && $this->profil) {
            $path = $this->profil->foto;
        }

        if ($path) {
            $fullPath = storage_path('app/public/' . $path);
            if (file_exists($fullPath)) {
                return url('/_foto/' . $path);
            }
        }

        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&color=059669&background=ECFDF5&bold=true&size=256";
    }

    public function getFotoProfilAttribute()
    {
        return $this->avatar_url;
    }

    public function getStatusBadgeAttribute()
    {
        $status = strtoupper($this->status ?? 'AKTIF');
        $colors = [
            'AKTIF' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
            'CUTI' => 'bg-amber-50 text-amber-600 border-amber-100',
            'KELUAR' => 'bg-red-50 text-red-600 border-red-100',
            'LULUS' => 'bg-blue-50 text-blue-600 border-blue-100',
        ];

        return $colors[$status] ?? 'bg-gray-50 text-gray-600 border-gray-100';
    }

    public function courseSchedules()
    {
        return $this->belongsToMany(Jadwal::class, 'jadwal_mahasiswa', 'mahasiswa_id', 'jadwal_id')->withTimestamps();
    }

    public function taughtSchedules()
    {
        return $this->hasMany(Jadwal::class, 'dosen_id');
    }

    public function presensiDosens()
    {
        return $this->hasMany(PresensiDosen::class);
    }

    // ===== KTM Relations =====

    public function profil()
    {
        return $this->hasOne(ProfilMahasiswa::class);
    }

    public function skills()
    {
        return $this->hasMany(SkillMahasiswa::class);
    }

    public function portofolio()
    {
        return $this->hasMany(PortofolioMahasiswa::class);
    }

    public function getQrUrlAttribute()
    {
        if (!$this->qr_token && $this->role === 'mahasiswa') {
            $this->qr_token = \Illuminate\Support\Str::random(32);
            $this->save();
        }
        
        if ($this->qr_token) {
            return url('/p/' . $this->qr_token);
        }
        return null;
    }

    public function affiliate()
    {
        return $this->hasOne(Affiliate::class);
    }

    public function enrolledSchedules()
    {
        return $this->belongsToMany(Jadwal::class, 'jadwal_mahasiswa', 'mahasiswa_id', 'jadwal_id')->withTimestamps();
    }

    public function logTontonans()
    {
        return $this->hasMany(LogTontonan::class, 'mahasiswa_id');
    }

    public function jawabanEvaluasis()
    {
        return $this->hasMany(JawabanEvaluasi::class, 'mahasiswa_id');
    }
    public function khs()
    {
        return $this->hasMany(Khs::class, 'mahasiswa_id');
    }
}
