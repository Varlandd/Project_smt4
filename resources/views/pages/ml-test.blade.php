@extends('layouts.app')

@section('title', 'Test Machine Learning — RumahKu')

@push('styles')
<style>
    .ml-test-page {
        min-height: 100vh;
        padding: 6rem 2rem 4rem;
        background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 50%, #ecfdf5 100%);
    }
    .ml-container { max-width: 900px; margin: 0 auto; }

    /* ── Header ── */
    .ml-header { text-align: center; margin-bottom: 2.5rem; }
    .ml-header-badge {
        display: inline-flex; align-items: center; gap: .5rem;
        background: linear-gradient(135deg, #dbeafe, #c7d2fe);
        color: #4338ca; padding: .5rem 1.2rem; border-radius: 50px;
        font-size: .82rem; font-weight: 700; margin-bottom: 1rem;
        border: 1px solid #c7d2fe;
    }
    .ml-header h1 {
        font-size: 2rem; font-weight: 800; margin-bottom: .5rem;
        background: linear-gradient(135deg, var(--primary), #4338ca);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .ml-header p { color: var(--text-soft); font-size: .95rem; max-width: 600px; margin: 0 auto; line-height: 1.7; }

    /* ── Service Status ── */
    .ml-status-bar {
        display: flex; align-items: center; justify-content: center; gap: .8rem;
        background: white; border: 1px solid var(--border);
        border-radius: 14px; padding: .8rem 1.5rem; margin-bottom: 2rem;
        font-size: .88rem; font-weight: 600; color: var(--text-soft);
        transition: all .3s;
    }
    .ml-status-bar.online { border-color: #bbf7d0; background: #f0fdf4; color: #15803d; }
    .ml-status-bar.offline { border-color: #fecaca; background: #fef2f2; color: #dc2626; }
    .ml-status-bar.checking { border-color: #fde68a; background: #fefce8; color: #a16207; }
    .status-dot {
        width: 10px; height: 10px; border-radius: 50%;
        background: var(--text-light); flex-shrink: 0;
    }
    .ml-status-bar.online .status-dot { background: #22c55e; box-shadow: 0 0 8px rgba(34,197,94,.5); animation: pulse-dot 2s ease-in-out infinite; }
    .ml-status-bar.offline .status-dot { background: #ef4444; }
    .ml-status-bar.checking .status-dot { background: #f59e0b; animation: pulse-dot 1s ease-in-out infinite; }
    @keyframes pulse-dot { 0%, 100% { opacity: 1; } 50% { opacity: .4; } }

    /* ── Card ── */
    .ml-card {
        background: white; border-radius: 20px; padding: 2rem;
        border: 1px solid var(--border); box-shadow: var(--shadow-md);
        margin-bottom: 2rem; position: relative; overflow: hidden;
    }
    .ml-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
        background: linear-gradient(90deg, var(--primary), #4338ca, var(--accent));
    }
    .ml-card h2 {
        font-size: 1.2rem; font-weight: 700; margin-bottom: .4rem;
        display: flex; align-items: center; gap: .6rem;
    }
    .ml-card .card-desc {
        font-size: .85rem; color: var(--text-soft); margin-bottom: 1.8rem; line-height: 1.6;
    }

    /* ── Form Grid ── */
    .ml-form-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .ml-form-group { }
    .ml-form-group.full-width { grid-column: 1 / -1; }
    .ml-form-group label {
        display: flex; align-items: center; gap: .4rem;
        font-size: .86rem; font-weight: 700; color: var(--text-dark);
        margin-bottom: .45rem;
    }
    .ml-form-group label .label-icon { font-size: 1rem; }
    .ml-form-group input,
    .ml-form-group select {
        width: 100%; padding: .8rem 1rem;
        border: 2px solid var(--border); border-radius: 12px;
        font-size: .92rem; font-family: inherit; color: var(--text-dark);
        background: white; transition: all .25s;
    }
    .ml-form-group input:focus,
    .ml-form-group select:focus {
        outline: none; border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(15,118,110,.08);
    }
    .ml-form-group input::placeholder { color: var(--text-light); }
    .input-hint {
        font-size: .76rem; color: var(--text-light); margin-top: .3rem;
        font-weight: 500;
    }

    /* ── Harga Input ── */
    .harga-input-wrap { position: relative; }
    .harga-input-wrap .prefix {
        position: absolute; left: 1rem; top: 50%; transform: translateY(-50%);
        font-weight: 700; color: var(--primary); font-size: .88rem;
    }
    .harga-input-wrap input { padding-left: 2.8rem; }

    /* ── Submit Button ── */
    .ml-submit-btn {
        width: 100%; padding: 1rem 2rem; border: none; border-radius: 14px;
        background: linear-gradient(135deg, var(--primary), #0d9488);
        color: white; font-size: 1rem; font-weight: 700;
        font-family: inherit; cursor: pointer; transition: all .3s;
        display: flex; align-items: center; justify-content: center; gap: .6rem;
        box-shadow: 0 6px 24px rgba(15,118,110,.3);
    }
    .ml-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 32px rgba(15,118,110,.4);
    }
    .ml-submit-btn:disabled {
        opacity: .6; cursor: not-allowed; transform: none;
        box-shadow: none;
    }
    .ml-submit-btn .btn-spinner {
        width: 20px; height: 20px; border: 3px solid rgba(255,255,255,.3);
        border-top: 3px solid white; border-radius: 50%;
        animation: spin .8s linear infinite; display: none;
    }
    .ml-submit-btn.loading .btn-spinner { display: block; }
    .ml-submit-btn.loading .btn-text { display: none; }
    .ml-submit-btn.loading .btn-loading-text { display: inline; }
    .btn-loading-text { display: none; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── Error ── */
    .ml-error {
        background: #fef2f2; border: 1.5px solid #fecaca;
        color: #dc2626; padding: 1rem 1.2rem; border-radius: 14px;
        display: none; margin-bottom: 1.5rem; font-size: .9rem;
        font-weight: 600; align-items: center; gap: .6rem;
    }
    .ml-error.show { display: flex; }

    /* ── Result Section ── */
    .ml-result {
        display: none; animation: fadeSlideUp .5s ease;
    }
    .ml-result.show { display: block; }
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .result-cluster-hero {
        text-align: center; padding: 2.5rem 2rem;
        background: linear-gradient(135deg, var(--primary) 0%, #0d9488 50%, #4338ca 100%);
        border-radius: 20px; color: white; margin-bottom: 1.5rem;
        position: relative; overflow: hidden;
    }
    .result-cluster-hero::before {
        content: ''; position: absolute; top: -30%; right: -15%;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    .result-cluster-hero::after {
        content: ''; position: absolute; bottom: -20%; left: -10%;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,.08) 0%, transparent 70%);
        border-radius: 50%;
    }
    .cluster-label {
        font-size: .85rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .1em; opacity: .8; margin-bottom: .6rem;
    }
    .cluster-number {
        font-size: 4rem; font-weight: 900; line-height: 1;
        margin-bottom: .6rem; position: relative; z-index: 1;
    }
    .cluster-name {
        font-size: 1.4rem; font-weight: 700; margin-bottom: .3rem;
        position: relative; z-index: 1;
    }
    .cluster-desc {
        font-size: .9rem; opacity: .85; max-width: 500px;
        margin: 0 auto; line-height: 1.6; position: relative; z-index: 1;
    }

    /* ── Cluster Info Cards ── */
    .cluster-info-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 1rem; margin-bottom: 1.5rem;
    }
    .cluster-info-item {
        background: white; border-radius: 14px; padding: 1.2rem;
        border: 1px solid var(--border); text-align: center;
        transition: all .25s;
    }
    .cluster-info-item:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    .cluster-info-icon { font-size: 1.5rem; margin-bottom: .4rem; }
    .cluster-info-label {
        font-size: .78rem; color: var(--text-soft); font-weight: 600;
        margin-bottom: .2rem;
    }
    .cluster-info-value {
        font-size: 1.1rem; font-weight: 800; color: var(--text-dark);
    }

    /* ── Input Summary ── */
    .input-summary {
        background: var(--bg-soft); border-radius: 16px; padding: 1.5rem;
        border: 1px solid var(--border);
    }
    .input-summary h3 {
        font-size: .95rem; font-weight: 700; margin-bottom: 1rem;
        color: var(--text-dark); display: flex; align-items: center; gap: .4rem;
    }
    .summary-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: .8rem;
    }
    .summary-item {
        background: white; padding: .7rem .9rem; border-radius: 10px;
        border: 1px solid var(--border);
    }
    .summary-item-label {
        font-size: .75rem; color: var(--text-soft); font-weight: 600;
        margin-bottom: .15rem;
    }
    .summary-item-value {
        font-size: .92rem; font-weight: 700; color: var(--text-dark);
    }

    /* ── Cluster Colors ── */
    .cluster-0 { background: linear-gradient(135deg, #059669, #047857, #10b981) !important; }
    .cluster-1 { background: linear-gradient(135deg, #2563eb, #1d4ed8, #3b82f6) !important; }
    .cluster-2 { background: linear-gradient(135deg, #d97706, #b45309, #f59e0b) !important; }
    .cluster-3 { background: linear-gradient(135deg, #7c3aed, #6d28d9, #8b5cf6) !important; }
    .cluster-4 { background: linear-gradient(135deg, #dc2626, #b91c1c, #ef4444) !important; }

    /* ── How It Works ── */
    .ml-how {
        background: white; border-radius: 20px; padding: 2rem;
        border: 1px solid var(--border); margin-top: 2rem;
    }
    .ml-how h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 1.2rem; display: flex; align-items: center; gap: .5rem; }
    .how-steps {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    .how-step {
        text-align: center; padding: 1.2rem;
        background: var(--bg-soft); border-radius: 14px;
        border: 1px solid var(--border);
    }
    .how-step-num {
        width: 36px; height: 36px; border-radius: 10px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: white; font-weight: 800; font-size: .9rem;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto .7rem;
    }
    .how-step-title { font-size: .88rem; font-weight: 700; margin-bottom: .2rem; color: var(--text-dark); }
    .how-step-desc { font-size: .8rem; color: var(--text-soft); line-height: 1.5; }

    /* ── Property Cards (from components) ── */
    .property-card {
        background: white; border-radius: 18px; overflow: hidden;
        border: 1px solid var(--border); transition: all .3s;
        text-decoration: none; color: inherit; display: flex;
        flex-direction: column; position: relative;
    }
    .property-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); border-color: var(--primary); }
    .property-card-img {
        width: 100%; height: 200px; object-fit: cover; background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        display: flex; align-items: center; justify-content: center; font-size: 3rem; color: var(--text-soft); position: relative;
    }
    .property-card-img img { width: 100%; height: 100%; object-fit: cover; }
    .property-card-body { padding: 1.3rem; flex: 1; display: flex; flex-direction: column; }
    .property-card-type {
        display: inline-block; background: var(--primary-light); color: var(--primary);
        padding: .2rem .7rem; border-radius: 6px; font-size: .75rem; font-weight: 700; margin-bottom: .6rem; width: fit-content;
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
        width: 36px; height: 36px; border-radius: 10px; border: 1.5px solid var(--border); background: white;
        cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all .2s;
    }
    .btn-fav:hover { border-color: #ef4444; background: #fef2f2; }
    .btn-fav svg { width: 18px; height: 18px; }
    .btn-fav.active { background: #fef2f2; border-color: #ef4444; }
    .btn-fav.active svg { fill: #ef4444; stroke: #ef4444; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .ml-test-page { padding: 5rem 1rem 3rem; }
        .ml-form-grid { grid-template-columns: 1fr; }
        .cluster-info-grid { grid-template-columns: 1fr; }
        .cluster-number { font-size: 3rem; }
        .how-steps { grid-template-columns: 1fr; }
        .ml-header h1 { font-size: 1.5rem; }
    }
</style>
@endpush

@section('content')
<section class="ml-test-page">
    <div class="ml-container">

        {{-- ═══ HEADER ═══ --}}
        <div class="ml-header">
            <div class="ml-header-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
                KNN Clustering Model
            </div>
            <h1>🧪 Rekomendasi Rumah</h1>
            <p>Uji model KNN (K-Nearest Neighbors) untuk mengklasifikasi properti ke dalam cluster berdasarkan spesifikasi rumah yang kamu masukkan.</p>
        </div>

        {{-- ═══ SERVICE STATUS ═══ --}}
        <div class="ml-status-bar checking" id="mlStatusBar">
            <div class="status-dot"></div>
            <span id="mlStatusText">Memeriksa koneksi Flask ML Service...</span>
        </div>

        {{-- ═══ INPUT FORM ═══ --}}
        <div class="ml-card">
            <h2>📋 Input Data Properti</h2>
            <p class="card-desc">Masukkan data properti untuk diproses oleh model KNN. Data akan di-scale menggunakan StandardScaler dan di-encode menggunakan One-Hot Encoding sebelum diprediksi.</p>

            <form id="mlTestForm">
                <div class="ml-form-grid">
                    {{-- Harga --}}
                    <div class="ml-form-group">
                        <label><span class="label-icon">💰</span> Harga (Rp)</label>
                        <div class="harga-input-wrap">
                            <span class="prefix">Rp</span>
                            <input type="text" id="ml_harga" name="harga" placeholder="500.000.000" required oninput="formatRupiahML(this)">
                        </div>
                        <div class="input-hint">Contoh: 500000000 untuk Rp 500 Juta</div>
                    </div>

                    {{-- Luas Tanah --}}
                    <div class="ml-form-group">
                        <label><span class="label-icon">📐</span> Luas Tanah (m²)</label>
                        <input type="number" id="ml_luas_tanah" name="luas_tanah" placeholder="120" required min="1">
                    </div>

                    {{-- Luas Bangunan --}}
                    <div class="ml-form-group">
                        <label><span class="label-icon">🏗️</span> Luas Bangunan (m²)</label>
                        <input type="number" id="ml_luas_bangunan" name="luas_bangunan" placeholder="80" required min="1">
                    </div>

                    {{-- Kamar Tidur --}}
                    <div class="ml-form-group">
                        <label><span class="label-icon">🛏️</span> Kamar Tidur</label>
                        <input type="number" id="ml_kamar_tidur" name="kamar_tidur" placeholder="3" required min="1" max="20">
                    </div>

                    {{-- Kamar Mandi --}}
                    <div class="ml-form-group">
                        <label><span class="label-icon">🚿</span> Kamar Mandi</label>
                        <input type="number" id="ml_kamar_mandi" name="kamar_mandi" placeholder="2" required min="1" max="20">
                    </div>

                    {{-- Kota --}}
                    <div class="ml-form-group">
                        <label><span class="label-icon">🏙️</span> Kota</label>
                        <select id="ml_kota" name="kota" required>
                            <option value="">— Pilih Kota —</option>
                            <option value="Jakarta">Jakarta</option>
                            <option value="Bogor">Bogor</option>
                            <option value="Depok">Depok</option>
                            <option value="Tangerang">Tangerang</option>
                            <option value="Bekasi">Bekasi</option>
                        </select>
                    </div>

                    {{-- Posisi Kota --}}
                    <div class="ml-form-group full-width">
                        <label><span class="label-icon">📍</span> Posisi Kota</label>
                        <select id="ml_posisi_kota" name="posisi_kota" required>
                            <option value="">— Pilih Posisi —</option>
                            <option value="Pusat Kota">Pusat Kota</option>
                            <option value="Dekat Pusat Kota">Dekat Pusat Kota</option>
                            <option value="Pinggiran Kota">Pinggiran Kota</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="ml-submit-btn" id="mlSubmitBtn">
                    <div class="btn-spinner"></div>
                    <span class="btn-text">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                        Jalankan Prediksi KNN
                    </span>
                    <span class="btn-loading-text">Memproses dengan ML Model...</span>
                </button>
            </form>
        </div>

        {{-- ═══ ERROR ═══ --}}
        <div class="ml-error" id="mlError">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span id="mlErrorText"></span>
        </div>

        {{-- ═══ RESULT ═══ --}}
        <div class="ml-result" id="mlResult">
            {{-- Cluster Hero --}}
            <div class="result-cluster-hero" id="clusterHero">
                <div class="cluster-label">Hasil Prediksi Cluster</div>
                <div class="cluster-number" id="clusterNumber">—</div>
                <div class="cluster-name" id="clusterName">—</div>
                <div class="cluster-desc" id="clusterDesc">—</div>
            </div>

            {{-- Cluster Info --}}
            <div class="cluster-info-grid" id="clusterInfoGrid">
                <div class="cluster-info-item">
                    <div class="cluster-info-icon">🏷️</div>
                    <div class="cluster-info-label">Kategori</div>
                    <div class="cluster-info-value" id="infoKategori">—</div>
                </div>
                <div class="cluster-info-item">
                    <div class="cluster-info-icon">📊</div>
                    <div class="cluster-info-label">Model</div>
                    <div class="cluster-info-value">KNN</div>
                </div>
                <div class="cluster-info-item">
                    <div class="cluster-info-icon">⚡</div>
                    <div class="cluster-info-label">Status</div>
                    <div class="cluster-info-value" id="infoStatus" style="color: #15803d;">Sukses</div>
                </div>
            </div>

            {{-- Input Summary --}}
            <div class="input-summary">
                <h3>📝 Ringkasan Input</h3>
                <div class="summary-grid" id="summaryGrid">
                    <!-- filled by JS -->
                </div>
            </div>
            
            {{-- Rekomendasi Properti --}}
            <div id="mlRecommendations" style="margin-top: 2rem; display: none;">
                <h3 id="recTitle" style="font-size: 1.15rem; font-weight: 800; margin-bottom: 1rem; color: var(--text-dark); display: flex; align-items: center; flex-wrap: wrap;">
                    🏠 Rekomendasi Properti di Kategori Ini
                </h3>
                <div id="recommendationsGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem;">
                    <!-- filled by JS -->
                </div>
            </div>
        </div>

        {{-- ═══ HOW IT WORKS ═══ --}}
        <div class="ml-how">
            <h3>🧠 Bagaimana Model Ini Bekerja?</h3>
            <div class="how-steps">
                <div class="how-step">
                    <div class="how-step-num">1</div>
                    <div class="how-step-title">Input Data</div>
                    <div class="how-step-desc">Data properti dikirim ke Flask API berupa harga, luas, kamar, kota, dan posisi.</div>
                </div>
                <div class="how-step">
                    <div class="how-step-num">2</div>
                    <div class="how-step-title">One-Hot Encoding</div>
                    <div class="how-step-desc">Fitur kategorikal (kota, posisi) diubah menjadi variabel binary menggunakan One-Hot Encoding.</div>
                </div>
                <div class="how-step">
                    <div class="how-step-num">3</div>
                    <div class="how-step-title">StandardScaler</div>
                    <div class="how-step-desc">Data numerik di-scale agar fitur memiliki distribusi yang seimbang untuk model.</div>
                </div>
                <div class="how-step">
                    <div class="how-step-num">4</div>
                    <div class="how-step-title">Prediksi KNN</div>
                    <div class="how-step-desc">Model KNN mencari tetangga terdekat dan menentukan cluster properti berdasarkan kesamaan fitur.</div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
function formatRupiahML(input) {
    let raw = input.value.replace(/\D/g, '');
    input.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
}
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('mlTestForm');
    const resultEl = document.getElementById('mlResult');
    const errorEl = document.getElementById('mlError');
    const errorText = document.getElementById('mlErrorText');
    const submitBtn = document.getElementById('mlSubmitBtn');
    const statusBar = document.getElementById('mlStatusBar');
    const statusText = document.getElementById('mlStatusText');

    // ── Cluster Interpretation ──
    const clusterInfo = {
        0: {
            name: 'Properti Ekonomis',
            desc: 'Properti dengan harga terjangkau dan efisien. Cocok untuk keluarga muda, first-time buyer, atau investasi awal. Rata-rata harga di bawah 2 Miliar.',
            kategori: 'Ekonomis',
            color: 'cluster-0'
        },
        1: {
            name: 'Properti Premium',
            desc: 'Properti kelas premium dengan harga di atas rata-rata. Lokasi strategis, spesifikasi tinggi, dan fasilitas lengkap. Rata-rata harga di atas 2 Miliar.',
            kategori: 'Premium',
            color: 'cluster-1'
        }
    };

    // Default fallback for unknown clusters
    function getClusterInfo(cluster) {
        return clusterInfo[cluster] || {
            name: `Cluster ${cluster}`,
            desc: `Properti termasuk dalam cluster ${cluster} berdasarkan karakteristik yang serupa dengan data training.`,
            kategori: `Cluster ${cluster}`,
            color: 'cluster-0'
        };
    }

    // ── Check ML Service Status ──
    async function checkServiceStatus() {
        statusBar.className = 'ml-status-bar checking';
        statusText.textContent = 'Memeriksa koneksi Flask ML Service...';

        try {
            const response = await fetch('{{ route("ml.test.check") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });
            const result = await response.json();

            if (result.status === 'online') {
                statusBar.className = 'ml-status-bar online';
                statusText.textContent = 'Flask ML Service Online — 127.0.0.1:5000';
            } else {
                statusBar.className = 'ml-status-bar offline';
                statusText.textContent = 'Flask ML Service Offline — Jalankan: cd ml_service && python app.py';
            }
        } catch (err) {
            statusBar.className = 'ml-status-bar offline';
            statusText.textContent = 'Flask ML Service Offline — Jalankan: cd ml_service && python app.py';
        }
    }

    checkServiceStatus();

    // ── Format Rupiah ──
    const formatRp = (num) => 'Rp ' + Math.round(num).toLocaleString('id-ID');

    // ── Form Submit ──
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Reset
        resultEl.classList.remove('show');
        errorEl.classList.remove('show');
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        const data = {
            harga: parseFloat(document.getElementById('ml_harga').value.replace(/\./g, '')),
            luas_tanah: parseFloat(document.getElementById('ml_luas_tanah').value),
            luas_bangunan: parseFloat(document.getElementById('ml_luas_bangunan').value),
            kamar_tidur: parseInt(document.getElementById('ml_kamar_tidur').value),
            kamar_mandi: parseInt(document.getElementById('ml_kamar_mandi').value),
            kota: document.getElementById('ml_kota').value,
            posisi_kota: document.getElementById('ml_posisi_kota').value,
        };

        try {
            const response = await fetch('{{ route("ml.test.predict") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });

            const result = await response.json();

            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;

            if (response.ok && result.status === 'success') {
                displayResult(result.predicted_cluster, data);
                
                // Show recommendations
                if (result.recommendations_html) {
                    document.getElementById('recommendationsGrid').innerHTML = result.recommendations_html;
                    document.getElementById('mlRecommendations').style.display = 'block';
                    
                    const recTitle = document.getElementById('recTitle');
                    if (recTitle) {
                        if (result.is_fallback) {
                            recTitle.innerHTML = `🏠 Rekomendasi Properti Alternatif <span style="font-size: 0.8rem; font-weight: 600; color: #b91c1c; background: #fee2e2; padding: 4px 8px; border-radius: 6px; margin-left: 10px;">(Belum ada data di ${result.req_kota})</span>`;
                        } else {
                            recTitle.innerHTML = `🏠 Rekomendasi Properti di ${result.req_kota || 'Kategori Ini'}`;
                        }
                    }
                } else {
                    document.getElementById('mlRecommendations').style.display = 'none';
                }
                
                checkServiceStatus(); // refresh status
            } else {
                showError(result.message || 'Terjadi kesalahan pada ML Service.');
            }
        } catch (err) {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
            showError('Tidak dapat terhubung ke server. Pastikan Flask ML Service berjalan di port 5000.');
        }
    });

    function showError(message) {
        errorText.textContent = message;
        errorEl.classList.add('show');
        errorEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function displayResult(cluster, inputData) {
        const info = getClusterInfo(cluster);

        // Hero
        const hero = document.getElementById('clusterHero');
        hero.className = `result-cluster-hero ${info.color}`;
        document.getElementById('clusterNumber').textContent = cluster;
        document.getElementById('clusterName').textContent = info.name;
        document.getElementById('clusterDesc').textContent = info.desc;

        // Info cards
        document.getElementById('infoKategori').textContent = info.kategori;

        // Summary
        const summaryGrid = document.getElementById('summaryGrid');
        summaryGrid.innerHTML = `
            <div class="summary-item">
                <div class="summary-item-label">Harga</div>
                <div class="summary-item-value">${formatRp(inputData.harga)}</div>
            </div>
            <div class="summary-item">
                <div class="summary-item-label">Luas Tanah</div>
                <div class="summary-item-value">${inputData.luas_tanah} m²</div>
            </div>
            <div class="summary-item">
                <div class="summary-item-label">Luas Bangunan</div>
                <div class="summary-item-value">${inputData.luas_bangunan} m²</div>
            </div>
            <div class="summary-item">
                <div class="summary-item-label">Kamar Tidur</div>
                <div class="summary-item-value">${inputData.kamar_tidur} KT</div>
            </div>
            <div class="summary-item">
                <div class="summary-item-label">Kamar Mandi</div>
                <div class="summary-item-value">${inputData.kamar_mandi} KM</div>
            </div>
            <div class="summary-item">
                <div class="summary-item-label">Kota</div>
                <div class="summary-item-value">${inputData.kota}</div>
            </div>
            <div class="summary-item">
                <div class="summary-item-label">Posisi</div>
                <div class="summary-item-value">${inputData.posisi_kota}</div>
            </div>
        `;

        // Show result
        resultEl.classList.add('show');
        resultEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
});
</script>
@endpush
