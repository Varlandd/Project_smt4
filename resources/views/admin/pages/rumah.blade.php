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

    {{-- ═══ SEARCH BAR ═══ --}}
<form action="{{ route('admin.rumah.index') }}" method="GET" style="margin-bottom: 20px;">
    <input type="hidden" name="per_page" value="{{ $perPage ?? 10 }}">
    <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;
                background: #f9fafb; padding: 16px; border-radius: 10px; border: 1.5px solid #e5e7eb;">
        <div style="flex: 2; min-width: 200px; position: relative;">
            <span style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:.9rem;">🔍</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, lokasi, kota..."
                   style="width:100%; padding:9px 12px 9px 34px; border-radius:8px;
                          border:1.5px solid #e5e7eb; background:white; color:#111827;
                          font-size:.875rem; outline:none; box-sizing:border-box;">
        </div>
        <div style="flex: 1; min-width: 130px; position: relative;">
    <span style="position:absolute; left:10px; top:50%; transform:translateY(-50%);
                 font-size:.83rem; color:#9ca3af; font-weight:500; pointer-events:none;">Rp</span>
    <input type="text" name="harga_min" value="{{ request('harga_min') }}"
           placeholder="Harga Min" oninput="formatRupiahAdmin(this)"
           style="width:100%; padding:9px 12px 9px 30px; border-radius:8px;
                  border:1.5px solid #e5e7eb; background:white; color:#111827;
                  font-size:.875rem; outline:none; box-sizing:border-box;">
</div>
<div style="flex: 1; min-width: 130px; position: relative;">
    <span style="position:absolute; left:10px; top:50%; transform:translateY(-50%);
                 font-size:.83rem; color:#9ca3af; font-weight:500; pointer-events:none;">Rp</span>
    <input type="text" name="harga_max" value="{{ request('harga_max') }}"
           placeholder="Harga Max" oninput="formatRupiahAdmin(this)"
           style="width:100%; padding:9px 12px 9px 30px; border-radius:8px;
                  border:1.5px solid #e5e7eb; background:white; color:#111827;
                  font-size:.875rem; outline:none; box-sizing:border-box;">
</div>
        <div style="flex: 1; min-width: 130px;">
            <input type="number" name="kamar_tidur" value="{{ request('kamar_tidur') }}"
                   placeholder="Kamar Tidur"
                   style="width:100%; padding:9px 12px; border-radius:8px;
                          border:1.5px solid #e5e7eb; background:white; color:#111827;
                          font-size:.875rem; outline:none; box-sizing:border-box;">
        </div>
        <div style="display:flex; gap:8px;">
            <button type="submit"
                    style="padding:9px 18px; background:#4f46e5; color:white; border:none;
                           border-radius:8px; font-weight:600; font-size:.875rem; cursor:pointer;">
                🔍 Cari
            </button>
            <a href="{{ route('admin.rumah.index') }}"
               style="padding:9px 14px; background:white; color:#6b7280; border-radius:8px;
                      font-size:.875rem; text-decoration:none; font-weight:500;
                      border:1.5px solid #e5e7eb;">
                Reset
            </a>
        </div>
    </div>
    @if(request('search') || request('harga_min') || request('harga_max') || request('kamar_tidur'))
    <div style="margin-top:8px; font-size:.82rem; color:#6b7280;">
        Filter aktif:
        @if(request('search')) <span style="background:#ede9fe;color:#5b21b6;padding:2px 8px;border-radius:4px;margin-right:4px;">Kata kunci: "{{ request('search') }}"</span> @endif
        @if(request('harga_min')) <span style="background:#dcfce7;color:#15803d;padding:2px 8px;border-radius:4px;margin-right:4px;">Harga Min: Rp {{ number_format((float) str_replace('.', '', request('harga_min')),0,',','.') }}</span> @endif
        @if(request('harga_max')) <span style="background:#dcfce7;color:#15803d;padding:2px 8px;border-radius:4px;margin-right:4px;">Harga Max: Rp {{ number_format((float) str_replace('.', '', request('harga_max')),0,',','.') }}</span> @endif
        @if(request('kamar_tidur')) <span style="background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:4px;">KT: {{ request('kamar_tidur') }}</span> @endif
    </div>
    @endif
</form>

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

    <form action="{{ route('admin.rumah.bulk-delete') }}" method="POST" id="bulkDeleteForm">
        @csrf
        @method('DELETE')
        
        <div class="admin-table-wrapper" style="overflow-x: auto;">
            <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                <button type="button" id="btnBulkDelete" class="btn btn-danger" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; display: none;" onclick="confirmBulkDelete()">
                    Hapus Terpilih (<span id="selectedCount">0</span>)
                </button>
            </div>

            <table class="admin-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e5e7eb; text-align: left;">
                        <th style="padding: 12px; width: 40px;">
                            <input type="checkbox" id="selectAll" style="cursor: pointer;">
                        </th>
                        <th style="padding: 12px;">#</th>
                        <th style="padding: 12px;">Foto</th>
                        <th style="padding: 12px;">Nama</th>
                        <th style="padding: 12px;">Lokasi</th>
                        <th style="padding: 12px;">Kota</th>
                        <th style="padding: 12px;">Luas (T/B)</th>
                        <th style="padding: 12px;">Kamar (T/M)</th>
                        <th style="padding: 12px;">Kategori (Cluster)</th>
                        <th style="padding: 12px;">Harga</th>
                        <th style="padding: 12px;">Tipe</th>
                        <th style="padding: 12px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rumahs as $i => $rumah)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px;">
                            <input type="checkbox" name="ids[]" value="{{ $rumah->id }}" class="rumah-checkbox" style="cursor: pointer;">
                        </td>
                        <td style="padding: 12px;">{{ $rumahs->firstItem() + $i }}</td>
                        <td style="padding: 12px;">
                          @php
        $fotoUrl = is_array($rumah->foto) ? ($rumah->foto[0] ?? null) : $rumah->foto;
    @endphp

   @if($fotoUrl)
    <img src="{{ asset($fotoUrl) }}" alt="{{ $rumah->nama }}" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                            @else
                                <span style="color: #9ca3af; font-size: 0.875rem;">No Image</span>
                            @endif
                        </td>
                        <td style="padding: 12px; font-weight: 500;">{{ $rumah->nama }}</td>
                        <td style="padding: 12px; color: #6b7280;">{{ $rumah->lokasi }}</td>
                        <td style="padding: 12px; color: #6b7280;">{{ $rumah->kota ?? '-' }}</td>
                        <td style="padding: 12px; color: #4b5563;">{{ $rumah->luas_tanah ?? 0 }}m² / {{ $rumah->luas_bangunan ?? 0 }}m²</td>
                        <td style="padding: 12px; color: #4b5563;">{{ $rumah->kamar_tidur ?? 0 }} / {{ $rumah->kamar_mandi ?? 0 }}</td>
                        <td style="padding: 12px;">
                            @if($rumah->kategori_harga)
                                <span style="background: #e0e7ff; color: #4338ca; padding: 4px 8px; border-radius: 4px; font-size: 0.875rem; white-space: nowrap;">
                                    {{ $rumah->kategori_harga }} (C-{{ $rumah->cluster_harga }})
                                </span>
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                        <td style="padding: 12px; color: #10b981; font-weight: 600;">Rp {{ number_format($rumah->harga, 0, ',', '.') }}</td>
                        <td style="padding: 12px;">
                            <span style="background: #f3f4f6; padding: 4px 8px; border-radius: 4px; font-size: 0.875rem;">{{ ucfirst($rumah->tipe) }}</span>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.rumah.edit', $rumah->id) }}" style="color: #f59e0b; text-decoration: none; font-size: 0.875rem;">Edit</a>
                                <button type="button" onclick="deleteIndividual('{{ route('admin.rumah.destroy', $rumah->id) }}')" style="color: #ef4444; background: none; border: none; cursor: pointer; font-size: 0.875rem; padding: 0;">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="12" class="empty-state" style="padding: 20px; text-align: center; color: #6b7280;">Belum ada data rumah.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    {{-- Form tersembunyi untuk hapus individual --}}
    <form id="deleteIndividualForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.rumah-checkbox');
            const btnBulkDelete = document.getElementById('btnBulkDelete');
            const selectedCount = document.getElementById('selectedCount');

            function updateBulkDeleteButton() {
                const checked = document.querySelectorAll('.rumah-checkbox:checked');
                selectedCount.textContent = checked.length;
                if (checked.length > 0) {
                    btnBulkDelete.style.display = 'block';
                } else {
                    btnBulkDelete.style.display = 'none';
                }
            }

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                });
                updateBulkDeleteButton();
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkDeleteButton);
            });
        });

        function confirmBulkDelete() {
            if (confirm('Apakah Anda yakin ingin menghapus data yang dipilih?')) {
                document.getElementById('bulkDeleteForm').submit();
            }
        }

        function deleteIndividual(url) {
            if (confirm('Apakah Anda yakin ingin menghapus properti ini?')) {
                const form = document.getElementById('deleteIndividualForm');
                form.action = url;
                form.submit();
            }
        }

        function formatRupiahAdmin(input) {
    let raw = input.value.replace(/\D/g, '');
    input.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
}

// Hapus titik sebelum submit
document.querySelector('form[action="{{ route('admin.rumah.index') }}"]')
    ?.addEventListener('submit', function() {
        ['harga_min', 'harga_max'].forEach(function(name) {
            const el = document.querySelector('[name="' + name + '"]');
            if (el) el.value = el.value.replace(/\./g, '');
        });
    });
    </script>
    
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
