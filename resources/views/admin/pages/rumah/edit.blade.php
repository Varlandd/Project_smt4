@extends('admin.layouts.admin')

@section('title', 'Edit Properti — Admin RumahKu')
@section('page-title', 'Edit Properti')

@section('content')
<style>
    /* Default (Light) Mode */
    .form-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 30px;
        width: 100%;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: background 0.3s, border-color 0.3s;
    }
    .form-card .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        padding-bottom: 18px;
        border-bottom: 1px solid #e2e8f0;
        transition: border-color 0.3s;
    }
    .form-card .form-header h3 {
        color: #0f172a;
        font-size: 1.15rem;
        margin: 0;
        font-weight: 700;
        transition: color 0.3s;
    }
    .form-card label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        font-size: 0.875rem;
        color: #334155;
        transition: color 0.3s;
    }
    .form-card input[type="text"],
    .form-card input[type="number"],
    .form-card input[type="file"],
    .form-card select,
    .form-card textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #f8fafc;
        color: #0f172a;
        font-size: 0.9rem;
        font-family: inherit;
        transition: background 0.3s, border-color 0.3s, color 0.3s, box-shadow 0.2s;
    }
    .form-card input:focus,
    .form-card select:focus,
    .form-card textarea:focus {
        outline: none;
        border-color: #6366f1;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(99,102,241,.15);
    }
    .form-card input::placeholder,
    .form-card textarea::placeholder {
        color: #94a3b8;
    }
    .form-card select option {
        background: #ffffff;
        color: #0f172a;
    }
    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    .form-grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }
    .form-group { margin-bottom: 20px; }
    .btn-back {
        padding: 8px 18px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: background .2s, color .2s;
    }
    .btn-back:hover { background: #e2e8f0; color: #0f172a; }
    .btn-save {
        padding: 12px 28px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        font-size: 0.95rem;
        transition: opacity .2s, transform .1s;
    }
    .btn-save:hover { opacity: .9; transform: translateY(-1px); }
    .form-card .error-box {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626;
        padding: 14px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 0.875rem;
    }
    .form-card .error-box ul { margin: 0; padding-left: 18px; }
    .form-hint {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 4px;
        transition: color 0.3s;
    }
    .current-photo {
        margin-bottom: 10px;
    }
    .current-photo img {
        max-width: 200px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    /* Dark Mode */
    .dark-mode .form-card { background: #1e293b; border-color: #334155; box-shadow: none; }
    .dark-mode .form-card .form-header { border-bottom-color: #334155; }
    .dark-mode .form-card .form-header h3 { color: #f1f5f9; }
    .dark-mode .form-card label { color: #cbd5e1; }
    .dark-mode .form-card input[type="text"],
    .dark-mode .form-card input[type="number"],
    .dark-mode .form-card input[type="file"],
    .dark-mode .form-card select,
    .dark-mode .form-card textarea { background: #0f172a; border-color: #475569; color: #e2e8f0; }
    .dark-mode .form-card input:focus,
    .dark-mode .form-card select:focus,
    .dark-mode .form-card textarea:focus { border-color: #6366f1; background: #0f172a; box-shadow: 0 0 0 3px rgba(99,102,241,.25); }
    .dark-mode .form-card input::placeholder,
    .dark-mode .form-card textarea::placeholder { color: #64748b; }
    .dark-mode .form-card select option { background: #1e293b; color: #e2e8f0; }
    .dark-mode .btn-back { background: #334155; color: #cbd5e1; }
    .dark-mode .btn-back:hover { background: #475569; color: #f1f5f9; }
    .dark-mode .form-card .error-box { background: rgba(239,68,68,.15); border-color: #991b1b; color: #fca5a5; }
    .dark-mode .form-hint { color: #94a3b8; }
    .dark-mode .current-photo img { border-color: #334155; }

    @media (max-width: 768px) {
        .form-grid-2, .form-grid-4 { grid-template-columns: 1fr; }
    }
</style>

<div class="form-card">
    <div class="form-header">
        <h3>✏️ Edit Properti: {{ $rumah->nama }}</h3>
        <a href="{{ route('admin.rumah.index') }}" class="btn-back">← Kembali</a>
    </div>

    @if($errors->any())
        <div class="error-box">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.rumah.update', $rumah->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Row 1: Nama & Tipe --}}
        <div class="form-grid-2">
            <div>
                <label for="nama">Nama Properti *</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $rumah->nama) }}" required>
            </div>
            <div>
                <label for="tipe">Tipe Properti *</label>
                <select id="tipe" name="tipe" required>
                    <option value="">— Pilih Tipe —</option>
                    @foreach(['Rumah','Apartemen','Ruko','Villa','Tanah'] as $t)
                        <option value="{{ $t }}" {{ old('tipe', $rumah->tipe) == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Row 2: Lokasi --}}
        <div class="form-group">
            <label for="lokasi">Lokasi *</label>
            <select id="lokasi" name="lokasi" required>
                <option value="">— Pilih Lokasi —</option>
                @foreach(['Jakarta Pusat','Jakarta Selatan','Jakarta Barat','Jakarta Timur','Jakarta Utara','Bogor','Depok','Tangerang','Tangerang Selatan','Bekasi'] as $kota)
                    <option value="{{ $kota }}" {{ old('lokasi', $rumah->lokasi) == $kota ? 'selected' : '' }}>{{ $kota }}</option>
                @endforeach
            </select>
            <div class="form-hint">Wilayah Jabodetabek</div>
        </div>

        {{-- Row 3: Harga --}}
        <div class="form-group">
            <label for="harga">Harga (Rp) *</label>
            <input type="number" id="harga" name="harga" value="{{ old('harga', $rumah->harga) }}" required min="0">
            <div class="form-hint">Masukkan dalam angka penuh tanpa titik (contoh: 500000000 = Rp 500 Juta)</div>
        </div>

        {{-- Row 4: Luas & Kamar --}}
        <div class="form-grid-4">
            <div>
                <label for="luas_tanah">L. Tanah (m²) *</label>
                <input type="number" id="luas_tanah" name="luas_tanah" value="{{ old('luas_tanah', $rumah->luas_tanah) }}" required min="0">
            </div>
            <div>
                <label for="luas_bangunan">L. Bangunan (m²) *</label>
                <input type="number" id="luas_bangunan" name="luas_bangunan" value="{{ old('luas_bangunan', $rumah->luas_bangunan) }}" required min="0">
            </div>
            <div>
                <label for="kamar_tidur">K. Tidur *</label>
                <input type="number" id="kamar_tidur" name="kamar_tidur" value="{{ old('kamar_tidur', $rumah->kamar_tidur) }}" required min="0">
            </div>
            <div>
                <label for="kamar_mandi">K. Mandi *</label>
                <input type="number" id="kamar_mandi" name="kamar_mandi" value="{{ old('kamar_mandi', $rumah->kamar_mandi) }}" required min="0">
            </div>
        </div>

        {{-- Row 5: Deskripsi --}}
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4">{{ old('deskripsi', $rumah->deskripsi) }}</textarea>
        </div>

        {{-- Row 6: Foto --}}
        <div class="form-group">
            <label for="foto">Foto Properti</label>
            @if($rumah->foto && is_array($rumah->foto) && count($rumah->foto) > 0)
                <div class="current-photo">
                    @php $fotoUrl = $rumah->foto[0]; @endphp
                    @if(is_string($fotoUrl) && \Illuminate\Support\Str::startsWith($fotoUrl, 'http'))
                        <img src="{{ $fotoUrl }}" alt="Foto saat ini">
                    @elseif(is_string($fotoUrl) && \Illuminate\Support\Str::startsWith($fotoUrl, 'rumah_photos/'))
                        <img src="{{ asset('storage/' . $fotoUrl) }}" alt="Foto saat ini">
                    @else
                        <img src="{{ asset($fotoUrl) }}" alt="Foto saat ini">
                    @endif
                </div>
            @elseif($rumah->foto && is_string($rumah->foto))
                <div class="current-photo">
                    @if(\Illuminate\Support\Str::startsWith($rumah->foto, 'http'))
                        <img src="{{ $rumah->foto }}" alt="Foto saat ini">
                    @elseif(\Illuminate\Support\Str::startsWith($rumah->foto, 'rumah_photos/'))
                        <img src="{{ asset('storage/' . $rumah->foto) }}" alt="Foto saat ini">
                    @else
                        <img src="{{ asset($rumah->foto) }}" alt="Foto saat ini">
                    @endif
                </div>
            @endif
            <input type="file" id="foto" name="foto" accept="image/*">
            <div class="form-hint">Kosongkan jika tidak ingin mengubah foto. Format: JPEG, PNG, GIF. Maks 2MB</div>
        </div>

        {{-- Submit --}}
        <div style="padding-top: 10px;">
            <button type="submit" class="btn-save">💾 Update Properti</button>
        </div>
    </form>
</div>
@endsection
