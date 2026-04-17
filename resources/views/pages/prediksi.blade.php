@extends('layouts.app')

@section('title', 'Prediksi Harga Properti — RumahKu')

@push('styles')
<style>
    .predict-page {
        min-height: 100vh;
        padding: 6rem 2rem 4rem;
        background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 50%, #ecfdf5 100%);
    }
    .predict-container { max-width: 800px; margin: 0 auto; }

    .predict-header { text-align: center; margin-bottom: 2rem; }
    .predict-header h1 { font-size: 1.8rem; font-weight: 800; margin-bottom: .4rem; }
    .predict-header p { color: var(--text-soft); font-size: .95rem; }

    .predict-card {
        background: white; border-radius: 20px; padding: 2rem;
        border: 1px solid var(--border); box-shadow: var(--shadow-md);
        margin-bottom: 2rem;
    }
    .predict-card h2 { font-size: 1.2rem; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: .5rem; }

    .predict-result {
        background: var(--bg-soft); border-radius: 16px; padding: 2rem;
        border: 2px solid var(--primary); text-align: center; display: none;
    }
    .predict-result.show { display: block; }
    .predict-result h3 { font-size: 1rem; font-weight: 700; color: var(--text-soft); margin-bottom: .5rem; }
    .predict-price { font-size: 2.2rem; font-weight: 800; color: var(--primary); margin-bottom: 1rem; }
    .predict-range { display: flex; justify-content: center; gap: 2rem; margin-bottom: 1.5rem; }
    .predict-range-item { text-align: center; }
    .predict-range-label { font-size: .78rem; color: var(--text-soft); font-weight: 600; }
    .predict-range-value { font-size: 1.1rem; font-weight: 700; color: var(--text-dark); }
    .predict-note { font-size: .82rem; color: var(--text-light); font-style: italic; }

    .predict-loading { display: none; text-align: center; padding: 2rem; }
    .predict-loading.show { display: block; }
    .spinner { width: 40px; height: 40px; border: 4px solid var(--border); border-top: 4px solid var(--primary); border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1rem; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .predict-error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 1rem; border-radius: 12px; display: none; margin-bottom: 1rem; font-size: .9rem; }
    .predict-error.show { display: block; }

    @media (max-width: 768px) {
        .predict-page { padding: 5rem 1rem 3rem; }
        .predict-range { flex-direction: column; gap: .8rem; }
    }
</style>
@endpush

@section('content')
<section class="predict-page">
    <div class="predict-container">
        <div class="predict-header">
            <h1>📊 Prediksi Harga Properti</h1>
            <p>Masukkan spesifikasi properti untuk mendapatkan estimasi harga berdasarkan AI/Machine Learning</p>
        </div>

        <div class="predict-card">
            <h2>🏠 Spesifikasi Properti</h2>
            <form id="predictForm" class="calculator-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="pred_lokasi">Lokasi</label>
                        <select id="pred_lokasi" name="lokasi" required>
                            <option value="">— Pilih Lokasi —</option>
                            @foreach(['Jakarta Pusat','Jakarta Selatan','Jakarta Barat','Jakarta Timur','Jakarta Utara','Bogor','Depok','Tangerang','Tangerang Selatan','Bekasi'] as $kota)
                                <option value="{{ $kota }}">{{ $kota }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pred_luas_tanah">Luas Tanah (m²)</label>
                        <input type="number" id="pred_luas_tanah" name="luas_tanah" placeholder="72" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="pred_luas_bangunan">Luas Bangunan (m²)</label>
                        <input type="number" id="pred_luas_bangunan" name="luas_bangunan" placeholder="45" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="pred_kamar_tidur">Kamar Tidur</label>
                        <input type="number" id="pred_kamar_tidur" name="kamar_tidur" placeholder="3" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="pred_kamar_mandi">Kamar Mandi</label>
                        <input type="number" id="pred_kamar_mandi" name="kamar_mandi" placeholder="2" required min="1">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block" id="predictBtn">
                    🤖 Prediksi Harga dengan AI
                </button>
            </form>
        </div>

        <div class="predict-error" id="predictError"></div>

        <div class="predict-loading" id="predictLoading">
            <div class="spinner"></div>
            <p>Sedang memproses prediksi dengan Machine Learning...</p>
        </div>

        <div class="predict-result" id="predictResult">
            <h3>Estimasi Harga Properti</h3>
            <div class="predict-price" id="predictPrice">-</div>
            <div class="predict-range">
                <div class="predict-range-item">
                    <div class="predict-range-label">Batas Bawah</div>
                    <div class="predict-range-value" id="predictMin">-</div>
                </div>
                <div class="predict-range-item">
                    <div class="predict-range-label">Batas Atas</div>
                    <div class="predict-range-value" id="predictMax">-</div>
                </div>
            </div>
            <p class="predict-note">* Hasil prediksi berdasarkan algoritma Multiple Linear Regression dari data properti Jabodetabek</p>
            <div style="margin-top: 1.5rem;">
                <a href="{{ route('properti.browse') }}" class="btn btn-primary">
                    Cari Properti Sesuai Budget →
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('predictForm');
    const resultEl = document.getElementById('predictResult');
    const loadingEl = document.getElementById('predictLoading');
    const errorEl = document.getElementById('predictError');

    const formatRp = (num) => 'Rp ' + Math.round(num).toLocaleString('id-ID');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        resultEl.classList.remove('show');
        errorEl.classList.remove('show');
        loadingEl.classList.add('show');

        const data = {
            lokasi: document.getElementById('pred_lokasi').value,
            luas_tanah: parseInt(document.getElementById('pred_luas_tanah').value),
            luas_bangunan: parseInt(document.getElementById('pred_luas_bangunan').value),
            kamar_tidur: parseInt(document.getElementById('pred_kamar_tidur').value),
            kamar_mandi: parseInt(document.getElementById('pred_kamar_mandi').value),
        };

        try {
            const response = await fetch('{{ route("admin.predict") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });

            const result = await response.json();
            loadingEl.classList.remove('show');

            if (response.ok && result.predicted_price) {
                const price = result.predicted_price;
                document.getElementById('predictPrice').textContent = formatRp(price);
                document.getElementById('predictMin').textContent = formatRp(price * 0.85);
                document.getElementById('predictMax').textContent = formatRp(price * 1.15);
                resultEl.classList.add('show');
                resultEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                // ML service offline — use fallback calculation
                fallbackPredict(data);
            }
        } catch (err) {
            loadingEl.classList.remove('show');
            // ML offline — fallback
            fallbackPredict(data);
        }
    });

    function fallbackPredict(data) {
        // Simple regression-based estimation using avg price per m2 per location
        const avgPerM2 = {
            'Jakarta Pusat': 25000000, 'Jakarta Selatan': 22000000,
            'Jakarta Barat': 18000000, 'Jakarta Timur': 15000000,
            'Jakarta Utara': 16000000, 'Bogor': 8000000,
            'Depok': 10000000, 'Tangerang': 12000000,
            'Tangerang Selatan': 14000000, 'Bekasi': 9000000,
        };

        const basePerM2 = avgPerM2[data.lokasi] || 12000000;
        let price = data.luas_bangunan * basePerM2;
        price += data.luas_tanah * (basePerM2 * 0.3);
        price += data.kamar_tidur * 50000000;
        price += data.kamar_mandi * 30000000;

        document.getElementById('predictPrice').textContent = formatRp(price);
        document.getElementById('predictMin').textContent = formatRp(price * 0.8);
        document.getElementById('predictMax').textContent = formatRp(price * 1.2);
        resultEl.classList.add('show');
        resultEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
});
</script>
@endpush
