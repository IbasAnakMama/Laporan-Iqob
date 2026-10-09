<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mabna extends Model
{
    protected $table = 'mabna';
    protected $fillable = ['nama'];

    public function kamars() { return $this->hasMany(Kamar::class); }
    public function musrif() { return $this->hasMany(User::class)->where('role', 'musrif'); }

    /** Ringkasan tiap mabna: daftar kamar, jumlah kamar, musrif, dan santri. */
    public static function ringkasan()
    {
        $kamar = Kamar::whereNotNull('mabna_id')->orderBy('nomor')->get()->groupBy('mabna_id');
        $musrif = User::where('role', 'musrif')->whereNotNull('mabna_id')
            ->selectRaw('mabna_id as m, count(*) as c')->groupBy('mabna_id')->pluck('c', 'm');
        $santri = User::where('users.role', 'santri')->join('kamars', 'users.kamar', '=', 'kamars.nomor')
            ->whereNotNull('kamars.mabna_id')->selectRaw('kamars.mabna_id as m, count(*) as c')
            ->groupBy('kamars.mabna_id')->pluck('c', 'm');

        return self::orderBy('nama')->get()->map(function ($m) use ($kamar, $musrif, $santri) {
            $nomor = isset($kamar[$m->id]) ? $kamar[$m->id]->pluck('nomor')->all() : [];
            $m->daftar_kamar = MusrifKamar::ringkas($nomor);
            $m->jml_kamar = count($nomor);
            $m->jml_musrif = $musrif[$m->id] ?? 0;
            $m->jml_santri = $santri[$m->id] ?? 0;
            return $m;
        });
    }
}
