@extends('layouts.app')

@section('title', $rumah->nama . ' — RumahKu')

@push('styles')
<style>
    .detail-page {
        min-height: 100vh;
        padding: 6rem 2rem 4rem;
        background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 50%, #ecfdf5 100%);
    }
    .detail-container { max-width: 1000px; margin: 0 auto; }

    /* ── Breadcrumb ── */
    .breadcrumb {
        display: flex; align-items: center; gap: .5rem;
        margin-bottom: 1.5rem; font-size: .85rem; flex-wrap: wrap;
    }
    .breadcrumb a { color: var(--text-soft); text-decoration: none; font-weight: 600; transition: color .2s; }
    .breadcrumb a:hover { color: var(--primary); }
    .breadcrumb span { color: var(--text-light); }

    /* ── Main Card ── */
    .detail-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-md);
        margin-bottom: 2.5rem;
    }
    .detail-img-wrapper {
        position: relative;
        width: 100%; height: 400px;
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        display: flex; align-items: center; justify-content: center;
        font-size: 5rem; color: var(--text-soft);
    }
    .detail-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    .detail-fav-btn {
        position: absolute; top: 1rem; right: 1rem;
        width: 44px; height: 44px; border-radius: 12px;
        border: 2px solid rgba(255,255,255,.5);
        background: rgba(255,255,255,.85); backdrop-filter: blur(8px);
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: all .2s;
    }
    .detail-fav-btn:hover { background: white; border-color: #ef4444; }
    .detail-fav-btn svg { width: 22px; height: 22px; }
    .detail-fav-btn.active { background: #fef2f2; border-color: #ef4444; }

    .detail-body { padding: 2rem; }

    .detail-top {
        display: flex; justify-content: space-between; align-items: flex-start;
        margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;
    }
    .detail-type {
        display: inline-block; background: var(--primary-light); color: var(--primary);
        padding: .3rem .9rem; border-radius: 8px; font-size: .8rem; font-weight: 700;
        margin-bottom: .5rem;
    }
    .detail-name { font-size: 1.6rem; font-weight: 800; color: var(--text-dark); margin-bottom: .3rem; }
    .detail-loc {
        display: flex; align-items: center; gap: .4rem;
        font-size: .95rem; color: var(--text-soft); font-weight: 500;
    }
    .detail-loc svg { width: 16px; height: 16px; flex-shrink: 0; }
    .detail-price {
        font-size: 1.8rem; font-weight: 800; color: var(--primary);
        text-align: right;
    }
    .detail-price-label { font-size: .78rem; color: var(--text-soft); font-weight: 600; }

    /* ── Specs Grid ── */
    .detail-specs {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 1rem; margin-bottom: 2rem;
        padding: 1.5rem; background: var(--bg-soft); border-radius: 14px;
    }
    .detail-spec {
        text-align: center; padding: .8rem;
    }
    .detail-spec-value { font-size: 1.3rem; font-weight: 800; color: var(--text-dark); }
    .detail-spec-label { font-size: .78rem; color: var(--text-soft); font-weight: 600; margin-top: .2rem; }

    /* ── Description ── */
    .detail-desc h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: .8rem; }
    .detail-desc p {
        color: var(--text-mid); font-size: .95rem; line-height: 1.8;
    }
    .detail-desc-empty {
        color: var(--text-light); font-style: italic; font-size: .9rem;
    }

    /* ── Similar ── */
    .similar-section { margin-bottom: 2rem; }
    .similar-section h2 { font-size: 1.3rem; font-weight: 800; margin-bottom: 1.25rem; }
    .property-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    .property-card {
        background: white; border-radius: 18px; overflow: hidden;
        border: 1px solid var(--border); transition: all .3s;
        text-decoration: none; color: inherit; display: flex;
        flex-direction: column; position: relative;
    }
    .property-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); border-color: var(--primary); }
    .property-card-img {
        width: 100%; height: 180px; object-fit: cover;
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        display: flex; align-items: center; justify-content: center;
        font-size: 3rem; color: var(--text-soft);
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

    .back-link {
        display: inline-flex; align-items: center; gap: .5rem;
        color: var(--primary); text-decoration: none; font-weight: 700;
        font-size: .9rem; margin-bottom: 1.5rem; transition: color .2s;
    }
    .back-link:hover { color: var(--primary-dark); }

    @media (max-width: 768px) {
        .detail-page { padding: 5rem 1rem 3rem; }
        .detail-img-wrapper { height: 250px; }
        .detail-specs { grid-template-columns: repeat(2, 1fr); }
        .detail-top { flex-direction: column; }
        .detail-price { text-align: left; }
        .property-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<section class="detail-page">
    <div class="detail-container">

        @if(session('success'))
            <div class="form-success">{{ session('success') }}</div>
        @endif

        {{-- Breadcrumb --}}
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <span>›</span>
            <a href="{{ route('properti.browse') }}">Properti</a>
            <span>›</span>
            <span style="color: var(--text-dark); font-weight: 600;">{{ $rumah->nama }}</span>
        </div>

        {{-- Main Detail Card --}}
        <div class="detail-card">
            <div class="detail-img-wrapper" style="position:relative; overflow:hidden;">
    @php
        $fotos = is_array($rumah->foto) ? $rumah->foto : ($rumah->foto ? [$rumah->foto] : []);
    @endphp

    @if(count($fotos) > 0)
        {{-- Slides --}}
        @foreach($fotos as $i => $foto)
            <div class="slide" data-index="{{ $i }}" style="display:{{ $i === 0 ? 'block' : 'none' }}; width:100%; height:100%;">
                <img src="{{ asset($foto) }}" alt="{{ $rumah->nama }}"
                     style="width:100%; height:100%; object-fit:cover; cursor:pointer;"
                     onclick="openLightbox({{ $i }})">
            </div>
        @endforeach

        {{-- Counter --}}
        @if(count($fotos) > 1)
            <div style="position:absolute; bottom:1rem; left:1rem; background:rgba(0,0,0,.5); color:white; padding:.3rem .8rem; border-radius:20px; font-size:.85rem; font-weight:600;">
                <span id="slideCounter">1</span>/{{ count($fotos) }}
            </div>

            {{-- Arrows --}}
            <button onclick="changeSlide(-1)" style="position:absolute; left:1rem; top:50%; transform:translateY(-50%); background:rgba(255,255,255,.85); border:none; border-radius:50%; width:40px; height:40px; font-size:1.2rem; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px rgba(0,0,0,.2);">‹</button>
            <button onclick="changeSlide(1)" style="position:absolute; right:4rem; top:50%; transform:translateY(-50%); background:rgba(255,255,255,.85); border:none; border-radius:50%; width:40px; height:40px; font-size:1.2rem; cursor:pointer; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px rgba(0,0,0,.2);">›</button>
        @endif
    @else
        🏠
    @endif

                <form action="{{ route('properti.favorit', $rumah->_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="detail-fav-btn {{ $rumah->is_favorit ? 'active' : '' }}" title="{{ $rumah->is_favorit ? 'Hapus Favorit' : 'Tambah Favorit' }}">
                        <svg viewBox="0 0 24 24" fill="{{ $rumah->is_favorit ? '#ef4444' : 'none' }}" stroke="{{ $rumah->is_favorit ? '#ef4444' : '#64748b' }}" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="detail-body">
                <div class="detail-top">
                    <div>
                        <span class="detail-type">{{ ucfirst($rumah->tipe ?? 'Rumah') }}</span>
                        <h1 class="detail-name">{{ $rumah->nama }}</h1>
                        <div class="detail-loc">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            {{ $rumah->lokasi }}
                        </div>
                    </div>
                    <div class="detail-price">
                        <div class="detail-price-label">Harga</div>
                        Rp {{ number_format($rumah->harga, 0, ',', '.') }}
                    </div>
                </div>

                {{-- Specs --}}
                <div class="detail-specs">
                    <div class="detail-spec">
                        <div class="detail-spec-value">{{ $rumah->luas_tanah ?? '-' }}</div>
                        <div class="detail-spec-label">Luas Tanah (m²)</div>
                    </div>
                    <div class="detail-spec">
                        <div class="detail-spec-value">{{ $rumah->luas_bangunan ?? '-' }}</div>
                        <div class="detail-spec-label">Luas Bangunan (m²)</div>
                    </div>
                    <div class="detail-spec">
                        <div class="detail-spec-value">{{ $rumah->kamar_tidur ?? '-' }}</div>
                        <div class="detail-spec-label">Kamar Tidur</div>
                    </div>
                    <div class="detail-spec">
                        <div class="detail-spec-value">{{ $rumah->kamar_mandi ?? '-' }}</div>
                        <div class="detail-spec-label">Kamar Mandi</div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="detail-desc">
                    <h3>📝 Deskripsi</h3>
                    @if($rumah->deskripsi)
                        <p style="white-space: pre-line">{{ $rumah->deskripsi }}</p>
                    @else
                        <p class="detail-desc-empty">Belum ada deskripsi untuk properti ini.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Similar Properties --}}
        @if($similar->count() > 0)
        <div class="similar-section">
            <h2>🏘️ Properti Serupa</h2>
            <div class="property-grid">
                @foreach($similar as $item)
                    @include('components.property-card', ['rumah' => $item])
                @endforeach
            </div>
        </div>
        @endif

        <a href="{{ route('properti.browse') }}" class="back-link">
            ← Kembali ke Daftar Properti
        </a>
    </div>
</section>
{{-- Lightbox --}}
@php $fotos = is_array($rumah->foto) ? $rumah->foto : ($rumah->foto ? [$rumah->foto] : []); @endphp
@if(count($fotos) > 0)
<div id="lightbox" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.9); z-index:9999; align-items:center; justify-content:center;">
    <button onclick="closeLightbox()" style="position:absolute; top:1rem; right:1.5rem; background:none; border:none; color:white; font-size:2rem; cursor:pointer;">✕</button>
    <button onclick="changeLightbox(-1)" style="position:absolute; left:1rem; background:rgba(255,255,255,.2); border:none; color:white; font-size:2rem; width:50px; height:50px; border-radius:50%; cursor:pointer;">‹</button>
    <img id="lightboxImg" src="" style="max-width:90vw; max-height:90vh; object-fit:contain; border-radius:8px;">
    <button onclick="changeLightbox(1)" style="position:absolute; right:1rem; background:rgba(255,255,255,.2); border:none; color:white; font-size:2rem; width:50px; height:50px; border-radius:50%; cursor:pointer;">›</button>
    <div style="position:absolute; bottom:1rem; color:white; font-size:.9rem;">
        <span id="lightboxCounter">1</span>/{{ count($fotos) }}
    </div>
</div>

<script>
    const fotos = @json($fotos).map(f => f.startsWith('http') ? f : '{{ asset('') }}' + f);
    let currentSlide = 0;
    let currentLightbox = 0;

    function changeSlide(dir) {
        document.querySelectorAll('.slide').forEach(s => s.style.display = 'none');
        currentSlide = (currentSlide + dir + fotos.length) % fotos.length;
        document.querySelector(`.slide[data-index="${currentSlide}"]`).style.display = 'block';
        document.getElementById('slideCounter').textContent = currentSlide + 1;
    }

    function openLightbox(index) {
        currentLightbox = index;
        document.getElementById('lightboxImg').src = fotos[currentLightbox];
        document.getElementById('lightboxCounter').textContent = currentLightbox + 1;
        document.getElementById('lightbox').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
        document.body.style.overflow = '';
    }

    function changeLightbox(dir) {
        currentLightbox = (currentLightbox + dir + fotos.length) % fotos.length;
        document.getElementById('lightboxImg').src = fotos[currentLightbox];
        document.getElementById('lightboxCounter').textContent = currentLightbox + 1;
    }

    // Close lightbox with Escape key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') changeLightbox(-1);
        if (e.key === 'ArrowRight') changeLightbox(1);
    });
</script>
@endif
@endsection
