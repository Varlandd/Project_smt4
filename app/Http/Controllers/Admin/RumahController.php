<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rumah;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\RumahImport;
use App\Exports\RumahExport;

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

        \App\Models\Rumah::create($validated);

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

        $rumah->delete();

        return redirect()->route('admin.rumah.index')->with('success', 'Data properti berhasil dihapus.');
    }
}
