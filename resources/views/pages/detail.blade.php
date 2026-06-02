@extends('layouts.app')

@section('title', $rumah->nama . ' — RumahKu')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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

    /* ── KPR Simulation ── */
    .kpr-sim-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        border: 1px solid var(--border);
        box-shadow: 0 4px 24px rgba(0,0,0,.04);
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
    }
    .kpr-sim-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f59e0b, #d97706, #b45309);
    }
    .kpr-sim-header {
        display: flex; align-items: center; gap: .8rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1.5px solid var(--border);
    }
    .kpr-sim-header .kpr-icon-box {
        width: 48px; height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    .kpr-sim-header h2 { font-size: 1.3rem; font-weight: 800; color: var(--text-dark); }
    .kpr-sim-header p { font-size: .85rem; color: var(--text-soft); margin-top: .2rem; }

    .kpr-info-box {
        background: linear-gradient(135deg, #fefce8, #fef9c3);
        border: 1px solid #fde68a;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex; align-items: flex-start; gap: .8rem;
    }
    .kpr-info-box .info-icon { font-size: 1.2rem; flex-shrink: 0; }
    .kpr-info-box .info-content h4 { font-size: .88rem; font-weight: 700; color: #92400e; margin-bottom: .3rem; }
    .kpr-info-box .info-content p { font-size: .8rem; color: #a16207; line-height: 1.5; }

    .kpr-price-display {
        background: linear-gradient(135deg, #f0fdfa, #ccfbf1);
        border: 1.5px solid #99f6e4;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex; align-items: center; gap: .8rem;
    }
    .kpr-price-display .price-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        background: var(--primary);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .kpr-price-display .price-label { font-size: .8rem; color: var(--text-soft); font-weight: 600; }
    .kpr-price-display .price-value { font-size: 1.3rem; font-weight: 800; color: var(--primary); }

    .kpr-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .kpr-form-group {
        display: flex; flex-direction: column; gap: .5rem;
    }
    .kpr-form-group label {
        font-size: .85rem; font-weight: 700; color: var(--text-dark);
        display: flex; align-items: center; gap: .4rem;
    }
    .kpr-form-group label .label-icon { font-size: 1rem; }
    .kpr-form-group .input-wrapper { position: relative; }
    .kpr-form-group .input-wrapper .prefix {
        position: absolute;
        left: 14px; top: 50%; transform: translateY(-50%);
        font-size: .85rem; font-weight: 600; color: var(--primary);
        pointer-events: none;
    }
    .kpr-form-group input,
    .kpr-form-group select {
        width: 100%;
        padding: 13px 16px;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        font-size: .95rem;
        font-family: inherit;
        font-weight: 500;
        outline: none;
        transition: all .25s;
        background: #fafafa;
        color: var(--text-dark);
        box-sizing: border-box;
    }
    .kpr-form-group input.has-prefix { padding-left: 42px; }
    .kpr-form-group input:focus,
    .kpr-form-group select:focus {
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(13, 148, 136, .08);
    }
    .kpr-form-group .input-hint {
        font-size: .78rem; color: var(--text-soft); margin-top: .2rem;
    }

    .btn-kpr-sim {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #d97706, #b45309);
        color: white;
        border: none;
        border-radius: 14px;
        font-size: 1rem;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: .6rem;
        transition: all .3s;
        box-shadow: 0 4px 14px rgba(217, 119, 6, .3);
    }
    .btn-kpr-sim:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(217, 119, 6, .4);
    }
    .btn-kpr-sim:active { transform: translateY(0); }

    /* KPR Result */
    .kpr-sim-result {
        display: none;
        margin-top: 2rem;
        animation: kprFadeIn .5s ease;
    }
    .kpr-sim-result.show { display: block; }
    @keyframes kprFadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .kpr-verdict-box {
        border-radius: 18px;
        padding: 2rem;
        text-align: center;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    .kpr-verdict-box::before {
        content: '';
        position: absolute;
        top: -40%; right: -15%;
        width: 250px; height: 250px;
        background: radial-gradient(circle, rgba(255,255,255,.15), transparent 70%);
        border-radius: 50%;
    }
    .kpr-verdict-box.layak {
        background: linear-gradient(135deg, #059669, #047857, #065f46);
        color: white;
    }
    .kpr-verdict-box.tidak-layak {
        background: linear-gradient(135deg, #dc2626, #b91c1c, #991b1b);
        color: white;
    }
    .kpr-verdict-box .v-icon { font-size: 3.5rem; margin-bottom: .6rem; position: relative; z-index: 1; }
    .kpr-verdict-box .v-title { font-size: 1.6rem; font-weight: 800; margin-bottom: .4rem; position: relative; z-index: 1; }
    .kpr-verdict-box .v-desc {
        font-size: .92rem; opacity: .9; max-width: 500px;
        margin: 0 auto; line-height: 1.6; position: relative; z-index: 1;
    }

    .kpr-detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .kpr-detail-card {
        background: white;
        border-radius: 14px;
        padding: 1.25rem;
        border: 1px solid var(--border);
        text-align: center;
        transition: all .25s;
    }
    .kpr-detail-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    .kpr-detail-card .kd-icon { font-size: 1.3rem; margin-bottom: .4rem; }
    .kpr-detail-card .kd-label {
        font-size: .75rem; color: var(--text-soft); font-weight: 600; margin-bottom: .25rem;
    }
    .kpr-detail-card .kd-value { font-size: 1rem; font-weight: 800; color: var(--text-dark); }
    .kpr-detail-card .kd-value.danger { color: #dc2626; }
    .kpr-detail-card .kd-value.success { color: #059669; }
    .kpr-detail-card .kd-value.warning { color: #d97706; }

    /* DSR Gauge */
    .kpr-dsr-gauge {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid var(--border);
        margin-bottom: 1.5rem;
    }
    .kpr-dsr-gauge h4 {
        font-size: .9rem; font-weight: 700; margin-bottom: 1rem;
        display: flex; align-items: center; gap: .4rem;
    }
    .kpr-dsr-bar-wrap {
        background: #f1f5f9;
        border-radius: 10px;
        height: 32px;
        position: relative;
        overflow: hidden;
        margin-bottom: .6rem;
    }
    .kpr-dsr-bar-fill {
        height: 100%;
        border-radius: 10px;
        transition: width .8s ease;
        display: flex; align-items: center; justify-content: flex-end;
        padding-right: .8rem;
        font-size: .78rem; font-weight: 800; color: white;
        min-width: 60px;
    }
    .kpr-dsr-bar-fill.safe { background: linear-gradient(90deg, #22c55e, #16a34a); }
    .kpr-dsr-bar-fill.warning { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .kpr-dsr-bar-fill.danger { background: linear-gradient(90deg, #ef4444, #dc2626); }
    .kpr-dsr-bar-limit {
        position: absolute;
        top: 0; bottom: 0;
        left: 30%;
        width: 2px;
        background: #1e293b;
        z-index: 2;
    }
    .kpr-dsr-bar-limit-label {
        position: absolute;
        top: -22px; left: 30%;
        transform: translateX(-50%);
        font-size: .7rem; font-weight: 700;
        color: #1e293b;
        background: #f1f5f9;
        padding: .1rem .4rem;
        border-radius: 4px;
    }
    .kpr-dsr-legend {
        display: flex; gap: 1.5rem; font-size: .78rem; color: var(--text-soft); font-weight: 600;
        flex-wrap: wrap;
    }
    .kpr-dsr-legend span { display: flex; align-items: center; gap: .3rem; }
    .kpr-dsr-dot {
        width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
    }

    /* Rincian Chips */
    .kpr-rincian-box {
        background: var(--bg-soft);
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid var(--border);
    }
    .kpr-rincian-box h4 {
        font-size: .9rem; font-weight: 700; color: var(--text-dark);
        margin-bottom: 1rem;
        display: flex; align-items: center; gap: .4rem;
    }
    .kpr-chips {
        display: flex; gap: .6rem; flex-wrap: wrap;
    }
    .kpr-chip {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: .5rem .9rem;
        font-size: .82rem;
        font-weight: 600;
        color: var(--text-dark);
        display: flex; align-items: center; gap: .35rem;
    }
    .kpr-chip .chip-icon { font-size: .9rem; }
    .kpr-chip .chip-label { color: var(--text-soft); margin-right: .2rem; }

    @media (max-width: 768px) {
        .detail-page { padding: 5rem 1rem 3rem; }
        .detail-img-wrapper { height: 250px; }
        .detail-specs { grid-template-columns: repeat(2, 1fr); }
        .detail-top { flex-direction: column; }
        .detail-price { text-align: left; }
        .property-grid { grid-template-columns: 1fr; }
        .kpr-sim-card { padding: 1.5rem; }
        .kpr-form-grid { grid-template-columns: 1fr; }
        .kpr-detail-grid { grid-template-columns: 1fr 1fr; }
        .kpr-sim-header { flex-direction: column; align-items: flex-start; }
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

                {{-- Peta Lokasi --}}
                @if($rumah->latitude && $rumah->longitude)
                <div class="detail-desc" style="margin-top: 2rem;">
                    <h3>📍 Lokasi di Peta</h3>
                    <div id="peta" style="height: 350px; width: 100%; border-radius: 14px; margin-top: .8rem;"></div>
                </div>
                @endif

            </div>
        </div>

        {{-- ═══ SIMULASI KPR ═══ --}}
        @auth
        <div class="kpr-sim-card" id="kprSimCard">
            <div class="kpr-sim-header">
                <div class="kpr-icon-box">🏦</div>
                <div>
                    <h2>Simulasi KPR (Kredit Pemilikan Rumah)</h2>
                    <p>Cek apakah kamu layak mengajukan KPR untuk properti ini berdasarkan standar OJK/BI</p>
                </div>
            </div>

            {{-- Info Box --}}
            <div class="kpr-info-box">
                <span class="info-icon">📋</span>
                <div class="info-content">
                    <h4>Standar Kelayakan KPR (OJK/BI)</h4>
                    <p><strong>• LTV (Loan to Value):</strong> Minimal DP 20% dari harga rumah untuk rumah pertama.<br>
                    <strong>• DSR (Debt Service Ratio):</strong> Total cicilan maksimal 30% dari pendapatan kotor bulanan.<br>
                    <strong>• Suku Bunga:</strong> Rata-rata bunga KPR fixed ~8.5% per tahun (bisa disesuaikan).<br>
                    <strong>• Rumus Cicilan:</strong> PMT = P × [r(1+r)ⁿ] / [(1+r)ⁿ - 1]</p>
                </div>
            </div>

            {{-- Harga Properti Ini --}}
            <div class="kpr-price-display">
                <div class="price-icon">🏠</div>
                <div>
                    <div class="price-label">Harga Properti Ini</div>
                    <div class="price-value">Rp {{ number_format($rumah->harga, 0, ',', '.') }}</div>
                </div>
            </div>

            {{-- Form --}}
            <form id="kprSimForm">
                <div class="kpr-form-grid">
                    <div class="kpr-form-group">
                        <label><span class="label-icon">💰</span> Pendapatan Bulanan</label>
                        <div class="input-wrapper">
                            <span class="prefix">Rp</span>
                            <input type="text" id="kprSimPendapatan" class="has-prefix" placeholder="10.000.000" required oninput="formatRupiahKpr(this)">
                        </div>
                        <span class="input-hint">Pendapatan kotor bulanan kamu</span>
                    </div>

                    <div class="kpr-form-group">
                        <label><span class="label-icon">📋</span> Pengeluaran Bulanan</label>
                        <div class="input-wrapper">
                            <span class="prefix">Rp</span>
                            <input type="text" id="kprSimPengeluaran" class="has-prefix" placeholder="5.000.000" required oninput="formatRupiahKpr(this)">
                        </div>
                        <span class="input-hint">Total pengeluaran rutin bulanan (tanpa cicilan)</span>
                    </div>

                    <div class="kpr-form-group">
                        <label><span class="label-icon">💵</span> Uang Muka (DP) %</label>
                        <select id="kprSimDp" required>
                            <option value="20">20% (Minimum OJK)</option>
                            <option value="25">25%</option>
                            <option value="30" selected>30%</option>
                            <option value="35">35%</option>
                            <option value="40">40%</option>
                            <option value="50">50%</option>
                        </select>
                        <span class="input-hint">Minimal 20% sesuai ketentuan OJK untuk rumah pertama</span>
                    </div>

                    <div class="kpr-form-group">
                        <label><span class="label-icon">📅</span> Tenor KPR (Tahun)</label>
                        <select id="kprSimTenor" required>
                            <option value="5">5 Tahun (60 bulan)</option>
                            <option value="10">10 Tahun (120 bulan)</option>
                            <option value="15" selected>15 Tahun (180 bulan)</option>
                            <option value="20">20 Tahun (240 bulan)</option>
                            <option value="25">25 Tahun (300 bulan)</option>
                            <option value="30">30 Tahun (360 bulan)</option>
                        </select>
                        <span class="input-hint">Jangka waktu cicilan KPR</span>
                    </div>

                    <div class="kpr-form-group">
                        <label><span class="label-icon">📊</span> Suku Bunga (% / Tahun)</label>
                        <input type="number" id="kprSimBunga" value="8.5" step="0.1" min="1" max="20" required>
                        <span class="input-hint">Rata-rata bunga KPR bank ~7-10% per tahun</span>
                    </div>
                </div>

                <button type="submit" class="btn-kpr-sim" id="btnKprSim">
                    🧮 Hitung Kelayakan KPR
                </button>
            </form>

            {{-- KPR Result --}}
            <div class="kpr-sim-result" id="kprSimResult">

                {{-- Verdict --}}
                <div class="kpr-verdict-box" id="kprSimVerdict">
                    <div class="v-icon" id="kprSimVIcon">-</div>
                    <div class="v-title" id="kprSimVTitle">-</div>
                    <div class="v-desc" id="kprSimVDesc">-</div>
                </div>

                {{-- Detail Cards --}}
                <div class="kpr-detail-grid">
                    <div class="kpr-detail-card">
                        <div class="kd-icon">🏠</div>
                        <div class="kd-label">Harga Rumah</div>
                        <div class="kd-value" id="kprResHarga">-</div>
                    </div>
                    <div class="kpr-detail-card">
                        <div class="kd-icon">💵</div>
                        <div class="kd-label">Uang Muka (DP)</div>
                        <div class="kd-value" id="kprResDp">-</div>
                    </div>
                    <div class="kpr-detail-card">
                        <div class="kd-icon">💳</div>
                        <div class="kd-label">Pokok Pinjaman</div>
                        <div class="kd-value" id="kprResPinjaman">-</div>
                    </div>
                    <div class="kpr-detail-card">
                        <div class="kd-icon">📅</div>
                        <div class="kd-label">Cicilan / Bulan</div>
                        <div class="kd-value" id="kprResCicilan">-</div>
                    </div>
                    <div class="kpr-detail-card">
                        <div class="kd-icon">📊</div>
                        <div class="kd-label">DSR (Debt Service Ratio)</div>
                        <div class="kd-value" id="kprResDsr">-</div>
                    </div>
                    <div class="kpr-detail-card">
                        <div class="kd-icon">💰</div>
                        <div class="kd-label">Total Bayar (Pokok + Bunga)</div>
                        <div class="kd-value" id="kprResTotalBayar">-</div>
                    </div>
                </div>

                {{-- DSR Gauge --}}
                <div class="kpr-dsr-gauge">
                    <h4>📊 Rasio Cicilan terhadap Pendapatan (DSR)</h4>
                    <div style="position: relative; margin-bottom: .5rem;">
                        <span class="kpr-dsr-bar-limit-label">Batas 30%</span>
                        <div class="kpr-dsr-bar-wrap">
                            <div class="kpr-dsr-bar-fill" id="kprSimDsrBar" style="width: 0%;">0%</div>
                            <div class="kpr-dsr-bar-limit"></div>
                        </div>
                    </div>
                    <div class="kpr-dsr-legend">
                        <span><span class="kpr-dsr-dot" style="background: #22c55e;"></span> Aman (&lt;25%)</span>
                        <span><span class="kpr-dsr-dot" style="background: #f59e0b;"></span> Mendekati Batas (25-30%)</span>
                        <span><span class="kpr-dsr-dot" style="background: #ef4444;"></span> Melebihi Batas (&gt;30%)</span>
                    </div>
                </div>

                {{-- Rincian Keuangan --}}
                <div class="kpr-rincian-box">
                    <h4>💡 Rincian Keuangan Setelah KPR</h4>
                    <div class="kpr-chips" id="kprSimRincian"></div>
                </div>

            </div>
        </div>
        @endauth

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

{{-- Leaflet Maps --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@if($rumah->latitude && $rumah->longitude)
<script>
    var peta = L.map('peta').setView([{{ $rumah->latitude }}, {{ $rumah->longitude }}], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(peta);
    L.marker([{{ $rumah->latitude }}, {{ $rumah->longitude }}])
        .addTo(peta)
        .bindPopup('<b>{{ $rumah->nama }}</b><br>{{ $rumah->lokasi }}')
        .openPopup();
</script>
@endif

{{-- ═══ KPR Simulation Script ═══ --}}
@auth
<script>
    // Rupiah formatter for KPR inputs
    function formatRupiahKpr(input) {
        let raw = input.value.replace(/\D/g, '');
        input.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const kprForm   = document.getElementById('kprSimForm');
        const kprResult = document.getElementById('kprSimResult');

        if (!kprForm) return;

        const HARGA_RUMAH = {{ $rumah->harga }};

        function formatRupiahFull(num) {
            return 'Rp ' + Math.round(num).toLocaleString('id-ID');
        }

        function formatRupiah(num) {
            if (num >= 1000000000) {
                return 'Rp ' + (num / 1000000000).toFixed(1).replace('.0', '') + ' Miliar';
            }
            if (num >= 1000000) {
                return 'Rp ' + (num / 1000000).toFixed(1).replace('.0', '') + ' Juta';
            }
            return 'Rp ' + num.toLocaleString('id-ID');
        }

        kprForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const pendapatan   = parseFloat(document.getElementById('kprSimPendapatan').value.replace(/\./g, '')) || 0;
            const pengeluaran  = parseFloat(document.getElementById('kprSimPengeluaran').value.replace(/\./g, '')) || 0;
            const dpPersen     = parseFloat(document.getElementById('kprSimDp').value) || 30;
            const tenorTahun   = parseInt(document.getElementById('kprSimTenor').value) || 15;
            const bungaTahunan = parseFloat(document.getElementById('kprSimBunga').value) || 8.5;

            if (pendapatan <= 0) {
                alert('Pendapatan harus lebih dari 0.');
                return;
            }
            if (pengeluaran >= pendapatan) {
                alert('Pengeluaran harus lebih kecil dari pendapatan.');
                return;
            }

            // ── Perhitungan KPR ──
            const dpAmount      = HARGA_RUMAH * (dpPersen / 100);
            const pokokPinjaman = HARGA_RUMAH - dpAmount;
            const tenorBulan    = tenorTahun * 12;
            const bungaBulanan  = (bungaTahunan / 100) / 12;

            // Rumus PMT: P × [r(1+r)^n] / [(1+r)^n - 1]
            let cicilanBulanan;
            if (bungaBulanan === 0) {
                cicilanBulanan = pokokPinjaman / tenorBulan;
            } else {
                const factor   = Math.pow(1 + bungaBulanan, tenorBulan);
                cicilanBulanan = pokokPinjaman * (bungaBulanan * factor) / (factor - 1);
            }

            const totalBayar     = cicilanBulanan * tenorBulan;
            const totalBunga     = totalBayar - pokokPinjaman;
            const dsr            = (cicilanBulanan / pendapatan) * 100;
            const isLayak        = dsr <= 30;
            const sisaPendapatan = pendapatan - pengeluaran - cicilanBulanan;

            // ── Verdict ──
            const verdict = document.getElementById('kprSimVerdict');
            verdict.className = 'kpr-verdict-box ' + (isLayak ? 'layak' : 'tidak-layak');
            document.getElementById('kprSimVIcon').textContent  = isLayak ? '✅' : '❌';
            document.getElementById('kprSimVTitle').textContent = isLayak ? 'LAYAK KPR' : 'TIDAK LAYAK KPR';
            document.getElementById('kprSimVDesc').textContent  = isLayak
                ? `Selamat! DSR kamu ${dsr.toFixed(1)}% (di bawah batas 30%). Kamu memenuhi syarat kelayakan KPR berdasarkan standar OJK/BI.`
                : `Maaf, DSR kamu ${dsr.toFixed(1)}% (melebihi batas 30%). Berdasarkan standar OJK/BI, cicilan terlalu besar dibandingkan pendapatan. Coba tambah DP atau perpanjang tenor.`;

            // ── Detail Cards ──
            document.getElementById('kprResHarga').textContent      = formatRupiahFull(HARGA_RUMAH);
            document.getElementById('kprResDp').textContent         = formatRupiahFull(dpAmount) + ` (${dpPersen}%)`;
            document.getElementById('kprResPinjaman').textContent   = formatRupiahFull(pokokPinjaman);

            const cicilanEl   = document.getElementById('kprResCicilan');
            cicilanEl.textContent = formatRupiahFull(cicilanBulanan);
            cicilanEl.className   = 'kd-value ' + (isLayak ? 'success' : 'danger');

            const dsrEl       = document.getElementById('kprResDsr');
            dsrEl.textContent = dsr.toFixed(1) + '%';
            dsrEl.className   = 'kd-value ' + (dsr <= 25 ? 'success' : dsr <= 30 ? 'warning' : 'danger');

            document.getElementById('kprResTotalBayar').textContent = formatRupiahFull(totalBayar);

            // ── DSR Gauge ──
            const dsrBar   = document.getElementById('kprSimDsrBar');
            const dsrWidth = Math.min(dsr, 100);
            dsrBar.style.width = dsrWidth + '%';
            dsrBar.textContent = dsr.toFixed(1) + '%';
            dsrBar.className   = 'kpr-dsr-bar-fill ' + (dsr <= 25 ? 'safe' : dsr <= 30 ? 'warning' : 'danger');

            // ── Rincian Keuangan ──
            document.getElementById('kprSimRincian').innerHTML = `
                <div class="kpr-chip"><span class="chip-icon">💰</span> <span class="chip-label">Pendapatan:</span> ${formatRupiahFull(pendapatan)}</div>
                <div class="kpr-chip"><span class="chip-icon">📋</span> <span class="chip-label">Pengeluaran:</span> ${formatRupiahFull(pengeluaran)}</div>
                <div class="kpr-chip"><span class="chip-icon">🏦</span> <span class="chip-label">Cicilan KPR:</span> ${formatRupiahFull(cicilanBulanan)}</div>
                <div class="kpr-chip"><span class="chip-icon">${sisaPendapatan >= 0 ? '✅' : '⚠️'}</span> <span class="chip-label">Sisa:</span> ${formatRupiahFull(Math.abs(sisaPendapatan))}${sisaPendapatan < 0 ? ' (MINUS!)' : ''}</div>
                <div class="kpr-chip"><span class="chip-icon">📊</span> <span class="chip-label">Bunga/Tahun:</span> ${bungaTahunan}%</div>
                <div class="kpr-chip"><span class="chip-icon">📅</span> <span class="chip-label">Tenor:</span> ${tenorTahun} Tahun</div>
                <div class="kpr-chip"><span class="chip-icon">💸</span> <span class="chip-label">Total Bunga:</span> ${formatRupiah(totalBunga)}</div>
            `;

            // Tampilkan hasil
            kprResult.classList.add('show');
            kprResult.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
</script>
@endauth
@endsection
