<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MusrifKamar extends Model
{
    protected $table = 'musrif_kamar';
    public $timestamps = false;
    protected $fillable = ['musrif_id', 'kamar'];

    /** "40-43, 45" => [40,41,42,43,45]. Null bila format salah / di luar 1-46. */
    public static function parse(string $in): ?array
    {
        $in = preg_replace('/\s*-\s*/', '-', trim($in));
        $out = [];
        foreach (preg_split('/[\s,;]+/', $in, -1, PREG_SPLIT_NO_EMPTY) as $t) {
            if (preg_match('/^(\d+)-(\d+)$/', $t, $m)) {
                if ((int) $m[1] > (int) $m[2]) return null;
                foreach (range((int) $m[1], (int) $m[2]) as $n) $out[] = $n;
            } elseif (ctype_digit($t)) {
                $out[] = (int) $t;
            } else {
                return null;
            }
        }
        foreach ($out as $n) if ($n < 1 || $n > 46) return null;
        return array_values(array_unique($out));
    }

    /** [40,41,42,43,45] => "40-43, 45" */
    public static function ringkas(array $k): string
    {
        $k = array_values(array_unique(array_map('intval', $k)));
        sort($k);
        $hasil = [];
        for ($i = 0; $i < count($k); $i++) {
            $a = $k[$i];
            while ($i + 1 < count($k) && $k[$i + 1] === $k[$i] + 1) $i++;
            $hasil[] = $a === $k[$i] ? (string) $a : $a . '-' . $k[$i];
        }
        return implode(', ', $hasil);
    }
}
