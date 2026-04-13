<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'angkatan', 'semester', 'status', 'avatar', 'nidn', 'phone', 'address', 'bio', 'education', 'expertise', 'social_links'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
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
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=059669&background=ECFDF5&bold=true';
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
}
