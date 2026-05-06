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
            <p>Atur prioritas kriteria sesuai kebutuhanmu, dan sistem TOPSIS (Technique for Order of Preference by Similarity to Ideal Solution) akan memberikan ranking properti terbaik</p>
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
                Metode TOPSIS — Diproses oleh Flask ML Service
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
    const btn = document.getElementById('rekomendasiBtn');
    const resultsEl = document.getElementById('rekomendasiResults');
    const rankingList = document.getElementById('rankingList');

    btn.addEventListener('click', async function() {
        // Get weights
        const weights = {
            harga:         parseInt(document.getElementById('w_harga').value),
            luas_tanah:    parseInt(document.getElementById('w_tanah').value),
            luas_bangunan: parseInt(document.getElementById('w_bangunan').value),
            kamar_tidur:   parseInt(document.getElementById('w_kt').value),
            kamar_mandi:   parseInt(document.getElementById('w_km').value),
        };

        const lokasi_filter = document.getElementById('filter_lokasi').value;
        const budget_max = parseFloat(document.getElementById('filter_budget').value) || 0;

        // Show loading
        btn.disabled = true;
        btn.innerHTML = '<div class="spinner" style="width:20px;height:20px;border-width:3px;margin:0 auto;"></div> Memproses TOPSIS...';

        try {
            const response = await fetch('{{ route("ml.recommend") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ weights, lokasi_filter, budget_max }),
            });

            const result = await response.json();

            if (response.ok && result.status === 'success' && result.data.rankings) {
                renderResults(result.data.rankings);
            } else {
                showError('ML Service tidak merespons. Pastikan Flask server berjalan di port 5000.');
            }
        } catch (err) {
            showError('Tidak dapat terhubung ke ML Service (Flask). Pastikan server Flask berjalan dengan perintah: python app.py');
        }

        btn.disabled = false;
        btn.innerHTML = '🎯 Dapatkan Rekomendasi';
    });

    function showError(message) {
        rankingList.innerHTML = `
            <div style="text-align:center;padding:2rem;background:#fef2f2;border:1px solid #fecaca;border-radius:16px;color:#dc2626;">
                <div style="font-size:2rem;margin-bottom:.5rem;">⚠️</div>
                <strong>Flask ML Service Offline</strong>
                <p style="margin-top:.5rem;font-size:.9rem;color:#991b1b;">${message}</p>
                <p style="margin-top:.8rem;font-size:.82rem;color:#b91c1c;">
                    Jalankan: <code style="background:#fee2e2;padding:.2rem .5rem;border-radius:4px;">cd ml_service && python app.py</code>
                </p>
            </div>`;
        resultsEl.classList.add('show');
    }

    function renderResults(rankings) {
        if (rankings.length === 0) {
            rankingList.innerHTML = '<div style="text-align:center;padding:2rem;color:var(--text-soft);">Tidak ada properti yang cocok dengan filter.</div>';
            resultsEl.classList.add('show');
            return;
        }

        // Update method badge
        const badgeEl = resultsEl.querySelector('.method-badge');
        if (badgeEl) {
            badgeEl.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg> Metode TOPSIS — Diproses oleh Flask ML Service`;
        }

        let html = '';
        rankings.forEach((p, i) => {
            const rank = p.rank || (i + 1);
            const badgeClass = rank <= 3 ? `rank-${rank}` : 'rank-other';
            const medal = rank === 1 ? '🥇' : rank === 2 ? '🥈' : rank === 3 ? '🥉' : rank;
            const score = p.topsis_score || 0;
            const detailUrl = `/properti/${p.id || p._id}`;

            html += `
            <a href="${detailUrl}" class="rank-card">
                <div class="rank-badge ${badgeClass}">${medal}</div>
                <div class="rank-info">
                    <div class="rank-name">${p.nama}</div>
                    <div class="rank-detail">${p.lokasi} · ${p.luas_tanah || '-'}m² · ${p.kamar_tidur || '-'} KT · ${formatRp(p.harga)}</div>
                </div>
                <div class="rank-score-wrap">
                    <div class="rank-score">${score.toFixed(1)}</div>
                    <div class="rank-score-label">Skor TOPSIS</div>
                </div>
            </a>`;
        });

        rankingList.innerHTML = html;
        resultsEl.classList.add('show');
        resultsEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
});
</script>
@endpush

