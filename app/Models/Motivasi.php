<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motivasi extends Model
{
    protected $table = 'motivasi';
    public $timestamps = false;
    protected $fillable = ['teks', 'sumber'];

    /** Kata motivasi yang sama seharian, berganti acak tiap hari. */
    public static function hariIni(): ?self
    {
        $ids = self::orderBy('id')->pluck('id');
        if ($ids->isEmpty()) {
            return null;
        }
        $index = crc32(now()->format('Y-m-d')) % $ids->count();
        return self::find($ids[$index]);
    }
}
