@extends('admin.layouts.admin')

@section('title', 'Data Rumah — Admin RumahKu')
@section('page-title', 'Data Rumah')

@section('content')
<div class="admin-section">
    <div class="admin-section-header">
        <h3>Daftar Properti</h3>
        <span class="admin-count">{{ \App\Models\Rumah::count() }} data</span>
    </div>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Lokasi</th>
                    <th>Harga</th>
                    <th>Tipe</th>
                    <th>Luas Tanah</th>
                    <th>Luas Bangunan</th>
                </tr>
            </thead>
            <tbody>
                @forelse(\App\Models\Rumah::latest()->take(20)->get() as $i => $rumah)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $rumah->nama }}</td>
                    <td>{{ $rumah->lokasi }}</td>
                    <td>Rp {{ number_format($rumah->harga, 0, ',', '.') }}</td>
                    <td>{{ $rumah->tipe }}</td>
                    <td>{{ $rumah->luas_tanah }} m²</td>
                    <td>{{ $rumah->luas_bangunan }} m²</td>
                </tr>
                @empty
                <tr><td colspan="7" class="empty-state">Belum ada data rumah.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
