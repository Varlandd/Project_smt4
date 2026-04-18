<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesan;
use Illuminate\Http\Request;

class PesanController extends Controller
{
    public function index()
    {
        $pesan = Pesan::latest()->get();
        return view('admin.pages.pesan.index', compact('pesan'));
    }

    public function show($id)
    {
        $p = Pesan::findOrFail($id);
        $p->update(['status' => 'read']);
        return view('admin.pages.pesan.show', compact('p'));
    }

    public function destroy($id)
    {
        Pesan::findOrFail($id)->delete();
        return redirect()->route('admin.pesan.index')->with('success', 'Pesan berhasil dihapus');
    }
}
