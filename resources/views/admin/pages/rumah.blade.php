@extends('admin.layouts.admin')

@section('title', 'Data Rumah — Admin RumahKu')
@section('page-title', 'Data Rumah')

@section('content')
<div class="admin-section">
    <div class="admin-section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Daftar Properti</h3>
        <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <form action="{{ route('admin.rumah.index') }}" method="GET" id="filterForm">
                <select name="per_page" onchange="document.getElementById('filterForm').submit()" style="padding: 8px; border-radius: 6px; border: 1px solid #d1d5db; font-size: 0.875rem;">
                    <option value="5" {{ isset($perPage) && $perPage == 5 ? 'selected' : '' }}>5 data</option>
                    <option value="10" {{ isset($perPage) && $perPage == 10 ? 'selected' : '' }}>10 data</option>
                    <option value="50" {{ isset($perPage) && $perPage == 50 ? 'selected' : '' }}>50 data</option>
                    <option value="100" {{ isset($perPage) && $perPage == 100 ? 'selected' : '' }}>100 data</option>
                </select>
            </form>
            <span class="admin-count">{{ $rumahs->total() }} data</span>
            
            <a href="{{ route('admin.rumah.export') }}" class="btn btn-secondary" style="padding: 8px 15px; background: #10b981; color: white; border-radius: 6px; text-decoration: none; font-weight: 500;">Unduh Template Excel</a>
            
            <form action="{{ route('admin.rumah.import') }}" method="POST" enctype="multipart/form-data" id="importForm" style="margin: 0;">
                @csrf
                <input type="file" name="file_excel" id="file_excel" accept=".xlsx, .xls, .csv" style="display: none;" onchange="document.getElementById('importForm').submit()">
                <button type="button" onclick="document.getElementById('file_excel').click()" class="btn btn-warning" style="padding: 8px 15px; background: #f59e0b; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer;">Import Excel</button>
            </form>

            <a href="{{ route('admin.rumah.create') }}" class="btn btn-primary" style="padding: 8px 15px; background: #4f46e5; color: white; border-radius: 6px; text-decoration: none; font-weight: 500;">+ Tambah Properti</a>
        </div>
    </div>
    
    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif
    
    @if($errors->any())
        <div style="background-color: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
    
    @if($rumahs->hasPages())
    <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
        <span style="color: #9ca3af; font-size: 0.875rem;">
            Menampilkan {{ $rumahs->firstItem() }}–{{ $rumahs->lastItem() }} dari {{ $rumahs->total() }} data
        </span>
        <div style="display: flex; gap: 4px; align-items: center;">
            {{-- Previous --}}
            @if($rumahs->onFirstPage())
                <span style="padding: 8px 12px; border-radius: 6px; background: #1f2937; color: #4b5563; font-size: 0.875rem; cursor: default;">‹</span>
            @else
                <a href="{{ $rumahs->previousPageUrl() }}" style="padding: 8px 12px; border-radius: 6px; background: #374151; color: #d1d5db; font-size: 0.875rem; text-decoration: none; transition: background .2s;">‹</a>
            @endif

            {{-- Page Numbers --}}
            @foreach($rumahs->getUrlRange(max($rumahs->currentPage()-2, 1), min($rumahs->currentPage()+2, $rumahs->lastPage())) as $page => $url)
                @if($page == $rumahs->currentPage())
                    <span style="padding: 8px 12px; border-radius: 6px; background: #4f46e5; color: #fff; font-size: 0.875rem; font-weight: 600;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="padding: 8px 12px; border-radius: 6px; background: #374151; color: #d1d5db; font-size: 0.875rem; text-decoration: none; transition: background .2s;">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next --}}
            @if($rumahs->hasMorePages())
                <a href="{{ $rumahs->nextPageUrl() }}" style="padding: 8px 12px; border-radius: 6px; background: #374151; color: #d1d5db; font-size: 0.875rem; text-decoration: none; transition: background .2s;">›</a>
            @else
                <span style="padding: 8px 12px; border-radius: 6px; background: #1f2937; color: #4b5563; font-size: 0.875rem; cursor: default;">›</span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
