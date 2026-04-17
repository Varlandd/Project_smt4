@extends('layouts.app')

@section('title', 'Favorit Saya — RumahKu')

@push('styles')
<style>
    .favorit-page {
        min-height: 100vh;
        padding: 6rem 2rem 4rem;
        background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 50%, #ecfdf5 100%);
    }
    .favorit-container { max-width: 1200px; margin: 0 auto; }

    .favorit-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;
    }
    .favorit-header h1 { font-size: 1.8rem; font-weight: 800; }
    .favorit-header p { color: var(--text-soft); font-size: .95rem; }
    .favorit-header a {
        color: var(--primary); text-decoration: none; font-weight: 700;
        font-size: .9rem; transition: color .2s;
    }
    .favorit-header a:hover { color: var(--primary-dark); }

    .property-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem; margin-bottom: 2rem;
    }
    .property-card {
        background: white; border-radius: 18px; overflow: hidden;
        border: 1px solid var(--border); transition: all .3s;
        text-decoration: none; color: inherit; display: flex;
        flex-direction: column; position: relative;
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
    .property-card-loc { display: flex; align-items: center; gap: .35rem; font-size: .85rem; color: var(--text-soft); margin-bottom: .8rem; }
    .property-card-loc svg { width: 14px; height: 14px; flex-shrink: 0; }
    .property-card-specs { display: flex; gap: 1rem; margin-bottom: 1rem; padding-top: .8rem; border-top: 1px solid var(--border); }
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

    .empty-card {
        background: white; border-radius: 18px; padding: 3rem 2rem;
        border: 2px dashed var(--border); text-align: center; grid-column: 1 / -1;
    }
    .empty-card-icon { font-size: 3rem; margin-bottom: 1rem; }
    .empty-card h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: .4rem; }
    .empty-card p { color: var(--text-soft); font-size: .9rem; margin-bottom: 1rem; }

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

    @media (max-width: 768px) {
        .favorit-page { padding: 5rem 1rem 3rem; }
        .property-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<section class="favorit-page">
    <div class="favorit-container">

        @if(session('success'))
            <div class="form-success">{{ session('success') }}</div>
        @endif

        <div class="favorit-header">
            <div>
                <h1>❤️ Favorit Saya</h1>
                <p>{{ $rumahs->total() }} properti yang kamu tandai sebagai favorit</p>
            </div>
            <a href="{{ route('properti.browse') }}">← Jelajahi Properti Lainnya</a>
        </div>

        <div class="property-grid">
            @forelse($rumahs as $rumah)
                @include('components.property-card', ['rumah' => $rumah])
            @empty
                <div class="empty-card">
                    <div class="empty-card-icon">💝</div>
                    <h3>Belum Ada Favorit</h3>
                    <p>Jelajahi properti dan tandai yang kamu suka dengan ikon ❤️</p>
                    <a href="{{ route('properti.browse') }}" class="btn btn-primary" style="display:inline-flex;">Jelajahi Properti</a>
                </div>
            @endforelse
        </div>

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
