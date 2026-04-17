@extends('layouts.app')

@section('title', 'Rekomendasi Personal — RumahKu')

@push('styles')
<style>
    .rekom-page {
        min-height: 100vh;
        padding: 6rem 2rem 4rem;
        background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 50%, #ecfdf5 100%);
    }
    .rekom-container { max-width: 1000px; margin: 0 auto; }

    .rekom-header { text-align: center; margin-bottom: 2rem; }
    .rekom-header h1 { font-size: 1.8rem; font-weight: 800; margin-bottom: .4rem; }
    .rekom-header p { color: var(--text-soft); font-size: .95rem; max-width: 600px; margin: 0 auto; }

    .rekom-card {
        background: white; border-radius: 20px; padding: 2rem;
        border: 1px solid var(--border); box-shadow: var(--shadow-md);
        margin-bottom: 2rem;
    }
    .rekom-card h2 { font-size: 1.15rem; font-weight: 700; margin-bottom: .5rem; display: flex; align-items: center; gap: .5rem; }
    .rekom-card p.sub { font-size: .85rem; color: var(--text-soft); margin-bottom: 1.5rem; }

    /* Priority Sliders */
    .priority-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
    .priority-item { }
    .priority-label { display: flex; justify-content: space-between; align-items: center; margin-bottom: .4rem; }
    .priority-label span { font-size: .88rem; font-weight: 600; color: var(--text-dark); }
    .priority-label .pval { font-size: .8rem; font-weight: 700; color: var(--primary); background: var(--primary-light); padding: .15rem .5rem; border-radius: 6px; }
    .priority-item input[type="range"] {
        width: 100%; height: 6px; border-radius: 3px;
        -webkit-appearance: none; appearance: none;
        background: linear-gradient(to right, var(--primary) 0%, #e2e8f0 0%);
        outline: none;
    }
    .priority-item input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none; width: 18px; height: 18px;
        border-radius: 50%; background: var(--primary); cursor: pointer;
        border: 2px solid white; box-shadow: 0 1px 4px rgba(0,0,0,.2);
    }

    /* Results */
    .rekom-results { display: none; }
    .rekom-results.show { display: block; }
    .rekom-results h2 { font-size: 1.2rem; font-weight: 800; margin-bottom: 1.25rem; }

    .rank-card {
        background: white; border-radius: 16px; padding: 1.2rem 1.5rem;
        border: 1px solid var(--border); margin-bottom: 1rem;
        display: flex; align-items: center; gap: 1.2rem;
        transition: all .25s; text-decoration: none; color: inherit;
    }
    .rank-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); border-color: var(--primary); }
    .rank-badge {
        width: 42px; height: 42px; border-radius: 12px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.1rem; color: white;
    }
    .rank-1 { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .rank-2 { background: linear-gradient(135deg, #94a3b8, #64748b); }
    .rank-3 { background: linear-gradient(135deg, #b45309, #92400e); }
    .rank-other { background: linear-gradient(135deg, #cbd5e1, #94a3b8); }
    .rank-info { flex: 1; }
    .rank-name { font-size: 1rem; font-weight: 700; color: var(--text-dark); }
    .rank-detail { font-size: .82rem; color: var(--text-soft); margin-top: .15rem; }
    .rank-score-wrap { text-align: right; flex-shrink: 0; }
    .rank-score { font-size: 1.3rem; font-weight: 800; color: var(--primary); }
    .rank-score-label { font-size: .72rem; color: var(--text-soft); font-weight: 600; }

    .method-badge {
        display: inline-flex; align-items: center; gap: .4rem;
        background: var(--primary-light); color: var(--primary);
        padding: .4rem .9rem; border-radius: 8px;
        font-size: .78rem; font-weight: 700; margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .rekom-page { padding: 5rem 1rem 3rem; }
        .priority-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<section class="rekom-page">
    <div class="rekom-container">
        <div class="rekom-header">
            <h1>🎯 Rekomendasi Personal</h1>
            <p>Atur prioritas kriteria sesuai kebutuhanmu, dan sistem SAW (Simple Additive Weighting) akan memberikan ranking properti terbaik</p>
        </div>

        <div class="rekom-card">
            <h2>⚙️ Atur Prioritas Kriteria</h2>
            <p class="sub">Geser slider untuk menentukan bobot prioritas setiap kriteria (1 = rendah, 5 = sangat penting)</p>

            <div class="priority-grid">
                <div class="priority-item">
                    <div class="priority-label">
                        <span>💰 Harga Terjangkau</span>
                        <span class="pval" id="val_harga">3</span>
                    </div>
                    <input type="range" min="1" max="5" value="3" id="w_harga">
                </div>
                <div class="priority-item">
                    <div class="priority-label">
                        <span>📐 Luas Tanah</span>
                        <span class="pval" id="val_tanah">3</span>
                    </div>
                    <input type="range" min="1" max="5" value="3" id="w_tanah">
                </div>
                <div class="priority-item">
                    <div class="priority-label">
                        <span>🏗️ Luas Bangunan</span>
                        <span class="pval" id="val_bangunan">3</span>
                    </div>
                    <input type="range" min="1" max="5" value="3" id="w_bangunan">
                </div>
                <div class="priority-item">
                    <div class="priority-label">
                        <span>🛏️ Kamar Tidur</span>
                        <span class="pval" id="val_kt">3</span>
                    </div>
                    <input type="range" min="1" max="5" value="3" id="w_kt">
                </div>
                <div class="priority-item">
                    <div class="priority-label">
                        <span>🚿 Kamar Mandi</span>
                        <span class="pval" id="val_km">2</span>
                    </div>
                    <input type="range" min="1" max="5" value="2" id="w_km">
                </div>
            </div>

            <div class="form-grid" style="margin-bottom:1.5rem;">
                <div class="form-group">
                    <label for="filter_lokasi">Filter Lokasi (Opsional)</label>
                    <select id="filter_lokasi">
                        <option value="">Semua Lokasi</option>
                        @foreach($lokasiList as $lok)
                            <option value="{{ $lok }}">{{ $lok }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="filter_budget">Budget Maksimal (Opsional)</label>
                    <input type="number" id="filter_budget" placeholder="Contoh: 2000000000">
                </div>
            </div>

            <button class="btn btn-primary btn-block" id="rekomendasiBtn">
                🎯 Dapatkan Rekomendasi
            </button>
        </div>

        <div class="rekom-results" id="rekomendasiResults">
            <div class="method-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Metode SAW (Simple Additive Weighting)
            </div>
            <h2>🏆 Ranking Rekomendasi</h2>
            <div id="rankingList"></div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Raw property data from server
    const properties = @json($rumahs);

    // Slider visual update
    document.querySelectorAll('input[type="range"]').forEach(slider => {
        const valEl = document.getElementById('val_' + slider.id.replace('w_', ''));
        const update = () => {
            const pct = ((slider.value - 1) / 4) * 100;
            slider.style.background = `linear-gradient(to right, var(--primary) ${pct}%, #e2e8f0 ${pct}%)`;
            if (valEl) valEl.textContent = slider.value;
        };
        slider.addEventListener('input', update);
        update();
    });

    const formatRp = (num) => 'Rp ' + Math.round(num).toLocaleString('id-ID');

    document.getElementById('rekomendasiBtn').addEventListener('click', function() {
        // Get weights
        const w = {
            harga:    parseInt(document.getElementById('w_harga').value),
            tanah:    parseInt(document.getElementById('w_tanah').value),
            bangunan: parseInt(document.getElementById('w_bangunan').value),
            kt:       parseInt(document.getElementById('w_kt').value),
            km:       parseInt(document.getElementById('w_km').value),
        };

        // Normalize weights
        const totalW = w.harga + w.tanah + w.bangunan + w.kt + w.km;
        const nw = {
            harga:    w.harga / totalW,
            tanah:    w.tanah / totalW,
            bangunan: w.bangunan / totalW,
            kt:       w.kt / totalW,
            km:       w.km / totalW,
        };

        // Filter properties
        let data = [...properties];
        const filterLok = document.getElementById('filter_lokasi').value;
        const filterBudget = parseFloat(document.getElementById('filter_budget').value) || 0;

        if (filterLok) data = data.filter(p => p.lokasi === filterLok);
        if (filterBudget > 0) data = data.filter(p => p.harga <= filterBudget);

        if (data.length === 0) {
            document.getElementById('rankingList').innerHTML = '<div style="text-align:center;padding:2rem;color:var(--text-soft);">Tidak ada properti yang cocok dengan filter.</div>';
            document.getElementById('rekomendasiResults').classList.add('show');
            return;
        }

        // SAW: Find max/min for normalization
        const maxHarga    = Math.max(...data.map(p => p.harga || 1));
        const maxTanah    = Math.max(...data.map(p => p.luas_tanah || 1));
        const maxBangunan = Math.max(...data.map(p => p.luas_bangunan || 1));
        const maxKT       = Math.max(...data.map(p => p.kamar_tidur || 1));
        const maxKM       = Math.max(...data.map(p => p.kamar_mandi || 1));
        const minHarga    = Math.min(...data.map(p => p.harga || 1));

        // Calculate SAW score
        data.forEach(p => {
            // Harga is COST criteria (lower is better) → min/value
            const nHarga    = minHarga / (p.harga || 1);
            // Others are BENEFIT criteria (higher is better) → value/max
            const nTanah    = (p.luas_tanah || 0) / maxTanah;
            const nBangunan = (p.luas_bangunan || 0) / maxBangunan;
            const nKT       = (p.kamar_tidur || 0) / maxKT;
            const nKM       = (p.kamar_mandi || 0) / maxKM;

            p.saw_score = (nw.harga * nHarga) + (nw.tanah * nTanah) + (nw.bangunan * nBangunan) + (nw.kt * nKT) + (nw.km * nKM);
        });

        // Sort by score desc
        data.sort((a, b) => b.saw_score - a.saw_score);

        // Render
        let html = '';
        data.forEach((p, i) => {
            const rank = i + 1;
            const badgeClass = rank <= 3 ? `rank-${rank}` : 'rank-other';
            const medal = rank === 1 ? '🥇' : rank === 2 ? '🥈' : rank === 3 ? '🥉' : rank;
            const detailUrl = `/properti/${p._id}`;

            html += `
            <a href="${detailUrl}" class="rank-card">
                <div class="rank-badge ${badgeClass}">${medal}</div>
                <div class="rank-info">
                    <div class="rank-name">${p.nama}</div>
                    <div class="rank-detail">${p.lokasi} · ${p.luas_tanah || '-'}m² · ${p.kamar_tidur || '-'} KT · ${formatRp(p.harga)}</div>
                </div>
                <div class="rank-score-wrap">
                    <div class="rank-score">${(p.saw_score * 100).toFixed(1)}</div>
                    <div class="rank-score-label">Skor SAW</div>
                </div>
            </a>`;
        });

        document.getElementById('rankingList').innerHTML = html;
        const resultsEl = document.getElementById('rekomendasiResults');
        resultsEl.classList.add('show');
        resultsEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});
</script>
@endpush
