@extends('admin.layouts.admin')

@section('title', 'Data Rumah — Admin RumahKu')
@section('page-title', 'Data Rumah')

@section('content')
<div class="admin-section">
    <div class="admin-section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Daftar Properti</h3>
        <div style="display: flex; gap: 15px; align-items: center;">
            <span class="admin-count">{{ $rumahs->total() }} data</span>
            <a href="{{ route('admin.rumah.create') }}" class="btn btn-primary" style="padding: 8px 15px; background: #4f46e5; color: white; border-radius: 6px; text-decoration: none; font-weight: 500;">+ Tambah Properti</a>
        </div>
    </div>
    
    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-table-wrapper" style="overflow-x: auto;">
        <table class="admin-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #e5e7eb; text-align: left;">
                    <th style="padding: 12px;">#</th>
                    <th style="padding: 12px;">Foto</th>
                    <th style="padding: 12px;">Nama</th>
                    <th style="padding: 12px;">Lokasi</th>
                    <th style="padding: 12px;">Harga</th>
                    <th style="padding: 12px;">Tipe</th>
                    <th style="padding: 12px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rumahs as $i => $rumah)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">{{ $rumahs->firstItem() + $i }}</td>
                    <td style="padding: 12px;">
                        @if($rumah->foto)
                            @if(\Illuminate\Support\Str::startsWith($rumah->foto, 'http'))
                                <img src="{{ $rumah->foto }}" alt="{{ $rumah->nama }}" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                            @elseif(\Illuminate\Support\Str::startsWith($rumah->foto, 'rumah_photos/'))
                                <img src="{{ asset('storage/' . $rumah->foto) }}" alt="{{ $rumah->nama }}" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                            @else
                                <img src="{{ asset($rumah->foto) }}" alt="{{ $rumah->nama }}" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                            @endif
                        @else
                            <span style="color: #9ca3af; font-size: 0.875rem;">No Image</span>
                        @endif
                    </td>
                    <td style="padding: 12px; font-weight: 500;">{{ $rumah->nama }}</td>
                    <td style="padding: 12px; color: #6b7280;">{{ $rumah->lokasi }}</td>
                    <td style="padding: 12px; color: #10b981; font-weight: 600;">Rp {{ number_format($rumah->harga, 0, ',', '.') }}</td>
                    <td style="padding: 12px;">
                        <span style="background: #f3f4f6; padding: 4px 8px; border-radius: 4px; font-size: 0.875rem;">{{ ucfirst($rumah->tipe) }}</span>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('admin.rumah.edit', $rumah->id) }}" style="color: #f59e0b; text-decoration: none; font-size: 0.875rem;">Edit</a>
                            <form action="{{ route('admin.rumah.destroy', $rumah->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus properti ini?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: #ef4444; background: none; border: none; cursor: pointer; font-size: 0.875rem; padding: 0;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="empty-state" style="padding: 20px; text-align: center; color: #6b7280;">Belum ada data rumah.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 20px;">
        {{ $rumahs->links() }}
    </div>
</div>
@endsection
