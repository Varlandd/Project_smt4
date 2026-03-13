<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rumah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RumahApiController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'lokasi' => 'required|string|max:255',
            'kamar_tidur' => 'required|integer',
            'kamar_mandi' => 'required|integer',
            'luas_tanah' => 'nullable|integer',
            'luas_bangunan' => 'nullable|integer',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $imageName = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('images'), $imageName);
            $validated['foto'] = 'images/' . $imageName;
        }

        $rumah = Rumah::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Rumah berhasil ditambahkan',
            'data' => $rumah
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $rumah = Rumah::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'sometimes|string|max:255',
            'harga' => 'sometimes|numeric',
            'lokasi' => 'sometimes|string|max:255',
            'kamar_tidur' => 'sometimes|integer',
            'kamar_mandi' => 'sometimes|integer',
            'luas_tanah' => 'nullable|integer',
            'luas_bangunan' => 'nullable|integer',
            'deskripsi' => 'nullable|string',
            'tipe' => 'sometimes|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $imageName = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('images'), $imageName);
            $validated['foto'] = 'images/' . $imageName;
            
            // Note: we could delete the old image here if needed
        }

        $rumah->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Rumah berhasil diperbarui',
            'data' => $rumah
        ]);
    }

    public function destroy($id)
    {
        $rumah = Rumah::findOrFail($id);
        
        // Try to delete image if exists
        // if ($rumah->foto && file_exists(public_path($rumah->foto))) {
        //     unlink(public_path($rumah->foto));
        // }

        $rumah->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rumah berhasil dihapus'
        ]);
    }
}
