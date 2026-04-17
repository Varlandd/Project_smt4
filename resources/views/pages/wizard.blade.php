@extends('layouts.app')

@section('title', 'Cari Rumah Impian — RumahKu')

@push('styles')
<style>
    .wizard-page {
        min-height: 100vh;
        padding: 6rem 1rem 4rem;
        background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 50%, #ecfdf5 100%);
    }
    .wizard-container { max-width: 900px; margin: 0 auto; }

    /* ── Progress Bar ── */
    .wizard-progress {
        display: flex; justify-content: space-between; margin-bottom: 3rem;
        position: relative; max-width: 600px; margin-left: auto; margin-right: auto;
    }
    .wizard-progress::before {
        content: ''; position: absolute; top: 20px; left: 0; right: 0;
        height: 4px; background: var(--border); z-index: 1;
    }
    .progress-fill {
        content: ''; position: absolute; top: 20px; left: 0; width: 0%;
        height: 4px; background: var(--primary); z-index: 2; transition: width .4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .step-indicator {
        position: relative; z-index: 3; display: flex; flex-direction: column; align-items: center; gap: .5rem;
    }
    .step-dot {
        width: 44px; height: 44px; border-radius: 50%; background: white;
        border: 4px solid var(--border); display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.1rem; color: var(--text-light);
        transition: all .3s;
    }
    .step-indicator.active .step-dot { border-color: var(--primary); color: var(--primary); background: white; transform: scale(1.1); box-shadow: 0 0 0 6px rgba(15,118,110,.1); }
    .step-indicator.completed .step-dot { background: var(--primary); border-color: var(--primary); color: white; }
    .step-label { font-size: .75rem; font-weight: 700; color: var(--text-soft); text-transform: uppercase; letter-spacing: .02em; }
    .step-indicator.active .step-label { color: var(--primary); }

    /* ── Step Content ── */
    .step-content { display: none; animation: fadeIn .5s ease-out; }
    .step-content.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }

    .wizard-card {
        background: white; border-radius: 24px; padding: 2.5rem;
        border: 1px solid var(--border); box-shadow: var(--shadow-lg);
    }
    .wizard-header { text-align: center; margin-bottom: 2rem; }
    .wizard-header h2 { font-size: 1.6rem; font-weight: 800; margin-bottom: .5rem; color: var(--text-dark); }
    .wizard-header p { color: var(--text-soft); font-size: .95rem; }

    /* ── Custom UI Components for Wizard ── */
    .budget-display {
        background: var(--primary-light); border-radius: 16px; padding: 1.5rem;
        text-align: center; margin-top: 1.5rem; border: 1px dashed var(--primary);
    }
    .budget-label { font-size: .8rem; font-weight: 700; color: var(--primary); text-transform: uppercase; margin-bottom: .3rem; }
    .budget-value { font-size: 1.8rem; font-weight: 800; color: var(--primary); }

    .priority-item { margin-bottom: 1.5rem; }
    .priority-label { display: flex; justify-content: space-between; align-items: center; margin-bottom: .5rem; }
    .priority-label span { font-weight: 700; font-size: .9rem; }
    .pval-badge { background: var(--bg-soft); color: var(--text-mid); padding: .2rem .6rem; border-radius: 6px; font-size: .8rem; font-weight: 800; }

    /* ── Step 3: Analysis Animation ── */
    .analysis-wrap { text-align: center; padding: 3rem 0; }
    .lottie-placeholder { width: 120px; height: 120px; margin: 0 auto 2rem; background: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; position: relative; }
    .pulse-ring { position: absolute; width: 100%; height: 100%; border: 4px solid var(--primary); border-radius: 50%; animation: pulse-anim 2s infinite; }
    @keyframes pulse-anim { 0% { transform: scale(0.8); opacity: 1; } 100% { transform: scale(1.6); opacity: 0; } }
    .analysis-text { font-size: 1.1rem; font-weight: 700; color: var(--text-mid); margin-bottom: .5rem; }
    .analysis-sub { font-size: .9rem; color: var(--text-soft); }

    /* ── Step 4: Results ── */
    .results-grid { display: grid; grid-template-columns: 1fr; gap: 1rem; }
    .rank-card {
        background: white; border-radius: 18px; padding: 1.2rem;
        border: 1px solid var(--border); display: flex; align-items: center; gap: 1.2rem;
        text-decoration: none; color: inherit; transition: all .3s;
    }
    .rank-card:hover { transform: scale(1.02); border-color: var(--primary); box-shadow: var(--shadow-md); }
    .rank-badge {
        width: 48px; height: 48px; border-radius: 12px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.2rem; color: white;
    }
    .rank-1 { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .rank-2 { background: linear-gradient(135deg, #94a3b8, #64748b); }
    .rank-3 { background: linear-gradient(135deg, #b45309, #92400e); }
    .rank-other { background: var(--bg-soft); color: var(--text-soft); }
    .rank-info { flex: 1; }
    .rank-name { font-size: 1.05rem; font-weight: 700; }
    .rank-score { font-size: 1.2rem; font-weight: 800; color: var(--primary); }

    .wizard-footer {
        display: flex; justify-content: space-between; margin-top: 2.5rem;
        padding-top: 1.5rem; border-top: 1px solid var(--border);
    }

    @media (max-width: 768px) {
        .wizard-page { padding-top: 5rem; }
        .form-grid { grid-template-columns: 1fr; }
        .wizard-progress { transform: scale(0.8); }
    }
</style>
@endpush

@section('content')
<section class="wizard-page">
    <div class="wizard-container">
        
        {{-- Progress Indicator --}}
        <div class="wizard-progress">
            <div class="progress-fill" id="progressFill"></div>
            <div class="step-indicator active" id="step1-ind">
                <div class="step-dot">1</div>
                <span class="step-label">Finansial</span>
            </div>
            <div class="step-indicator" id="step2-ind">
                <div class="step-dot">2</div>
                <span class="step-label">Kriteria</span>
            </div>
            <div class="step-indicator" id="step3-ind">
                <div class="step-dot">3</div>
                <span class="step-label">Analisis</span>
            </div>
            <div class="step-indicator" id="step4-ind">
                <div class="step-dot">4</div>
                <span class="step-label">Hasil</span>
            </div>
        </div>

        {{-- STEP 1: PROFIL FINANSIAL --}}
        <div class="step-content active" id="step1">
            <div class="wizard-card">
                <div class="wizard-header">
                    <h2>💰 Profil Finansial Anda</h2>
                    <p>Kami akan menghitung batas maksimal harga rumah yang sanggup Anda beli.</p>
                </div>
                
                <div class="form-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                    <div class="form-group">
                        <label>Penghasilan Bulanan</label>
                        <div class="input-with-icon">
                            <span class="input-prefix">Rp</span>
                            <input type="number" id="w_penghasilan" value="10000000" step="500000">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Uang Muka (DP) Tersedia</label>
                        <div class="input-with-icon">
                            <span class="input-prefix">Rp</span>
                            <input type="number" id="w_dp" value="50000000" step="1000000">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Cicilan Existing (Jika ada)</label>
                        <div class="input-with-icon">
                            <span class="input-prefix">Rp</span>
                            <input type="number" id="w_cicilan" value="0" step="100000">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tenor KPR (Tahun)</label>
                        <select id="w_tenor">
                            <option value="5">5 Tahun</option>
                            <option value="10">10 Tahun</option>
                            <option value="15" selected>15 Tahun</option>
                            <option value="20">20 Tahun</option>
                        </select>
                    </div>
                </div>

                <div class="budget-display">
                    <div class="budget-label">Estimasi Maksimal Budget Rumah</div>
                    <div class="budget-value" id="dispBudget">Rp 450.000.000</div>
                </div>

                <div class="wizard-footer">
                    <div></div>
                    <button class="btn btn-primary" onclick="nextStep(2)">Lanjutkan Langkah 2 →</button>
                </div>
            </div>
        </div>

        {{-- STEP 2: KRITERIA & LOKASI --}}
        <div class="step-content" id="step2">
            <div class="wizard-card">
                <div class="wizard-header">
                    <h2>🎯 Kriteria & Lokasi</h2>
                    <p>Tentukan lokasi target dan prioritas kriteria rumah yang Anda inginkan.</p>
                </div>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label>Filter Lokasi Properti</label>
                    <select id="w_lokasi">
                        <option value="">Semua Lokasi</option>
                        @foreach($lokasiList as $lok)
                            <option value="{{ $lok }}">{{ $lok }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="priority-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div class="priority-item">
                        <div class="priority-label"><span>💰 Harga Lebih Murah</span> <div class="pval-badge" id="v_w_harga">3</div></div>
                        <input type="range" min="1" max="5" value="3" id="weight_harga" class="slider">
                    </div>
                    <div class="priority-item">
                        <div class="priority-label"><span>📐 Luas Tanah</span> <div class="pval-badge" id="v_w_tanah">3</div></div>
                        <input type="range" min="1" max="5" value="3" id="weight_tanah" class="slider">
                    </div>
                    <div class="priority-item">
                        <div class="priority-label"><span>🏗️ Luas Bangunan</span> <div class="pval-badge" id="v_w_bangunan">3</div></div>
                        <input type="range" min="1" max="5" value="3" id="weight_bangunan" class="slider">
                    </div>
                    <div class="priority-item">
                        <div class="priority-label"><span>🛏️ Kamar Tidur</span> <div class="pval-badge" id="v_w_kt">2</div></div>
                        <input type="range" min="1" max="5" value="2" id="weight_kt" class="slider">
                    </div>
                </div>

                <div class="wizard-footer">
                    <button class="btn btn-outline" onclick="nextStep(1)">← Kembali</button>
                    <button class="btn btn-primary" onclick="runAnalysis()">Mulai Analisis Sistem →</button>
                </div>
            </div>
        </div>

        {{-- STEP 3: ANALISIS DATA --}}
        <div class="step-content" id="step3">
            <div class="wizard-card">
                <div class="analysis-wrap">
                    <div class="lottie-placeholder">
                        <div class="pulse-ring"></div>
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                            <line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                    </div>
                    <h3 class="analysis-text" id="analysisStatus">Menghubungkan ke Database...</h3>
                    <p class="analysis-sub">Menganalisis properti berdasarkan profil finansial Anda</p>
                    
                    <div style="max-width:300px; margin: 2rem auto 0; height: 8px; background: var(--border); border-radius: 4px; overflow: hidden;">
                        <div id="analysisBar" style="width: 10%; height: 100%; background: var(--primary); transition: width .2s;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STEP 4: HASIL REKOMENDASI --}}
        <div class="step-content" id="step4">
            <div class="wizard-header">
                <h2>🏆 Rekomendasi Untuk Anda</h2>
                <p id="totalFound">Menemukan 0 properti yang sesuai dengan budget Anda.</p>
            </div>

            <div class="results-grid" id="wizardResults">
                {{-- Result cards injected here --}}
            </div>

            <div class="wizard-footer">
                <button class="btn btn-outline" onclick="nextStep(1)">← Hitung Ulang</button>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Ke Dashboard</a>
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
    const properties = @json($rumahs);
    let currentStep = 1;
    let maxBudget = 450000000;

    function formatRp(num) {
        return 'Rp ' + Math.round(num).toLocaleString('id-ID');
    }

    // React to Step 1 Inputs
    document.querySelectorAll('#step1 input, #step1 select').forEach(el => {
        el.addEventListener('input', () => {
            const income = parseFloat(document.getElementById('w_penghasilan').value) || 0;
            const dp = parseFloat(document.getElementById('w_dp').value) || 0;
            const cicilan = parseFloat(document.getElementById('w_cicilan').value) || 0;
            const tenor = parseInt(document.getElementById('w_tenor').value) || 15;

            const maxCicilan = (income * 0.3) - cicilan;
            if (maxCicilan <= 0) {
                document.getElementById('dispBudget').textContent = 'Budget tidak mencukupi';
                maxBudget = 0;
                return;
            }

            const bungaBulanan = 0.08 / 12; // 8% per year
            const jumlahBulan = tenor * 12;
            const pokokPinjaman = maxCicilan * ((1 - Math.pow(1 + bungaBulanan, -jumlahBulan)) / bungaBulanan);
            maxBudget = pokokPinjaman + dp;

            document.getElementById('dispBudget').textContent = formatRp(maxBudget);
        });
    });

    // Slider visuals
    document.querySelectorAll('.slider').forEach(s => {
        s.addEventListener('input', () => {
            document.getElementById('v_' + s.id).textContent = s.value;
        });
    });

    function nextStep(step) {
        if (step > 1 && maxBudget <= 0) {
            alert("Maaf, berdasarkan profil finansial, budget Anda belum mencukupi untuk cicilan KPR. Mohon sesuaikan penghasilan atau tenor.");
            return;
        }

        document.querySelectorAll('.step-content').forEach(c => c.classList.remove('active'));
        document.getElementById('step' + step).classList.add('active');
        
        // Update Indicator
        document.querySelectorAll('.step-indicator').forEach((ind, i) => {
            const idx = i + 1;
            ind.classList.remove('active', 'completed');
            if (idx < step) ind.classList.add('completed');
            if (idx === step) ind.classList.add('active');
        });

        document.getElementById('progressFill').style.width = ((step - 1) / 3 * 100) + '%';
        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    async function runAnalysis() {
        nextStep(3);
        const statusEl = document.getElementById('analysisStatus');
        const barEl = document.getElementById('analysisBar');

        const transitions = [
            { t: "Mengambil data properti...", p: 30 },
            { t: "Menghitung Prediksi Harga (MLR)...", p: 60 },
            { t: "Menjalankan Algoritma Ranking (SAW)...", p: 90 },
            { t: "Memfilter berdasarkan budget...", p: 100 }
        ];

        for (const tr of transitions) {
            statusEl.textContent = tr.t;
            barEl.style.width = tr.p + '%';
            await new Promise(r => setTimeout(r, 800));
        }

        performRanking();
        nextStep(4);
    }

    function performRanking() {
        // 1. Inputs
        const weights = {
            harga:    parseInt(document.getElementById('weight_harga').value),
            tanah:    parseInt(document.getElementById('weight_tanah').value),
            bangunan: parseInt(document.getElementById('weight_bangunan').value),
            kt:       parseInt(document.getElementById('weight_kt').value)
        };
        const location = document.getElementById('w_lokasi').value;

        // 2. Filter by Location & Budget
        let filtered = properties.filter(p => {
            const matchLoc = !location || p.lokasi === location;
            const matchBudget = (p.harga || 0) <= maxBudget;
            return matchLoc && matchBudget;
        });

        document.getElementById('totalFound').textContent = `Menemukan ${filtered.length} properti yang sesuai dengan budget Anda (${formatRp(maxBudget)}).`;

        if (filtered.length === 0) {
            document.getElementById('wizardResults').innerHTML = `<div style="text-align:center; padding: 2rem; color: var(--text-soft);">Yah, belum ada properti yang cocok dengan budget Anda di lokasi ini. Coba naikkan tenor atau perkecil kriteria.</div>`;
            return;
        }

        // 3. Normalization & SAW
        const maxT = Math.max(...filtered.map(p => p.luas_tanah || 1));
        const maxB = Math.max(...filtered.map(p => p.luas_bangunan || 1));
        const maxK = Math.max(...filtered.map(p => p.kamar_tidur || 1));
        const minH = Math.min(...filtered.map(p => p.harga || 1));

        const sumW = weights.harga + weights.tanah + weights.bangunan + weights.kt;

        filtered.forEach(p => {
            const nH = minH / (p.harga || 1); // Cost
            const nT = (p.luas_tanah || 0) / maxT;
            const nB = (p.luas_bangunan || 0) / maxB;
            const nK = (p.kamar_tidur || 0) / maxK;

            p.score = ((weights.harga/sumW) * nH) + ((weights.tanah/sumW) * nT) + ((weights.bangunan/sumW) * nB) + ((weights.kt/sumW) * nK);
        });

        filtered.sort((a, b) => b.score - a.score);

        // 4. Render
        let html = '';
        filtered.forEach((p, i) => {
            const r = i + 1;
            const medal = r === 1 ? '🥇' : r === 2 ? '🥈' : r === 3 ? '🥉' : r;
            const badge = r <= 3 ? 'rank-' + r : 'rank-other';
            
            html += `
            <a href="/properti/${p._id}" class="rank-card">
                <div class="rank-badge ${badge}">${medal}</div>
                <div class="rank-info">
                    <div class="rank-name">${p.nama}</div>
                    <div style="font-size: .8rem; color: var(--text-soft);">${p.lokasi} · ${p.luas_tanah}m² · ${formatRp(p.harga)}</div>
                </div>
                <div style="text-align:right;">
                    <div class="rank-score">${(p.score * 100).toFixed(1)}</div>
                    <div style="font-size: .7rem; color: var(--text-light); text-transform:uppercase; font-weight:700;">SAW Score</div>
                </div>
            </a>`;
        });
        document.getElementById('wizardResults').innerHTML = html;
    }
</script>
@endpush
