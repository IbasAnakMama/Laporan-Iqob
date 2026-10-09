<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iqob extends Model
{
    protected $table = 'iqobs';
    protected $guarded = [];
    protected $casts = ['batas_waktu' => 'datetime', 'tanggal_dikumpulkan' => 'datetime'];

    public const TINGKAT = ['ringan' => 'Ringan', 'sedang' => 'Sedang', 'berat' => 'Berat'];
    public const JENIS = [
        'talim_afkar' => "Ta'lim Afkar",
        'talim_quran' => "Ta'lim Qur'an",
        'ubudiyah' => 'Ubudiyah',
        'soskeg' => 'Soskeg',
    ];

    public function santri() { return $this->belongsTo(User::class, 'santri_id'); }
    public function musrif() { return $this->belongsTo(User::class, 'musrif_id'); }

    public function getJenisLabelAttribute(): string { return self::JENIS[$this->jenis] ?? $this->jenis; }
    public function getTingkatLabelAttribute(): string { return self::TINGKAT[$this->tingkat] ?? $this->tingkat; }

    public function getTerlambatAttribute(): bool
    {
        return $this->status === 'belum_dikumpulkan' && now()->gt($this->batas_waktu);
    }

    /** Boleh upload selama belum disetujui dan batas waktu belum lewat. */
    public function getBisaUploadAttribute(): bool
    {
        return $this->status !== 'selesai' && now()->lte($this->batas_waktu);
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->terlambat) return 'Terlambat';
        return ['belum_dikumpulkan' => 'Belum dikumpulkan', 'dikumpulkan' => 'Menunggu verifikasi', 'selesai' => 'Selesai'][$this->status];
    }

    public function getStatusClassAttribute(): string
    {
        if ($this->terlambat) return 'b-red';
        return ['belum_dikumpulkan' => 'b-gold', 'dikumpulkan' => 'b-blue', 'selesai' => 'b-green'][$this->status];
    }
}
