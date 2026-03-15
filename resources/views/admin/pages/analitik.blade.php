@extends('admin.layouts.admin')

@section('title', 'Analitik Prediksi — RumahKu')
@section('page-title', 'Analitik Prediksi Harga Rumah')

@push('styles')
<style>
    /* ── Region Info Cards ── */
    .region-info-strip {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-top: 20px;
        transition: all 0.3s ease;
    }
    .region-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        position: relative;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .region-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
    .region-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }
    .region-card.card-kenaikan::before { background: linear-gradient(90deg, #10b981, #34d399); }
    .region-card.card-avg::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
    .region-card.card-range::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .region-card.card-jumlah::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }

    .region-card .card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }
    .region-card .card-icon svg {
        width: 20px;
        height: 20px;
    }
    .region-card .card-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }
    .region-card .card-label {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 4px;
    }
    .region-card .card-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 20px;
        margin-top: 8px;
    }
    .badge-up { background: #dcfce7; color: #15803d; }
    .badge-neutral { background: #f1f5f9; color: #475569; }

    /* ── Selected region header ── */
    .region-selector {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 24px;
        background: linear-gradient(135deg, #0f172a, #1e293b);
        padding: 20px 28px;
        border-radius: 16px;
        color: white;
    }
    .region-selector h2 {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 600;
    }
    .region-selector p {
        margin: 4px 0 0;
        font-size: 0.85rem;
        color: #94a3b8;
    }
    .region-selector select {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        min-width: 220px;
        transition: background 0.2s;
    }
    .region-selector select:hover {
        background: rgba(255,255,255,0.15);
    }
    .region-selector select:focus {
        outline: none;
        border-color: #3b82f6;
    }
    .region-selector select option {
        background: #1e293b;
        color: white;
    }

    /* ── ML Status ── */
    .ml-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-left: auto;
    }
    .ml-badge.online { background: rgba(34,197,94,0.15); color: #22c55e; }
    .ml-badge.offline { background: rgba(239,68,68,0.15); color: #ef4444; }
    .ml-badge .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        animation: pulse 2s infinite;
    }
    .ml-badge.online .dot { background: #22c55e; }
    .ml-badge.offline .dot { background: #ef4444; }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    /* ── Analytics Grid ── */
    .analytics-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-top: 24px;
    }
    .chart-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .chart-card h3 {
        margin: 0 0 20px;
        color: #1e293b;
        font-size: 1.05rem;
        font-weight: 700;
    }
    .full-width {
        grid-column: 1 / -1;
    }

    /* ── Prediction Form (Redesigned) ── */
    .predict-section {
        background: #ffffff;
        border-radius: 14px;
        padding: 28px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        margin-top: 24px;
    }
    .predict-section h3 {
        margin: 0 0 6px;
        color: #0f172a;
        font-size: 1.15rem;
        font-weight: 700;
    }
    .predict-section .subtitle {
        color: #64748b;
        font-size: 0.88rem;
        margin-bottom: 24px;
    }
    .predict-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr) auto;
        gap: 16px;
        align-items: end;
    }
    .predict-grid .form-group {
        display: flex;
        flex-direction: column;
    }
    .predict-grid label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .predict-grid input,
    .predict-grid select {
        padding: 11px 14px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        background: #f8fafc;
        transition: all 0.2s;
    }
    .predict-grid input:focus,
    .predict-grid select:focus {
        outline: none;
        border-color: #3b82f6;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(59,130,246,0.1);
    }
    .btn-predict {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
        height: 46px;
    }
    .btn-predict:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(37,99,235,0.35);
    }
    .btn-predict:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    /* ── Prediction Result ── */
    .predict-result {
        display: none;
        margin-top: 20px;
        padding: 20px 24px;
        border-radius: 12px;
        border-left: 4px solid;
    }
    .predict-result.show { display: block; animation: slideUp 0.35s ease; }
    .predict-result.success { background: #f0fdf4; border-color: #22c55e; }
    .predict-result.error   { background: #fef2f2; border-color: #ef4444; }

    .predict-result .result-header {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 4px;
    }
    .predict-result .result-price {
        font-size: 2rem;
        font-weight: 800;
        color: #059669;
    }
    .predict-result.error .result-price {
        color: #dc2626;
        font-size: 1rem;
        font-weight: 600;
    }
    .predict-result .result-range {
        font-size: 0.88rem;
        color: #047857;
        margin-top: 4px;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 1024px) {
        .region-info-strip { grid-template-columns: repeat(2, 1fr); }
        .predict-grid { grid-template-columns: repeat(2, 1fr); }
        .predict-grid .btn-predict { grid-column: 1 / -1; }
    }
    @media (max-width: 768px) {
        .analytics-grid { grid-template-columns: 1fr; }
        .region-info-strip { grid-template-columns: 1fr; }
        .predict-grid { grid-template-columns: 1fr; }
        .region-selector { flex-direction: column; align-items: flex-start; }
    }

    /* ── Dark Mode ── */
    .dark-mode .region-card {
        background: #1e293b;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    .dark-mode .region-card:hover {
        box-shadow: 0 6px 16px rgba(0,0,0,0.4);
    }
    .dark-mode .region-card .card-value {
        color: #f1f5f9;
    }
    .dark-mode .region-card .card-label {
        color: #94a3b8;
    }
    .dark-mode .region-card .card-icon {
        opacity: 0.85;
    }
    .dark-mode .badge-up {
        background: rgba(220,252,231,0.12);
        color: #4ade80;
    }
    .dark-mode .badge-neutral {
        background: rgba(241,245,249,0.08);
        color: #94a3b8;
    }
    .dark-mode .chart-card {
        background: #1e293b;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    .dark-mode .chart-card h3 {
        color: #f1f5f9;
    }
    .dark-mode .predict-section {
        background: #1e293b;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    .dark-mode .predict-section h3 {
        color: #f1f5f9;
    }
    .dark-mode .predict-section .subtitle {
        color: #94a3b8;
    }
    .dark-mode .predict-grid label {
        color: #94a3b8;
    }
    .dark-mode .predict-grid input,
    .dark-mode .predict-grid select {
        background: #0f172a;
        border-color: #334155;
        color: #e2e8f0;
    }
    .dark-mode .predict-grid input:focus,
    .dark-mode .predict-grid select:focus {
        border-color: #3b82f6;
        background: #0f172a;
        box-shadow: 0 0 0 4px rgba(59,130,246,0.15);
    }
    .dark-mode .predict-grid input::placeholder {
        color: #475569;
    }
    .dark-mode .predict-grid select option {
        background: #1e293b;
        color: #e2e8f0;
    }
    .dark-mode .predict-result.success {
        background: rgba(240,253,244,0.08);
        border-color: #22c55e;
    }
    .dark-mode .predict-result.error {
        background: rgba(254,242,242,0.08);
        border-color: #ef4444;
    }
    .dark-mode .predict-result .result-header {
        color: #94a3b8;
    }
    .dark-mode .predict-result .result-price {
        color: #4ade80;
    }
    .dark-mode .predict-result.error .result-price {
        color: #f87171;
    }
    .dark-mode .predict-result .result-range {
        color: #6ee7b7;
    }
</style>
@endpush

@section('content')

{{-- Region Selector Header --}}
<div class="region-selector">
    <div>
        <h2>📍 Analitik Wilayah</h2>
        <p>Pilih wilayah untuk melihat data prediksi kenaikan harga di wilayah tersebut</p>
    </div>
    <select id="regionSelect">
        @foreach($kenaikanPerWilayah as $lokasi => $data)
            <option value="{{ $lokasi }}">{{ $lokasi }}</option>
        @endforeach
    </select>
    <span class="ml-badge {{ $mlStatus }}">
        <span class="dot"></span>
        ML Engine {{ strtoupper($mlStatus) }}
    </span>
</div>

{{-- Dynamic Region Info Cards --}}
<div class="region-info-strip" id="regionCards">
    <div class="region-card card-kenaikan">
        <div class="card-icon" style="background: #dcfce7;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                <polyline points="17 6 23 6 23 12"/>
            </svg>
        </div>
        <div class="card-value" id="cardKenaikan">+12.5%</div>
        <div class="card-label">Prediksi Kenaikan Harga/Tahun</div>
        <span class="card-badge badge-up" id="badgeKenaikan">↑ Naik</span>
    </div>
    <div class="region-card card-avg">
        <div class="card-icon" style="background: #dbeafe;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#1e40af" stroke-width="2">
                 <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
            </svg>
        </div>
        <div class="card-value" id="cardAvgHarga">-</div>
        <div class="card-label">Rata-rata Harga Rumah</div>
        <span class="card-badge badge-neutral" id="badgeJumlah">0 properti</span>
    </div>
    <div class="region-card card-range">
        <div class="card-icon" style="background: #fef3c7;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2">
                <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
            </svg>
        </div>
        <div class="card-value" id="cardRange">-</div>
        <div class="card-label">Rentang Harga</div>
    </div>
    <div class="region-card card-jumlah">
        <div class="card-icon" style="background: #ede9fe;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#6d28d9" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </div>
        <div class="card-value" id="cardJumlah">0</div>
        <div class="card-label">Total Properti Tersedia</div>
    </div>
</div>

{{-- Predict Section --}}
<div class="predict-section">
    <h3>🔮 Prediksi Harga Rumah — Machine Learning</h3>
    <p class="subtitle">Masukkan spesifikasi rumah untuk mendapatkan estimasi harga dari model Random Forest Regressor.</p>

    <form id="predictForm" class="predict-grid">
        @csrf
        <div class="form-group">
            <label>Luas Tanah (m²)</label>
            <input type="number" id="luas_tanah" name="luas_tanah" placeholder="120" required min="1">
        </div>
        <div class="form-group">
            <label>Luas Bangunan (m²)</label>
            <input type="number" id="luas_bangunan" name="luas_bangunan" placeholder="80" required min="1">
        </div>
        <div class="form-group">
            <label>Kamar Tidur</label>
            <input type="number" id="kamar_tidur" name="kamar_tidur" placeholder="3" required min="1" max="10">
        </div>
        <div class="form-group">
            <label>Kamar Mandi</label>
            <input type="number" id="kamar_mandi" name="kamar_mandi" placeholder="2" required min="1" max="10">
        </div>
        <div class="form-group">
            <label>Lokasi</label>
            <select id="lokasi" name="lokasi" required>
                <option value="">Pilih Lokasi</option>
                @foreach($kenaikanPerWilayah as $lok => $data)
                    <option value="{{ $lok }}">{{ $lok }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-predict" id="btnPredict">⚡ Prediksi</button>
    </form>

    <div id="predictResult" class="predict-result">
        <div class="result-header">Estimasi Harga Rumah</div>
        <div class="result-price" id="resultPrice"></div>
        <div class="result-range" id="resultRange"></div>
    </div>
</div>

{{-- Charts --}}
<div class="analytics-grid">
    <div class="chart-card full-width">
        <h3>📈 Tren Harga Properti — Aktual vs Prediksi</h3>
        <canvas id="trendChart" height="90"></canvas>
    </div>

    <div class="chart-card">
        <h3>📊 Perbandingan Harga Antar Wilayah</h3>
        <canvas id="wilayahChart" height="250"></canvas>
    </div>

    <div class="chart-card">
        <h3>🏠 Distribusi Tipe Properti</h3>
        <canvas id="tipeChart" height="250"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // ── Per-Wilayah Data ──
    const regionData = {!! json_encode($kenaikanPerWilayah) !!};
    const regionSelect = document.getElementById('regionSelect');
    const lokasiSelect = document.getElementById('lokasi');

    function formatRupiah(num) {
        if (!num || num === 0) return 'Rp 0';
        if (num >= 1000000000) return 'Rp ' + (num / 1000000000).toFixed(1) + ' M';
        if (num >= 1000000) return 'Rp ' + (num / 1000000).toFixed(0) + ' Jt';
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function updateRegionCards(lokasi) {
        const data = regionData[lokasi];
        if (!data) return;

        document.getElementById('cardKenaikan').textContent = '+' + data.kenaikan + '%';
        document.getElementById('badgeKenaikan').textContent = data.kenaikan >= 10 ? '🔥 Tinggi' : '↑ Naik';
        document.getElementById('badgeKenaikan').className = 'card-badge badge-up';

        document.getElementById('cardAvgHarga').textContent = formatRupiah(data.avg_harga);
        document.getElementById('badgeJumlah').textContent = data.jumlah + ' properti';

        if (data.min_harga && data.max_harga && data.min_harga > 0) {
            document.getElementById('cardRange').textContent = formatRupiah(data.min_harga) + ' — ' + formatRupiah(data.max_harga);
        } else {
            document.getElementById('cardRange').textContent = 'Belum ada data';
        }

        document.getElementById('cardJumlah').textContent = data.jumlah;
    }

    // Initialize with first region
    if (regionSelect.options.length > 0) {
        updateRegionCards(regionSelect.value);
    }

    // Update on region dropdown change
    regionSelect.addEventListener('change', function() {
        updateRegionCards(this.value);
        // Sync prediction form's lokasi dropdown
        lokasiSelect.value = this.value;
    });

    // Also sync regionSelect when prediction lokasi changes
    lokasiSelect.addEventListener('change', function() {
        if (this.value && regionData[this.value]) {
            regionSelect.value = this.value;
            updateRegionCards(this.value);
        }
    });

    // ── Prediction Form ──
    const form = document.getElementById('predictForm');
    const resultDiv = document.getElementById('predictResult');
    const resultPrice = document.getElementById('resultPrice');
    const resultRange = document.getElementById('resultRange');
    const btnPredict = document.getElementById('btnPredict');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        btnPredict.textContent = '⏳ Memproses...';
        btnPredict.disabled = true;

        const payload = {
            luas_tanah: parseInt(document.getElementById('luas_tanah').value),
            luas_bangunan: parseInt(document.getElementById('luas_bangunan').value),
            kamar_tidur: parseInt(document.getElementById('kamar_tidur').value),
            kamar_mandi: parseInt(document.getElementById('kamar_mandi').value),
            lokasi: document.getElementById('lokasi').value
        };

        try {
            const res = await fetch("{{ route('admin.predict') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const json = await res.json();

            resultDiv.className = 'predict-result show';
            if (json.status === 'success') {
                resultDiv.classList.add('success');
                resultPrice.textContent = json.data.prediksi_formatted;
                const minF = 'Rp ' + json.data.range_harga.min.toLocaleString('id-ID');
                const maxF = 'Rp ' + json.data.range_harga.max.toLocaleString('id-ID');
                resultRange.textContent = 'Rentang estimasi: ' + minF + ' — ' + maxF;
            } else {
                resultDiv.classList.add('error');
                resultPrice.textContent = json.message || 'Gagal mendapatkan prediksi.';
                resultRange.textContent = '';
            }
        } catch (err) {
            resultDiv.className = 'predict-result show error';
            resultPrice.textContent = 'Tidak dapat terhubung ke ML Service. Pastikan Flask API berjalan.';
            resultRange.textContent = '';
        }

        btnPredict.textContent = '⚡ Prediksi';
        btnPredict.disabled = false;
    });

    // ── Charts ──
    const trendData   = {!! json_encode($trendHarga) !!};
    const wilayahData = {!! json_encode($prediksiWilayah) !!};
    const tipeData    = {!! json_encode($distribusiTipe) !!};

    // 1. Line Chart
    new Chart(document.getElementById('trendChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: trendData.labels,
            datasets: [
                {
                    label: 'Harga Aktual (Rp)',
                    data: trendData.actual,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    borderWidth: 2.5,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#2563eb',
                    pointRadius: 4
                },
                {
                    label: 'Harga Prediksi (Rp)',
                    data: trendData.predict,
                    borderColor: '#10b981',
                    borderWidth: 2.5,
                    borderDash: [6, 4],
                    tension: 0.4,
                    fill: false,
                    pointBackgroundColor: '#10b981',
                    pointRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, padding: 20 } }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(0) + 'Jt' },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Horizontal Bar Chart
    const barColors = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316','#06b6d4','#84cc16'];
    new Chart(document.getElementById('wilayahChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: wilayahData.labels,
            datasets: [{
                label: 'Rata-rata Harga',
                data: wilayahData.data,
                backgroundColor: barColors.slice(0, wilayahData.labels.length),
                borderRadius: 6,
                barThickness: 22
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(0) + 'Jt' },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                y: { grid: { display: false } }
            }
        }
    });

    // 3. Doughnut Chart
    const tipeLabels = tipeData.map(i => i.tipe || 'Lainnya');
    const tipeValues = tipeData.map(i => i.jumlah);
    new Chart(document.getElementById('tipeChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: tipeLabels,
            datasets: [{
                data: tipeValues,
                backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899'],
                hoverOffset: 6,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } }
            }
        }
    });
});
</script>
@endpush
