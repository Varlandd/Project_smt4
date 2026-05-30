@extends('layouts.app')

@section('title', 'Jelajahi Properti — RumahKu')

@push('styles')
<style>
    .browse-page {
        min-height: 100vh;
        padding: 6rem 2rem 4rem;
        background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 50%, #ecfdf5 100%);
    }
    .browse-container { max-width: 1200px; margin: 0 auto; }

    .browse-header { margin-bottom: 2rem; }
    .browse-header h1 { font-size: 1.8rem; font-weight: 800; margin-bottom: .3rem; }
    .browse-header p { color: var(--text-soft); font-size: .95rem; }

    /* ── Filter Bar ── */
    .filter-bar {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid var(--border);
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }
    .filter-form {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    .filter-group { display: flex; flex-direction: column; gap: .4rem; flex: 1; min-width: 160px; }
    .filter-group label {
        font-size: .78rem; font-weight: 700; color: var(--text-soft);
        text-transform: uppercase; letter-spacing: .04em;
    }
    .filter-group input,
    .filter-group select {
        padding: .65rem .9rem;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-size: .88rem;
        font-family: inherit;
        color: var(--text-dark);
        background: white;
        transition: border-color .2s;
    }
    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary);
    }
    .filter-actions {
        display: flex; gap: .5rem; align-items: flex-end;
    }
    .btn-filter {
        padding: .65rem 1.3rem;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: .88rem;
        font-family: inherit;
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
    }
    .btn-filter:hover { background: var(--primary-dark); }
    .btn-reset {
        padding: .65rem 1.3rem;
        background: var(--bg-soft);
        color: var(--text-soft);
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-weight: 600;
        font-size: .88rem;
        font-family: inherit;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s;
        white-space: nowrap;
    }
    .btn-reset:hover { background: #e2e8f0; color: var(--text-dark); }

    /* ── Results Info ── */
    .results-info {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 1.25rem;
    }
    .results-info span { font-size: .88rem; color: var(--text-soft); font-weight: 600; }
    .sort-select {
        padding: .5rem .8rem;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-size: .85rem;
        font-family: inherit;
        color: var(--text-dark);
        background: white;
    }

    /* ── Property Grid (reuse from dashboard) ── */
    .property-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .property-card {
        background: white; border-radius: 18px; overflow: hidden;
        border: 1px solid var(--border); transition: all .3s;
        text-decoration: none; color: inherit;
        display: flex; flex-direction: column; position: relative;
    }
    .property-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); border-color: var(--primary); }
    .property-card-img {
        width: 100%; height: 200px; object-fit: cover;
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        display: flex; align-items: center; justify-content: center;
        font-size: 3rem; color: var(--text-soft); position: relative;
    }
    .property-card-img img { width: 100%; height: 100%; object-fit: cover; }
    .property-card-body { padding: 1.3rem; flex: 1; display: flex; flex-direction: column; }
    .property-card-type {
        display: inline-block; background: var(--primary-light); color: var(--primary);
        padding: .2rem .7rem; border-radius: 6px; font-size: .75rem; font-weight: 700;
        margin-bottom: .6rem; width: fit-content;
    }
    .property-card-name { font-size: 1.05rem; font-weight: 700; margin-bottom: .4rem; color: var(--text-dark); }
    .property-card-loc {
        display: flex; align-items: center; gap: .35rem;
        font-size: .85rem; color: var(--text-soft); margin-bottom: .8rem;
    }
    .property-card-loc svg { width: 14px; height: 14px; flex-shrink: 0; }
    .property-card-specs {
        display: flex; gap: 1rem; margin-bottom: 1rem;
        padding-top: .8rem; border-top: 1px solid var(--border);
    }
    .spec-item { display: flex; align-items: center; gap: .3rem; font-size: .8rem; color: var(--text-soft); font-weight: 600; }
    .spec-item svg { width: 14px; height: 14px; }
    .property-card-bottom { display: flex; justify-content: space-between; align-items: center; margin-top: auto; }
    .property-card-price { font-size: 1.15rem; font-weight: 800; color: var(--primary); }
    .btn-fav {
        width: 36px; height: 36px; border-radius: 10px; border: 1.5px solid var(--border);
        background: white; cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: all .2s; position: absolute; top: 12px; right: 12px; z-index: 2;
    }
    .btn-fav:hover { border-color: #ef4444; background: #fef2f2; }
    .btn-fav svg { width: 18px; height: 18px; }
    .btn-fav.active { background: #fef2f2; border-color: #ef4444; }

    /* ── Pagination ── */
    .pagination-bar {
        display: flex; justify-content: center; align-items: center; gap: .4rem;
    }
    .pagination-bar a, .pagination-bar span {
        padding: .55rem .9rem; border-radius: 8px; font-size: .88rem; font-weight: 600;
        text-decoration: none; transition: all .2s;
    }
    .pagination-bar a { background: white; color: var(--text-mid); border: 1px solid var(--border); }
    .pagination-bar a:hover { background: var(--primary-light); color: var(--primary); border-color: var(--primary); }
    .pagination-bar .active-page { background: var(--primary); color: white; border: 1px solid var(--primary); }
    .pagination-bar .disabled { color: var(--text-light); background: var(--bg-soft); border: 1px solid var(--border); cursor: default; }

    .empty-card {
        background: white; border-radius: 18px; padding: 3rem 2rem;
        border: 2px dashed var(--border); text-align: center; grid-column: 1 / -1;
    }
    .empty-card-icon { font-size: 3rem; margin-bottom: 1rem; }
    .empty-card h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: .4rem; }
    .empty-card p { color: var(--text-soft); font-size: .9rem; }

    @media (max-width: 768px) {
        .browse-page { padding: 5rem 1rem 3rem; }
        .filter-form { flex-direction: column; }
        .property-grid { grid-template-columns: 1fr; }
    }

    /* ── Input Rupiah dengan prefix Rp ── */
.filter-group:has(.input-rupiah) {
    position: relative;
}
.filter-group:has(.input-rupiah) label {
    position: relative;
    z-index: 1;
}
.filter-group:has(.input-rupiah)::after {
    content: 'Rp';
    position: absolute;
    bottom: 11px;
    left: 10px;
    font-size: .83rem;
    color: var(--text-soft);
    font-weight: 500;
    pointer-events: none;
    z-index: 1;
}
.input-rupiah {
    padding-left: 30px !important;
}

/* ── Samakan tinggi tombol Cari & Reset ── */
.btn-filter,
.btn-reset {
    height: 40px;
    padding: 0 1.3rem !important;
    display: flex !important;
    align-items: center;
    justify-content: center;
}
</style>
@endpush

@section('content')
<section class="browse-page">
    <div class="browse-container">
        <div class="browse-header">
            <h1>🏠 Jelajahi Properti</h1>
            <p>Temukan properti impian dari {{ $rumahs->total() }} listing yang tersedia</p>
        </div>

        @if(session('success'))
            <div class="form-success">{{ session('success') }}</div>
        @endif

        {{-- ═══ FILTER BAR ═══ --}}
        <div class="filter-bar">
            <form class="filter-form" action="{{ route('properti.browse') }}" method="GET">
                <div class="filter-group">
                    <label>Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama properti...">
                </div>
                <div class="filter-group">
                    <label>Lokasi</label>
                    <select name="lokasi">
                        <option value="">Semua Lokasi</option>
                        @foreach($lokasiList as $lok)
                            <option value="{{ $lok }}" {{ request('lokasi') == $lok ? 'selected' : '' }}>{{ $lok }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Tipe</label>
                    <select name="tipe">
                        <option value="">Semua Tipe</option>
                        @foreach(['Rumah'] as $t)
                            <option value="{{ $t }}" {{ request('tipe') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group" style="min-width:130px;">
    <label>Harga Min</label>
    <input type="text" name="harga_min"
           value="{{ request('harga_min') ? number_format(request('harga_min'), 0, ',', '.') : '' }}"
           placeholder="Min" oninput="formatRupiah(this)"
           class="input-rupiah">
</div>
<div class="filter-group" style="min-width:130px;">
    <label>Harga Max</label>
    <input type="text" name="harga_max"
           value="{{ request('harga_max') ? number_format(request('harga_max'), 0, ',', '.') : '' }}"
           placeholder="Max" oninput="formatRupiah(this)"
           class="input-rupiah">
</div>
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">🔍 Cari</button>
                    <a href="{{ route('properti.browse') }}" class="btn-reset">Reset</a>
                </div>
            </form>
        </div>

        {{-- ═══ RESULTS ═══ --}}
        <div class="results-info">
            <span>Menampilkan {{ $rumahs->firstItem() ?? 0 }}–{{ $rumahs->lastItem() ?? 0 }} dari {{ $rumahs->total() }} properti</span>
            <form action="{{ route('properti.browse') }}" method="GET" style="display:inline;">
                @foreach(request()->except('sort','page') as $key => $val)
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endforeach
                <select name="sort" class="sort-select" onchange="this.form.submit()">
                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                    <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Termurah</option>
                    <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Termahal</option>
                </select>
            </form>
        </div>

        <div class="property-grid">
            @forelse($rumahs as $rumah)
                @include('components.property-card', ['rumah' => $rumah])
            @empty
                <div class="empty-card">
                    <div class="empty-card-icon">🔍</div>
                    <h3>Tidak Ada Hasil</h3>
                    <p>Coba ubah filter pencarian kamu.</p>
                </div>
            @endforelse
        </div>

        {{-- ═══ PAGINATION ═══ --}}
        @if($rumahs->hasPages())
        <div class="pagination-bar">
            @if($rumahs->onFirstPage())
                <span class="disabled">‹</span>
            @else
                <a href="{{ $rumahs->previousPageUrl() }}">‹</a>
            @endif

            @foreach($rumahs->getUrlRange(max($rumahs->currentPage()-2, 1), min($rumahs->currentPage()+2, $rumahs->lastPage())) as $page => $url)
                @if($page == $rumahs->currentPage())
                    <span class="active-page">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if($rumahs->hasMorePages())
                <a href="{{ $rumahs->nextPageUrl() }}">›</a>
            @else
                <span class="disabled">›</span>
            @endif
        </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
function formatRupiah(input) {
    let raw = input.value.replace(/\D/g, '');
    input.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
}

// Hapus titik sebelum form submit
document.querySelector('.filter-form').addEventListener('submit', function() {
    ['harga_min', 'harga_max'].forEach(function(name) {
        const el = document.querySelector('[name="' + name + '"]');
        if (el) el.value = el.value.replace(/\./g, '');
    });
});

</script>
@endpush