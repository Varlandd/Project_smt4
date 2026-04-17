@extends('layouts.app')

@section('title', 'Dashboard — RumahKu')

@push('styles')
<style>
    .user-dashboard {
        min-height: 100vh;
        padding: 6rem 2rem 4rem;
        background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 50%, #ecfdf5 100%);
    }
    .dash-container { max-width: 1200px; margin: 0 auto; }

    /* ── Welcome ── */
    .welcome-banner {
        background: linear-gradient(135deg, var(--primary) 0%, #0d9488 50%, var(--accent) 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,.12) 0%, transparent 70%);
        border-radius: 50%;
    }
    .welcome-banner h1 { font-size: 1.8rem; font-weight: 800; margin-bottom: .4rem; }
    .welcome-banner p { opacity: .85; font-size: 1rem; }
    .welcome-nav { display: flex; gap: .8rem; margin-top: 1.5rem; flex-wrap: wrap; }
    .welcome-nav a {
        background: rgba(255,255,255,.2);
        backdrop-filter: blur(10px);
        color: white;
        padding: .6rem 1.3rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: .88rem;
        transition: all .2s;
        border: 1px solid rgba(255,255,255,.25);
    }
    .welcome-nav a:hover { background: rgba(255,255,255,.35); transform: translateY(-1px); }

    /* ── Stats ── */
    .user-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2.5rem;
    }
    .user-stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all .25s;
    }
    .user-stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .user-stat-icon {
        width: 50px; height: 50px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .user-stat-icon svg { width: 24px; height: 24px; }
    .user-stat-number { font-size: 1.5rem; font-weight: 800; color: var(--text-dark); line-height: 1.2; }
    .user-stat-label { font-size: .78rem; color: var(--text-soft); font-weight: 600; margin-top: .1rem; }

    /* ── Section ── */
    .dash-section { margin-bottom: 2.5rem; }
    .dash-section-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 1.25rem;
    }
    .dash-section-header h2 { font-size: 1.3rem; font-weight: 800; color: var(--text-dark); }
    .dash-section-header a {
        color: var(--primary); text-decoration: none; font-weight: 700; font-size: .9rem;
        transition: color .2s;
    }
    .dash-section-header a:hover { color: var(--primary-dark); }

    /* ── Property Cards Grid ── */
    .property-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }
    .property-card {
        background: white;
        border-radius: 18px;
        overflow: hidden;
        border: 1px solid var(--border);
        transition: all .3s;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .property-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); border-color: var(--primary); }
    .property-card-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        display: flex; align-items: center; justify-content: center;
        font-size: 3rem; color: var(--text-soft);
        position: relative;
    }
    .property-card-img img { width: 100%; height: 100%; object-fit: cover; }
    .property-card-body { padding: 1.3rem; flex: 1; display: flex; flex-direction: column; }
    .property-card-type {
        display: inline-block;
        background: var(--primary-light);
        color: var(--primary);
        padding: .2rem .7rem;
        border-radius: 6px;
        font-size: .75rem;
        font-weight: 700;
        margin-bottom: .6rem;
        width: fit-content;
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
    .property-card-bottom {
        display: flex; justify-content: space-between; align-items: center;
        margin-top: auto;
    }
    .property-card-price { font-size: 1.15rem; font-weight: 800; color: var(--primary); }
    .btn-fav {
        width: 36px; height: 36px; border-radius: 10px; border: 1.5px solid var(--border);
        background: white; cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: all .2s;
    }
    .btn-fav:hover { border-color: #ef4444; background: #fef2f2; }
    .btn-fav svg { width: 18px; height: 18px; }
    .btn-fav.active { background: #fef2f2; border-color: #ef4444; }
    .btn-fav.active svg { fill: #ef4444; stroke: #ef4444; }

    /* ── Empty State ── */
    .empty-card {
        background: white; border-radius: 18px; padding: 3rem 2rem;
        border: 2px dashed var(--border); text-align: center;
        grid-column: 1 / -1;
    }
    .empty-card-icon { font-size: 3rem; margin-bottom: 1rem; }
    .empty-card h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: .4rem; }
    .empty-card p { color: var(--text-soft); font-size: .9rem; margin-bottom: 1rem; }

    /* ── Calculator (embedded) ── */
    .calc-section {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid var(--border);
    }
    .calc-section h2 { font-size: 1.3rem; font-weight: 800; margin-bottom: 1.5rem; }

    @media (max-width: 768px) {
        .user-dashboard { padding: 5rem 1rem 3rem; }
        .welcome-banner { padding: 1.5rem; }
        .welcome-banner h1 { font-size: 1.4rem; }
        .property-grid { grid-template-columns: 1fr; }
        .user-stats-grid { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('content')
<section class="user-dashboard">
    <div class="dash-container">

        {{-- ═══ WELCOME BANNER ═══ --}}
        <div class="welcome-banner">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
                <div>
                    <h1>Selamat Datang, {{ Auth::user()->name }} 👋</h1>
                    <p>Temukan rumah impian kamu dengan sistem rekomendasi cerdas RumahKu.</p>
                </div>
                <a href="{{ route('rekomendasi.wizard') }}" class="btn btn-white" style="border-radius: 12px; padding: .8rem 1.8rem;">
                    🎯 Temukan Rumah Impian (4 Langkah)
                </a>
            </div>
            <div class="welcome-nav" style="margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 1.5rem;">
                <a href="{{ route('properti.browse') }}">🏠 Jelajahi Properti</a>
                <a href="{{ route('favorit.index') }}">❤️ Favorit Saya</a>
                <a href="{{ route('prediksi') }}">📊 Prediksi Harga</a>
                <a href="{{ route('rekomendasi') }}">🎯 Rekomendasi</a>
                <a href="{{ route('bandingkan') }}">⚖️ Bandingkan</a>
            </div>
        </div>

        {{-- ═══ SUCCESS/FLASH ═══ --}}
        @if(session('success'))
            <div class="form-success">{{ session('success') }}</div>
        @endif

        {{-- ═══ STATS ═══ --}}
        <div class="user-stats-grid">
            <div class="user-stat-card">
                <div class="user-stat-icon" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#1e40af" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <div>
                    <div class="user-stat-number">{{ number_format($totalRumah) }}</div>
                    <div class="user-stat-label">Total Properti</div>
                </div>
            </div>

            <div class="user-stat-card">
                <div class="user-stat-icon" style="background: linear-gradient(135deg, #fce7f3, #fbcfe8);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#be185d" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </div>
                <div>
                    <div class="user-stat-number">{{ $totalFavorit }}</div>
                    <div class="user-stat-label">Favorit Saya</div>
                </div>
            </div>

            <div class="user-stat-card">
                <div class="user-stat-icon" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>
                <div>
                    <div class="user-stat-number">{{ $totalLokasi }}</div>
                    <div class="user-stat-label">Wilayah Tersedia</div>
                </div>
            </div>

            <div class="user-stat-card">
                <div class="user-stat-icon" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <div>
                    <div class="user-stat-number">Rp {{ number_format($avgHarga / 1000000, 0, ',', '.') }} Jt</div>
                    <div class="user-stat-label">Rata-rata Harga</div>
                </div>
            </div>
        </div>

        {{-- ═══ PROPERTI TERBARU ═══ --}}
        <div class="dash-section">
            <div class="dash-section-header">
                <h2>🏠 Properti Terbaru</h2>
                <a href="{{ route('properti.browse') }}">Lihat Semua →</a>
            </div>

            <div class="property-grid">
                @forelse($latestRumah as $rumah)
                    @include('components.property-card', ['rumah' => $rumah])
                @empty
                    <div class="empty-card">
                        <div class="empty-card-icon">🏗️</div>
                        <h3>Belum Ada Properti</h3>
                        <p>Properti akan segera ditambahkan oleh admin.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ═══ FAVORIT SAYA (Preview) ═══ --}}
        <div class="dash-section">
            <div class="dash-section-header">
                <h2>❤️ Favorit Saya</h2>
                <a href="{{ route('favorit.index') }}">Lihat Semua →</a>
            </div>

            <div class="property-grid">
                @forelse($favoritRumah as $rumah)
                    @include('components.property-card', ['rumah' => $rumah])
                @empty
                    <div class="empty-card">
                        <div class="empty-card-icon">💝</div>
                        <h3>Belum Ada Favorit</h3>
                        <p>Jelajahi properti dan tandai yang kamu suka!</p>
                        <a href="{{ route('properti.browse') }}" class="btn btn-primary" style="display:inline-flex;">Jelajahi Properti</a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ═══ KALKULATOR BUDGET ═══ --}}
        <div class="dash-section" id="calculator">
            <div class="calc-section">
                <h2>🧮 Kalkulator Budget KPR</h2>
                <form id="calculatorForm" class="calculator-form">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="penghasilan">Penghasilan Keluarga/Bulan</label>
                            <div class="input-with-icon">
                                <span class="input-prefix">Rp</span>
                                <input type="number" id="penghasilan" name="penghasilan" placeholder="10.000.000" step="100000" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uang_muka">Uang Muka Tersedia</label>
                            <div class="input-with-icon">
                                <span class="input-prefix">Rp</span>
                                <input type="number" id="uang_muka" name="uang_muka" placeholder="50.000.000" step="1000000"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cicilan_lain">Cicilan Bulanan Lain</label>
                            <div class="input-with-icon">
                                <span class="input-prefix">Rp</span>
                                <input type="number" id="cicilan_lain" name="cicilan_lain" placeholder="2.000.000" step="100000"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tenor">Tenor KPR (Tahun)</label>
                            <select id="tenor" name="tenor">
                                <option value="5">5 Tahun</option>
                                <option value="10">10 Tahun</option>
                                <option value="15" selected>15 Tahun</option>
                                <option value="20">20 Tahun</option>
                            </select>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary btn-block" id="hitungBtn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 11 12 14 22 4"/>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                        Hitung Kemampuan Saya
                    </button>
                </form>

                <div class="calculator-result" id="calculatorResult" style="display: none;">
                    <div class="result-header">
                        <h3>📊 Hasil Analisis Budget</h3>
                    </div>
                    <div class="result-grid">
                        <div class="result-item">
                            <div class="result-label">Budget Rumah</div>
                            <div class="result-value" id="resultBudget">-</div>
                        </div>
                        <div class="result-item">
                            <div class="result-label">Cicilan/Bulan</div>
                            <div class="result-value" id="resultCicilan">-</div>
                        </div>
                        <div class="result-item">
                            <div class="result-label">Sisa Pendapatan</div>
                            <div class="result-value" id="resultSisa">-</div>
                        </div>
                    </div>
                    <div class="result-action">
                        <a href="{{ route('properti.browse') }}" class="btn btn-primary">
                            Cari Rumah Sesuai Budget →
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
