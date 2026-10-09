<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Motivasi;
use Illuminate\Http\Request;

class MotivasiController extends Controller
{
    public function index()
    {
        return view('admin.motivasi', ['daftar' => Motivasi::orderBy('id', 'desc')->get()]);
    }

    public function store(Request $r)
    {
        Motivasi::create($r->validate(['teks' => 'required|string|max:500', 'sumber' => 'nullable|string|max:120']));
        return back()->with('ok', 'Kata motivasi ditambahkan.');
    }

    public function destroy(Motivasi $motivasi)
    {
        $motivasi->delete();
        return back()->with('ok', 'Kata motivasi dihapus.');
    }
}
