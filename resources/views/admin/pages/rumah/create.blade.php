@extends('admin.layouts.admin')

@section('title', 'Tambah Properti — Admin RumahKu')
@section('page-title', 'Tambah Properti')

@section('content')
<div class="admin-section">
    <div class="admin-section-header">
        <h3>Form Tambah Properti Baru</h3>
        <a href="{{ route('admin.rumah.index') }}" class="btn btn-secondary" style="padding: 8px 15px; background: #e5e7eb; color: #374151; border-radius: 6px; text-decoration: none; font-weight: 500;">Kembali</a>
    </div>

    @if($errors->any())
        <div style="background-color: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.rumah.store') }}" method="POST" enctype="multipart/form-data" style="max-width: 800px;">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label for="nama" style="display: block; margin-bottom: 8px; font-weight: 500;">Nama Properti</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
            </div>
            <div>
                <label for="tipe" style="display: block; margin-bottom: 8px; font-weight: 500;">Tipe</label>
                <select id="tipe" name="tipe" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                    <option value="">Pilih Tipe</option>
                    <option value="rumah" {{ old('tipe') == 'rumah' ? 'selected' : '' }}>Rumah</option>
                    <option value="apartemen" {{ old('tipe') == 'apartemen' ? 'selected' : '' }}>Apartemen</option>
                    <option value="ruko" {{ old('tipe') == 'ruko' ? 'selected' : '' }}>Ruko</option>
                    <option value="tanah" {{ old('tipe') == 'tanah' ? 'selected' : '' }}>Tanah</option>
                </select>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="lokasi" style="display: block; margin-bottom: 8px; font-weight: 500;">Lokasi Lengkap</label>
            <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="harga" style="display: block; margin-bottom: 8px; font-weight: 500;">Harga (Rp)</label>
            <input type="number" id="harga" name="harga" value="{{ old('harga') }}" required min="0" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
        </div>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 20px;">
            <div>
                <label for="luas_tanah" style="display: block; margin-bottom: 8px; font-weight: 500;">L. Tanah (m²)</label>
                <input type="number" id="luas_tanah" name="luas_tanah" value="{{ old('luas_tanah') }}" required min="0" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
            </div>
            <div>
                <label for="luas_bangunan" style="display: block; margin-bottom: 8px; font-weight: 500;">L. Bangunan (m²)</label>
                <input type="number" id="luas_bangunan" name="luas_bangunan" value="{{ old('luas_bangunan') }}" required min="0" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
            </div>
            <div>
                <label for="kamar_tidur" style="display: block; margin-bottom: 8px; font-weight: 500;">K. Tidur</label>
                <input type="number" id="kamar_tidur" name="kamar_tidur" value="{{ old('kamar_tidur') }}" required min="0" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
            </div>
            <div>
                <label for="kamar_mandi" style="display: block; margin-bottom: 8px; font-weight: 500;">K. Mandi</label>
                <input type="number" id="kamar_mandi" name="kamar_mandi" value="{{ old('kamar_mandi') }}" required min="0" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="deskripsi" style="display: block; margin-bottom: 8px; font-weight: 500;">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="5" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">{{ old('deskripsi') }}</textarea>
        </div>

        <div style="margin-bottom: 30px;">
            <label for="foto" style="display: block; margin-bottom: 8px; font-weight: 500;">Foto Properti</label>
            <input type="file" id="foto" name="foto" accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; background: white;">
        </div>

        <div>
            <button type="submit" style="padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 1rem;">Simpan Properti</button>
        </div>
    </form>
</div>
@endsection
