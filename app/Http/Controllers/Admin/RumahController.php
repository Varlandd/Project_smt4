<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rumah;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\RumahImport;
use App\Exports\RumahExport;
use App\Models\Aktivitas;
use Illuminate\Support\Facades\Auth;

class RumahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $rumahs = Rumah::latest()->paginate($perPage)->appends(request()->query());
        return view('admin.pages.rumah', compact('rumahs', 'perPage'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new RumahImport, $request->file('file_excel'));

            Aktivitas::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'aksi' => 'IMPORT',
                'tipe_objek' => 'Rumah',
                'deskripsi' => "Mengimport produk via Excel",
            ]);

            return redirect()->route('admin.rumah.index')->with('success', 'Data properti berhasil diimport dari Excel.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new RumahExport, 'format_import_rumah.xlsx');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.rumah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga' => 'required|integer',
            'luas_tanah' => 'required|integer',
            'luas_bangunan' => 'required|integer',
            'kamar_tidur' => 'required|integer',
            'kamar_mandi' => 'required|integer',
            'tipe' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('rumah_photos', 'public');
            $validated['foto'] = $path;
        }

        $r = \App\Models\Rumah::create($validated);

        Aktivitas::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'aksi' => 'CREATE',
            'tipe_objek' => 'Rumah',
            'deskripsi' => "Menambahkan properti baru: {$r->nama}",
        ]);

        return redirect()->route('admin.rumah.index')->with('success', 'Data properti berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rumah = \App\Models\Rumah::findOrFail($id);
        return view('admin.pages.rumah.edit', compact('rumah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rumah = \App\Models\Rumah::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'luas_tanah' => 'required|integer',
            'luas_bangunan' => 'required|integer',
            'kamar_tidur' => 'required|integer',
            'kamar_mandi' => 'required|integer',
            'tipe' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($rumah->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($rumah->foto);
            }
            $path = $request->file('foto')->store('rumah_photos', 'public');
            $validated['foto'] = $path;
        }

        $rumah->update($validated);

        Aktivitas::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'aksi' => 'UPDATE',
            'tipe_objek' => 'Rumah',
            'deskripsi' => "Mengupdate data properti: {$rumah->nama}",
        ]);

        return redirect()->route('admin.rumah.index')->with('success', 'Data properti berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rumah = \App\Models\Rumah::findOrFail($id);
        
        if ($rumah->foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($rumah->foto);
        }

        $nama = $rumah->nama;
        $rumah->delete();

        Aktivitas::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'aksi' => 'DELETE',
            'tipe_objek' => 'Rumah',
            'deskripsi' => "Menghapus properti: {$nama}",
        ]);

        return redirect()->route('admin.rumah.index')->with('success', 'Data properti berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Pilih minimal satu data untuk dihapus.');
        }

        try {
            $count = count($ids);
            
            // Log activity before deleting to have access to names if needed, or just log the count
            Aktivitas::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'aksi' => 'DELETE_BULK',
                'tipe_objek' => 'Rumah',
                'deskripsi' => "Menghapus {$count} properti sekaligus",
            ]);

            // Perform deletion
            Rumah::whereIn('_id', $ids)->delete();

            return redirect()->route('admin.rumah.index')->with('success', "{$count} data properti berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
