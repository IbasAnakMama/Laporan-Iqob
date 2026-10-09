<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';
    protected $fillable = ['nama_lengkap', 'nim', 'role', 'kamar', 'mabna_id', 'foto'];
    protected $hidden = ['remember_token'];
    private ?array $kamarCache = null;

    public function mabna() { return $this->belongsTo(Mabna::class, 'mabna_id'); }
    public function kamarRel() { return $this->belongsTo(Kamar::class, 'kamar', 'nomor'); }

    /** Santri: mabna dari kamarnya. Musrif: mabna yang ia pegang. Admin: null. */
    public function getMabnaNamaAttribute(): ?string
    {
        return match ($this->role) {
            'santri' => $this->kamarRel?->mabna?->nama,
            'musrif' => $this->mabna?->nama,
            default => null,
        };
    }

    public function iqobs()
    {
        return $this->hasMany(Iqob::class, 'santri_id');
    }

    /** Daftar kamar yang boleh diakses user ini: admin = semua, musrif = kamar yang dipegang, santri = kamarnya. */
    public function kamarSaya(): array
    {
        return $this->kamarCache ??= match ($this->role) {
            'admin' => range(1, 46),
            'musrif' => MusrifKamar::where('musrif_id', $this->id)->orderBy('kamar')->pluck('kamar')->map(fn ($k) => (int) $k)->all(),
            default => $this->kamar ? [(int) $this->kamar] : [],
        };
    }

    public function kamarRingkas(): string
    {
        return MusrifKamar::ringkas($this->kamarSaya());
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? route('foto', $this->id) . '?v=' . ($this->updated_at?->timestamp ?? 0) : null;
    }

    public function getInisialAttribute(): string
    {
        $kata = preg_split('/\s+/', trim($this->nama_lengkap), -1, PREG_SPLIT_NO_EMPTY);
        return mb_strtoupper(mb_substr($kata[0] ?? '?', 0, 1) . (isset($kata[1]) ? mb_substr($kata[1], 0, 1) : ''));
    }
}
