@extends('layouts.app')

@section('title', 'Rekomendasi Finansial — RumahKu')

@push('styles')
<style>
    .finansial-page {
        min-height: 100vh;
        padding: 6rem 2rem 4rem;
        background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 50%, #ecfdf5 100%);
    }
    .finansial-container { max-width: 1200px; margin: 0 auto; }

    /* ── Hero Section ── */
    .finansial-hero {
        background: linear-gradient(135deg, #0d9488 0%, #0f766e 40%, #115e59 100%);
        border-radius: 24px;
        padding: 3rem;
        color: white;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
    }
    .finansial-hero::before {
        content: '';
        position: absolute;
        top: -60%;
        right: -15%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    .finansial-hero::after {
        content: '';
        position: absolute;
        bottom: -40%;
        left: -10%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 70%);
        border-radius: 50%;
    }
    .finansial-hero-content { position: relative; z-index: 1; }
    .finansial-hero .breadcrumb {
        display: flex; align-items: center; gap: .5rem;
        font-size: .85rem; opacity: .75; margin-bottom: 1rem;
    }
    .finansial-hero .breadcrumb a { color: white; text-decoration: none; }
    .finansial-hero .breadcrumb a:hover { opacity: 1; text-decoration: underline; }
    .finansial-hero h1 { font-size: 2rem; font-weight: 800; margin-bottom: .6rem; }
    .finansial-hero p { opacity: .85; font-size: 1.05rem; max-width: 600px; line-height: 1.6; }

    /* ── Steps Indicator ── */
    .steps-bar {
        display: flex;
        justify-content: center;
        gap: 0;
        margin-bottom: 2.5rem;
    }
    .step-item {
        display: flex; align-items: center; gap: .6rem;
        padding: .8rem 1.5rem;
        background: white;
        border: 1.5px solid var(--border);
        font-size: .85rem;
        font-weight: 700;
        color: var(--text-soft);
        transition: all .3s;
    }
    .step-item:first-child { border-radius: 14px 0 0 14px; }
    .step-item:last-child { border-radius: 0 14px 14px 0; }
    .step-item .step-num {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: var(--border);
        color: var(--text-soft);
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem; font-weight: 800;
        transition: all .3s;
    }
    .step-item.active {
        background: var(--primary-light);
        border-color: var(--primary);
        color: var(--primary);
    }
    .step-item.active .step-num {
        background: var(--primary);
        color: white;
    }
    .step-item.done {
        background: #dcfce7;
        border-color: #16a34a;
        color: #16a34a;
    }
    .step-item.done .step-num {
        background: #16a34a;
        color: white;
    }

    /* ── Form Card ── */
    .form-card {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        border: 1px solid var(--border);
        box-shadow: 0 4px 24px rgba(0,0,0,.04);
        margin-bottom: 2.5rem;
    }
    .form-card-header {
        display: flex; align-items: center; gap: .8rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1.5px solid var(--border);
    }
    .form-card-header .icon-box {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }
    .form-card-header h2 { font-size: 1.3rem; font-weight: 800; color: var(--text-dark); }
    .form-card-header p { font-size: .85rem; color: var(--text-soft); margin-top: .2rem; }

    /* ── Section Divider ── */
    .form-section-title {
        font-size: .95rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 1rem;
        margin-top: .5rem;
        display: flex;
        align-items: center;
        gap: .5rem;
        padding-bottom: .5rem;
        border-bottom: 1.5px solid var(--border);
    }
    .form-section-title .section-badge {
        background: var(--primary-light);
        color: var(--primary);
        padding: .2rem .7rem;
        border-radius: 6px;
        font-size: .75rem;
        font-weight: 700;
    }

    .fin-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .fin-form-group {
        display: flex; flex-direction: column; gap: .5rem;
    }
    .fin-form-group.full-width {
        grid-column: 1 / -1;
    }
    .fin-form-group label {
        font-size: .85rem; font-weight: 700; color: var(--text-dark);
        display: flex; align-items: center; gap: .4rem;
    }
    .fin-form-group label .label-icon {
        font-size: 1rem;
    }
    .fin-form-group .input-wrapper {
        position: relative;
    }
    .fin-form-group .input-wrapper .prefix {
        position: absolute;
        left: 14px; top: 50%; transform: translateY(-50%);
        font-size: .85rem; font-weight: 600; color: var(--primary);
        pointer-events: none;
    }
    .fin-form-group input,
    .fin-form-group select {
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
    }
    .fin-form-group input.has-prefix {
        padding-left: 42px;
    }
    .fin-form-group input:focus,
    .fin-form-group select:focus {
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(13, 148, 136, .08);
    }
    .fin-form-group .input-hint {
        font-size: .78rem; color: var(--text-soft); margin-top: .2rem;
    }

    /* ── Info Box ── */
    .info-box {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border: 1px solid #bfdbfe;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
        display: flex; align-items: flex-start; gap: .8rem;
    }
    .info-box .info-icon {
        font-size: 1.3rem; flex-shrink: 0; margin-top: .1rem;
    }
    .info-box .info-content h4 {
        font-size: .9rem; font-weight: 700; color: #1e40af; margin-bottom: .3rem;
    }
    .info-box .info-content p {
        font-size: .82rem; color: #3b82f6; line-height: 1.5;
    }

    /* ── Calculate Button ── */
    .btn-calculate {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, var(--primary) 0%, #0f766e 100%);
        color: white;
        border: none;
        border-radius: 14px;
        font-size: 1rem;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: .6rem;
        transition: all .3s;
        box-shadow: 0 4px 14px rgba(13, 148, 136, .3);
    }
    .btn-calculate:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(13, 148, 136, .4);
    }
    .btn-calculate:active { transform: translateY(0); }
    .btn-calculate:disabled {
        opacity: .6; cursor: not-allowed; transform: none;
    }
    .btn-calculate svg { width: 20px; height: 20px; }

    /* ── Spinner ── */
    .spinner {
        width: 20px; height: 20px;
        border: 2.5px solid rgba(255,255,255,.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin .8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── Result Section ── */
    .result-section {
        display: none;
        animation: fadeInUp .5s ease;
    }
    .result-section.show { display: block; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── Budget Summary Card ── */
    .budget-summary {
        background: linear-gradient(135deg, #0d9488, #0f766e, #115e59);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .budget-summary::before {
        content: '';
        position: absolute;
        top: -50%; right: -20%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,.1), transparent 70%);
        border-radius: 50%;
    }
    .budget-summary-content { position: relative; z-index: 1; }
    .budget-summary h3 {
        font-size: 1rem; font-weight: 600; opacity: .85; margin-bottom: .3rem;
    }
    .budget-summary .budget-amount {
        font-size: 2.5rem; font-weight: 800; margin-bottom: 1.5rem;
        text-shadow: 0 2px 10px rgba(0,0,0,.15);
    }
    .budget-detail-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    .budget-detail-item {
        background: rgba(255,255,255,.12);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid rgba(255,255,255,.15);
    }
    .budget-detail-item .label {
        font-size: .75rem; opacity: .7; margin-bottom: .3rem;
    }
    .budget-detail-item .value {
        font-size: 1.1rem; font-weight: 800;
    }

    /* ── ML Cluster Badge ── */
    .cluster-badge-section {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid var(--border);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex-wrap: wrap;
    }
    .cluster-badge {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .6rem 1.2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: .9rem;
    }
    .cluster-badge.ekonomis { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .cluster-badge.premium { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
    .cluster-badge-info {
        flex: 1;
        min-width: 200px;
    }
    .cluster-badge-info h4 { font-size: .9rem; font-weight: 700; color: var(--text-dark); margin-bottom: .2rem; }
    .cluster-badge-info p { font-size: .82rem; color: var(--text-soft); line-height: 1.5; }

    /* ── Criteria Summary ── */
    .criteria-summary {
        background: var(--bg-soft);
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid var(--border);
        margin-bottom: 2rem;
    }
    .criteria-summary h4 {
        font-size: .9rem; font-weight: 700; color: var(--text-dark);
        margin-bottom: 1rem;
        display: flex; align-items: center; gap: .4rem;
    }
    .criteria-chips {
        display: flex; gap: .6rem; flex-wrap: wrap;
    }
    .criteria-chip {
        background: white;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: .5rem .9rem;
        font-size: .82rem;
        font-weight: 600;
        color: var(--text-dark);
        display: flex; align-items: center; gap: .35rem;
    }
    .criteria-chip .chip-icon { font-size: .9rem; }
    .criteria-chip .chip-label { color: var(--text-soft); margin-right: .2rem; }

    /* ── Property Results ── */
    .results-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: .5rem;
    }
    .results-header h2 {
        font-size: 1.3rem; font-weight: 800; color: var(--text-dark);
    }
    .results-header .results-count {
        background: var(--primary-light);
        color: var(--primary);
        padding: .4rem 1rem;
        border-radius: 20px;
        font-size: .85rem;
        font-weight: 700;
    }
    .results-header .fallback-note {
        width: 100%;
        font-size: .82rem;
        color: #b45309;
        background: #fef3c7;
        padding: .5rem 1rem;
        border-radius: 8px;
        border: 1px solid #fde68a;
        font-weight: 600;
    }

    .property-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }

    /* ── Empty State ── */
    .empty-result {
        background: white;
        border-radius: 20px;
        padding: 4rem 2rem;
        text-align: center;
        border: 2px dashed var(--border);
    }
    .empty-result .empty-icon { font-size: 4rem; margin-bottom: 1rem; }
    .empty-result h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: .5rem; }
    .empty-result p { color: var(--text-soft); max-width: 400px; margin: 0 auto; line-height: 1.6; }

    /* ── Error Alert ── */
    .error-alert {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 14px;
        padding: 1rem 1.5rem;
        color: #dc2626;
        font-size: .9rem;
        font-weight: 600;
        display: none;
        margin-bottom: 1.5rem;
        align-items: center;
        gap: .5rem;
    }
    .error-alert.show { display: flex; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .finansial-page { padding: 5rem 1rem 3rem; }
        .finansial-hero { padding: 2rem; }
        .finansial-hero h1 { font-size: 1.5rem; }
        .form-card { padding: 1.5rem; }
        .fin-form-grid { grid-template-columns: 1fr; }
        .budget-summary { padding: 1.5rem; }
        .budget-summary .budget-amount { font-size: 1.8rem; }
        .budget-detail-grid { grid-template-columns: 1fr; }
        .steps-bar { flex-direction: column; }
        .step-item:first-child { border-radius: 14px 14px 0 0; }
        .step-item:last-child { border-radius: 0 0 14px 14px; }
        .property-grid { grid-template-columns: 1fr; }
        .cluster-badge-section { flex-direction: column; align-items: flex-start; }
    }

    /* ── Property Card Styles ── */
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
</style>
@endpush

@section('content')
<section class="finansial-page">
    <div class="finansial-container">

        {{-- ═══ HERO ═══ --}}
        <div class="finansial-hero">
            <div class="finansial-hero-content">
                <div class="breadcrumb">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <span>›</span>
                    <span>Rekomendasi Finansial</span>
                </div>
                <h1>💰 Rekomendasi Rumah Sesuai Kemampuan Finansial</h1>
                <p>Masukkan data keuangan dan kriteria rumah impianmu. Sistem akan menghitung budget ideal dan menggunakan AI untuk menemukan rumah yang paling cocok.</p>
            </div>
        </div>

        {{-- ═══ STEPS ═══ --}}
        <div class="steps-bar">
            <div class="step-item active" id="step1">
                <span class="step-num">1</span>
                <span>Isi Data Keuangan & Kriteria</span>
            </div>
            <div class="step-item" id="step2">
                <span class="step-num">2</span>
                <span>Analisis AI</span>
            </div>
            <div class="step-item" id="step3">
                <span class="step-num">3</span>
                <span>Lihat Rekomendasi</span>
            </div>
        </div>

        {{-- ═══ ERROR ALERT ═══ --}}
        <div class="error-alert" id="errorAlert">
            <span>⚠️</span>
            <span id="errorMessage"></span>
        </div>

        {{-- ═══ FORM CARD ═══ --}}
        <div class="form-card" id="formCard">

            {{-- ── Section 1: Data Keuangan ── --}}
            <div class="form-card-header">
                <div class="icon-box" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">💵</div>
                <div>
                    <h2>Data Keuangan Bulanan</h2>
                    <p>Masukkan informasi pendapatan dan pengeluaran bulanan kamu</p>
                </div>
            </div>

            {{-- ── Info Box ── --}}
            <div class="info-box">
                <span class="info-icon">ℹ️</span>
                <div class="info-content">
                    <h4>Cara Kerja Perhitungan</h4>
                    <p>Budget rumah dihitung dengan rumus: <strong>3 × (Pendapatan Bersih × 12 bulan)</strong>. Lalu sistem menggunakan model <strong>KNN Machine Learning</strong> untuk mengklasifikasi dan mencocokkan rumah sesuai kriteria yang kamu inginkan.</p>
                </div>
            </div>

            <form id="finansialForm">
                @csrf

                <div class="form-section-title">
                    <span>💰</span> Data Keuangan
                    <span class="section-badge">Wajib</span>
                </div>

                <div class="fin-form-grid">
                    <div class="fin-form-group">
                        <label>
                            <span class="label-icon">💰</span>
                            Pendapatan Total / Bulan
                        </label>
                        <div class="input-wrapper">
                            <span class="prefix">Rp</span>
                            <input type="number" id="pendapatan" name="pendapatan" class="has-prefix" placeholder="10000000" step="100000" required min="0" />
                        </div>
                        <span class="input-hint">Total gaji/pendapatan seluruh keluarga per bulan</span>
                    </div>

                    <div class="fin-form-group">
                        <label>
                            <span class="label-icon">📋</span>
                            Total Pengeluaran / Bulan
                        </label>
                        <div class="input-wrapper">
                            <span class="prefix">Rp</span>
                            <input type="number" id="pengeluaran" name="pengeluaran" class="has-prefix" placeholder="5000000" step="100000" required min="0" />
                        </div>
                        <span class="input-hint">Kebutuhan pokok, cicilan, dll (tanpa biaya sewa)</span>
                    </div>
                </div>

                <div class="form-section-title" style="margin-top: 1rem;">
                    <span>🏠</span> Kriteria Rumah Impian
                    <span class="section-badge">Kriteria ML</span>
                </div>

                <div class="fin-form-grid">
                    <div class="fin-form-group">
                        <label>
                            <span class="label-icon">🛏️</span>
                            Kamar Tidur
                        </label>
                        <input type="number" id="kamar_tidur" name="kamar_tidur" placeholder="3" required min="1" max="20" />
                        <span class="input-hint">Jumlah kamar tidur yang diinginkan</span>
                    </div>

                    <div class="fin-form-group">
                        <label>
                            <span class="label-icon">🚿</span>
                            Kamar Mandi
                        </label>
                        <input type="number" id="kamar_mandi" name="kamar_mandi" placeholder="2" required min="1" max="20" />
                        <span class="input-hint">Jumlah kamar mandi yang diinginkan</span>
                    </div>

                    <div class="fin-form-group">
                        <label>
                            <span class="label-icon">📐</span>
                            Luas Tanah (m²)
                        </label>
                        <input type="number" id="luas_tanah" name="luas_tanah" placeholder="120" required min="1" />
                        <span class="input-hint">Luas tanah minimal yang diinginkan</span>
                    </div>

                    <div class="fin-form-group">
                        <label>
                            <span class="label-icon">🏗️</span>
                            Luas Bangunan (m²)
                        </label>
                        <input type="number" id="luas_bangunan" name="luas_bangunan" placeholder="80" required min="1" />
                        <span class="input-hint">Luas bangunan minimal yang diinginkan</span>
                    </div>

                    <div class="fin-form-group">
                        <label>
                            <span class="label-icon">🏙️</span>
                            Kota
                        </label>
                        <select id="kota" name="kota" required>
                            <option value="">— Pilih Kota —</option>
                            <option value="Jakarta">Jakarta</option>
                            <option value="Bogor">Bogor</option>
                            <option value="Depok">Depok</option>
                            <option value="Tangerang">Tangerang</option>
                            <option value="Bekasi">Bekasi</option>
                        </select>
                        <span class="input-hint">Kota lokasi rumah yang diinginkan</span>
                    </div>

                    <div class="fin-form-group">
                        <label>
                            <span class="label-icon">📍</span>
                            Posisi Kota
                        </label>
                        <select id="posisi_kota" name="posisi_kota" required>
                            <option value="">— Pilih Posisi —</option>
                            <option value="Pusat Kota">Pusat Kota</option>
                            <option value="Dekat Pusat Kota">Dekat Pusat Kota</option>
                            <option value="Pinggiran Kota">Pinggiran Kota</option>
                        </select>
                        <span class="input-hint">Posisi area di dalam kota</span>
                    </div>
                </div>

                <button type="submit" class="btn-calculate" id="btnHitung">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                    Hitung & Cari Rekomendasi dengan AI
                </button>
            </form>
        </div>

        {{-- ═══ RESULT SECTION ═══ --}}
        <div class="result-section" id="resultSection">

            {{-- Budget Summary --}}
            <div class="budget-summary">
                <div class="budget-summary-content">
                    <h3>💎 Budget Rumah Ideal Kamu</h3>
                    <div class="budget-amount" id="budgetAmount">-</div>
                    <div class="budget-detail-grid">
                        <div class="budget-detail-item">
                            <div class="label">Pendapatan Bersih / Bulan</div>
                            <div class="value" id="detailBersih">-</div>
                        </div>
                        <div class="budget-detail-item">
                            <div class="label">Pendapatan Bersih / Tahun</div>
                            <div class="value" id="detailTahunan">-</div>
                        </div>
                        <div class="budget-detail-item">
                            <div class="label">Rumus: 3 × Pendapatan Tahunan</div>
                            <div class="value" id="detailRumus">-</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ML Cluster Badge --}}
            <div class="cluster-badge-section" id="clusterSection" style="display: none;">
                <div class="cluster-badge" id="clusterBadge">
                    <span id="clusterIcon">🤖</span>
                    <span id="clusterText">-</span>
                </div>
                <div class="cluster-badge-info">
                    <h4 id="clusterTitle">Hasil Analisis AI</h4>
                    <p id="clusterDescription">-</p>
                </div>
            </div>

            {{-- Criteria Summary --}}
            <div class="criteria-summary" id="criteriaSummary">
                <h4>📝 Kriteria Pencarian</h4>
                <div class="criteria-chips" id="criteriaChips">
                    {{-- Filled dynamically --}}
                </div>
            </div>

            {{-- Property Results --}}
            <div class="results-header" id="resultsHeader">
                <h2>🏠 Rekomendasi Rumah Sesuai Budget & Kriteria</h2>
                <span class="results-count" id="resultsCount">0 Properti</span>
                <div class="fallback-note" id="fallbackNote" style="display: none;"></div>
            </div>

            <div class="property-grid" id="propertyGrid">
                {{-- Filled dynamically --}}
            </div>

            {{-- Empty State --}}
            <div class="empty-result" id="emptyResult" style="display: none;">
                <div class="empty-icon">🏗️</div>
                <h3>Belum Ada Properti yang Sesuai</h3>
                <p>Maaf, saat ini belum ada properti yang sesuai dengan budget dan kriteria kamu. Coba ubah kriteria atau tingkatkan pendapatan untuk melihat lebih banyak pilihan.</p>
            </div>

            {{-- Button Hitung Ulang --}}
            <div style="text-align: center; margin-top: 2rem;">
                <button type="button" class="btn-calculate" id="btnReset" style="max-width: 400px; margin: 0 auto; background: linear-gradient(135deg, #6366f1, #4f46e5);">
                    🔄 Hitung Ulang dengan Kriteria Baru
                </button>
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('finansialForm');
    const btnHitung = document.getElementById('btnHitung');
    const btnReset = document.getElementById('btnReset');
    const resultSection = document.getElementById('resultSection');
    const formCard = document.getElementById('formCard');
    const errorAlert = document.getElementById('errorAlert');
    const errorMessage = document.getElementById('errorMessage');
    const propertyGrid = document.getElementById('propertyGrid');
    const emptyResult = document.getElementById('emptyResult');

    // Steps
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');

    function formatRupiah(num) {
        if (num >= 1000000000) {
            return 'Rp ' + (num / 1000000000).toFixed(1).replace('.0', '') + ' Miliar';
        }
        if (num >= 1000000) {
            return 'Rp ' + (num / 1000000).toFixed(1).replace('.0', '') + ' Juta';
        }
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function formatRupiahFull(num) {
        return 'Rp ' + Math.round(num).toLocaleString('id-ID');
    }

    function setLoading(loading) {
        if (loading) {
            btnHitung.disabled = true;
            btnHitung.innerHTML = '<span class="spinner"></span> Menganalisis dengan AI...';
        } else {
            btnHitung.disabled = false;
            btnHitung.innerHTML = `
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
                Hitung & Cari Rekomendasi dengan AI`;
        }
    }

    function showError(msg) {
        errorMessage.textContent = msg;
        errorAlert.classList.add('show');
        setTimeout(() => errorAlert.classList.remove('show'), 5000);
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        errorAlert.classList.remove('show');

        const pendapatan = parseFloat(document.getElementById('pendapatan').value) || 0;
        const pengeluaran = parseFloat(document.getElementById('pengeluaran').value) || 0;
        const kamar_tidur = parseInt(document.getElementById('kamar_tidur').value) || 0;
        const kamar_mandi = parseInt(document.getElementById('kamar_mandi').value) || 0;
        const luas_tanah = parseFloat(document.getElementById('luas_tanah').value) || 0;
        const luas_bangunan = parseFloat(document.getElementById('luas_bangunan').value) || 0;
        const kota = document.getElementById('kota').value;
        const posisi_kota = document.getElementById('posisi_kota').value;

        if (pendapatan <= 0) {
            showError('Pendapatan harus lebih dari 0.');
            return;
        }
        if (pengeluaran >= pendapatan) {
            showError('Pengeluaran harus lebih kecil dari pendapatan agar ada pendapatan bersih.');
            return;
        }
        if (!kota) {
            showError('Silakan pilih kota.');
            return;
        }
        if (!posisi_kota) {
            showError('Silakan pilih posisi kota.');
            return;
        }

        setLoading(true);

        // Update steps
        step1.classList.remove('active');
        step1.classList.add('done');
        step2.classList.add('active');

        try {
            const response = await fetch('{{ route("rekomendasi.finansial.hitung") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    pendapatan, pengeluaran,
                    kamar_tidur, kamar_mandi,
                    luas_tanah, luas_bangunan,
                    kota, posisi_kota
                }),
            });

            const data = await response.json();

            if (!response.ok || data.status === 'error') {
                showError(data.message || 'Terjadi kesalahan saat menghitung.');
                setLoading(false);
                step2.classList.remove('active');
                step1.classList.remove('done');
                step1.classList.add('active');
                return;
            }

            // Update steps
            step2.classList.remove('active');
            step2.classList.add('done');
            step3.classList.add('active');

            // Fill budget summary
            document.getElementById('budgetAmount').textContent = formatRupiahFull(data.budget);
            document.getElementById('detailBersih').textContent = formatRupiah(data.pendapatan_bersih);
            document.getElementById('detailTahunan').textContent = formatRupiah(data.pendapatan_bersih * 12);
            document.getElementById('detailRumus').textContent = '3 × ' + formatRupiah(data.pendapatan_bersih * 12);

            // Show ML Cluster info
            const clusterSection = document.getElementById('clusterSection');
            if (data.ml_status === 'online' && data.predicted_cluster !== null) {
                clusterSection.style.display = 'flex';
                const badge = document.getElementById('clusterBadge');
                const isEkonomis = data.predicted_cluster === 0;

                badge.className = 'cluster-badge ' + (isEkonomis ? 'ekonomis' : 'premium');
                document.getElementById('clusterIcon').textContent = isEkonomis ? '🏡' : '🏰';
                document.getElementById('clusterText').textContent = 'Cluster ' + data.predicted_cluster + ' — ' + (data.kategori || (isEkonomis ? 'Ekonomis' : 'Premium'));
                document.getElementById('clusterTitle').textContent = 'Analisis AI: Kategori ' + (data.kategori || (isEkonomis ? 'Ekonomis' : 'Premium'));
                document.getElementById('clusterDescription').textContent = isEkonomis
                    ? 'Berdasarkan budget dan kriteria kamu, model KNN merekomendasikan properti kategori Ekonomis — rumah dengan harga terjangkau dan efisien.'
                    : 'Berdasarkan budget dan kriteria kamu, model KNN merekomendasikan properti kategori Premium — rumah dengan lokasi strategis dan spesifikasi tinggi.';
            } else {
                clusterSection.style.display = 'none';
            }

            // Fill criteria chips
            const criteriaChips = document.getElementById('criteriaChips');
            criteriaChips.innerHTML = `
                <div class="criteria-chip"><span class="chip-icon">🛏️</span> <span class="chip-label">KT:</span> ${kamar_tidur}</div>
                <div class="criteria-chip"><span class="chip-icon">🚿</span> <span class="chip-label">KM:</span> ${kamar_mandi}</div>
                <div class="criteria-chip"><span class="chip-icon">📐</span> <span class="chip-label">L. Tanah:</span> ${luas_tanah} m²</div>
                <div class="criteria-chip"><span class="chip-icon">🏗️</span> <span class="chip-label">L. Bangunan:</span> ${luas_bangunan} m²</div>
                <div class="criteria-chip"><span class="chip-icon">🏙️</span> <span class="chip-label">Kota:</span> ${kota}</div>
                <div class="criteria-chip"><span class="chip-icon">📍</span> <span class="chip-label">Posisi:</span> ${posisi_kota}</div>
                <div class="criteria-chip"><span class="chip-icon">💰</span> <span class="chip-label">Budget:</span> ${formatRupiah(data.budget)}</div>
            `;

            // Fallback note
            const fallbackNote = document.getElementById('fallbackNote');
            if (data.is_fallback && data.req_kota) {
                fallbackNote.style.display = 'block';
                fallbackNote.textContent = '⚠️ Belum ada properti yang cocok di ' + data.req_kota + ' dengan kriteria tersebut. Menampilkan properti dari kota lain yang sesuai budget.';
            } else {
                fallbackNote.style.display = 'none';
            }

            // Fill property grid
            document.getElementById('resultsCount').textContent = data.total_rumah + ' Properti';

            if (data.total_rumah > 0) {
                propertyGrid.innerHTML = data.html;
                propertyGrid.style.display = 'grid';
                emptyResult.style.display = 'none';
            } else {
                propertyGrid.style.display = 'none';
                emptyResult.style.display = 'block';
            }

            // Show result, hide form
            formCard.style.display = 'none';
            resultSection.classList.add('show');

            // Scroll to result
            resultSection.scrollIntoView({ behavior: 'smooth', block: 'start' });

        } catch (err) {
            showError('Gagal menghubungi server. Coba lagi nanti.');
            step2.classList.remove('active');
            step1.classList.remove('done');
            step1.classList.add('active');
        }

        setLoading(false);
    });

    // Reset button
    btnReset.addEventListener('click', function() {
        resultSection.classList.remove('show');
        formCard.style.display = 'block';
        propertyGrid.innerHTML = '';
        emptyResult.style.display = 'none';
        document.getElementById('clusterSection').style.display = 'none';
        document.getElementById('fallbackNote').style.display = 'none';

        // Reset steps
        step1.classList.add('active');
        step1.classList.remove('done');
        step2.classList.remove('active', 'done');
        step3.classList.remove('active', 'done');

        // Scroll to form
        formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});
</script>
@endpush
