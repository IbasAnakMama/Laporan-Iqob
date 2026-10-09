<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamars';
    protected $primaryKey = 'nomor';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['nomor', 'mabna_id'];

    public function mabna() { return $this->belongsTo(Mabna::class); }
}
