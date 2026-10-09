<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persyaratan extends Model
{
    protected $table = 'persyaratan';
    protected $fillable = ['judul', 'file_path', 'file_nama', 'uploaded_by'];
}
