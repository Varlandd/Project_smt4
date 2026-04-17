@extends('layouts.app')

@section('title', 'Bandingkan Properti — RumahKu')

@push('styles')
<style>
    .compare-page {
        min-height: 100vh;
        padding: 6rem 2rem 4rem;
        background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 50%, #ecfdf5 100%);
    }
    .compare-container { max-width: 1100px; margin: 0 auto; }

    .compare-header { text-align: center; margin-bottom: 2rem; }
    .compare-header h1 { font-size: 1.8rem; font-weight: 800; margin-bottom: .4rem; }
    .compare-header p { color: var(--text-soft); font-size: .95rem; }

    /* Selector */
    .selector-card {
        background: white; border-radius: 20px; padding: 2rem;
        border: 1px solid var(--border); margin-bottom: 2.5rem;
    }
    .selector-card h2 { font-size: 1.1rem; font-weight: 700; margin-bottom: 1.25rem; }
    .selector-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .selector-slot {
        border: 2px dashed var(--border); border-radius: 14px; padding: 1.2rem;
        text-align: center; transition: all .2s; position: relative;
        min-height: 180px; display: flex; flex-direction: column; align-items: center; justify-content: center;
    }
    .selector-slot.filled { border-style: solid; border-color: var(--primary); background: var(--primary-light); }
    .selector-slot select {
        width: 100%; padding: .6rem .8rem; border: 1.5px solid var(--border);
        border-radius: 8px; font-family: inherit; font-size: .85rem; color: var(--text-dark);
        background: white; margin-bottom: .5rem;
    }
    .slot-label { font-size: .78rem; font-weight: 700; color: var(--text-soft); text-transform: uppercase; letter-spacing: .04em; margin-bottom: .8rem; }
    .slot-name { font-size: .95rem; font-weight: 700; color: var(--text-dark); margin-top: .5rem; }
    .slot-loc { font-size: .8rem; color: var(--text-soft); }
    .slot-remove {
        position: absolute; top: 8px; right: 8px;
        width: 24px; height: 24px; border-radius: 6px;
        background: #fef2f2; border: 1px solid #fecaca; color: #ef4444;
        cursor: pointer; display: none; align-items: center; justify-content: center;
        font-size: .8rem; font-weight: 700;
    }
    .selector-slot.filled .slot-remove { display: flex; }

    /* Compare Table */
    .compare-table-wrap {
        background: white; border-radius: 20px; overflow: hidden;
        border: 1px solid var(--border); display: none;
    }
    .compare-table-wrap.show { display: block; }
    .compare-table {
        width: 100%; border-collapse: collapse;
    }
    .compare-table th, .compare-table td {
        padding: 1rem 1.2rem; text-align: center;
        border-bottom: 1px solid var(--border);
        font-size: .9rem;
    }
    .compare-table th {
        background: var(--bg-soft); font-weight: 700;
        color: var(--text-soft); font-size: .78rem;
        text-transform: uppercase; letter-spacing: .04em;
    }
    .compare-table td:first-child {
        text-align: left; font-weight: 600; color: var(--text-dark);
        background: #fafbfc; width: 160px;
    }
    .compare-table tr:last-child td { border-bottom: none; }
    .compare-table .prop-name { font-weight: 700; font-size: 1rem; }
    .compare-table .prop-price { font-weight: 800; color: var(--primary); font-size: 1.1rem; }
    .compare-table .best-value { background: #ecfdf5; color: #059669; font-weight: 700; }
    .compare-table .worst-value { background: #fef2f2; color: #dc2626; }

    .score-bar-wrap { display: flex; align-items: center; gap: .5rem; justify-content: center; }
    .score-bar { height: 8px; border-radius: 4px; background: #e2e8f0; flex: 1; max-width: 100px; }
    .score-bar-fill { height: 100%; border-radius: 4px; background: var(--primary); }
    .score-value { font-size: .88rem; font-weight: 800; color: var(--primary); }

    .compare-actions { text-align: center; margin-top: 1.5rem; }

    @media (max-width: 768px) {
        .compare-page { padding: 5rem 1rem 3rem; }
        .selector-grid { grid-template-columns: 1fr; }
        .compare-table-wrap { overflow-x: auto; }
    }
</style>
@endpush

@section('content')
<section class="compare-page">
    <div class="compare-container">
        <div class="compare-header">
            <h1>⚖️ Bandingkan Properti</h1>
            <p>Pilih hingga 3 properti untuk dibandingkan secara berdampingan</p>
        </div>

        <div class="selector-card">
            <h2>📌 Pilih Properti untuk Dibandingkan</h2>
            <div class="selector-grid">
                @for($i = 1; $i <= 3; $i++)
                <div class="selector-slot" id="slot{{ $i }}">
                    <div class="slot-label">Properti {{ $i }}</div>
                    <select class="prop-select" data-slot="{{ $i }}">
                        <option value="">— Pilih Properti —</option>
                        @foreach($rumahs as $rumah)
                            <option value="{{ $rumah->_id }}">{{ $rumah->nama }} ({{ $rumah->lokasi }})</option>
                        @endforeach
                    </select>
                    <div class="slot-name"></div>
                    <div class="slot-loc"></div>
                    <button class="slot-remove" title="Hapus">✕</button>
                </div>
                @endfor
            </div>
            <button class="btn btn-primary btn-block" id="compareBtn">
                ⚖️ Bandingkan Sekarang
            </button>
        </div>

        <div class="compare-table-wrap" id="compareResult">
            <table class="compare-table" id="compareTable">
                <thead><tr id="tableHead"></tr></thead>
                <tbody id="tableBody"></tbody>
            </table>
        </div>

        <div class="compare-actions" id="compareActions" style="display:none;">
            <a href="{{ route('rekomendasi') }}" class="btn btn-primary">🎯 Lihat Rekomendasi AI →</a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const allProperties = @json($rumahs);
    const propMap = {};
    allProperties.forEach(p => propMap[p._id] = p);

    const formatRp = (num) => 'Rp ' + Math.round(num).toLocaleString('id-ID');

    // Handle selector
    document.querySelectorAll('.prop-select').forEach(sel => {
        sel.addEventListener('change', function() {
            const slot = this.closest('.selector-slot');
            const id = this.value;

            if (id && propMap[id]) {
                const p = propMap[id];
                slot.classList.add('filled');
                slot.querySelector('.slot-name').textContent = p.nama;
                slot.querySelector('.slot-loc').textContent = p.lokasi;
            } else {
                slot.classList.remove('filled');
                slot.querySelector('.slot-name').textContent = '';
                slot.querySelector('.slot-loc').textContent = '';
            }
        });
    });

    // Remove button
    document.querySelectorAll('.slot-remove').forEach(btn => {
        btn.addEventListener('click', function() {
            const slot = this.closest('.selector-slot');
            const sel = slot.querySelector('select');
            sel.value = '';
            slot.classList.remove('filled');
            slot.querySelector('.slot-name').textContent = '';
            slot.querySelector('.slot-loc').textContent = '';
        });
    });

    // Compare
    document.getElementById('compareBtn').addEventListener('click', function() {
        const selected = [];
        document.querySelectorAll('.prop-select').forEach(sel => {
            if (sel.value && propMap[sel.value]) {
                selected.push(propMap[sel.value]);
            }
        });

        if (selected.length < 2) {
            alert('Pilih minimal 2 properti untuk dibandingkan!');
            return;
        }

        // Build comparison table
        const criteriaRows = [
            { label: 'Nama', key: 'nama', format: v => `<span class="prop-name">${v}</span>` },
            { label: 'Lokasi', key: 'lokasi', format: v => v },
            { label: 'Tipe', key: 'tipe', format: v => v || '-' },
            { label: 'Harga', key: 'harga', format: v => `<span class="prop-price">${formatRp(v)}</span>`, best: 'min' },
            { label: 'Luas Tanah', key: 'luas_tanah', format: v => `${v || 0} m²`, best: 'max' },
            { label: 'Luas Bangunan', key: 'luas_bangunan', format: v => `${v || 0} m²`, best: 'max' },
            { label: 'Kamar Tidur', key: 'kamar_tidur', format: v => `${v || 0} KT`, best: 'max' },
            { label: 'Kamar Mandi', key: 'kamar_mandi', format: v => `${v || 0} KM`, best: 'max' },
            { label: 'Harga/m² Tanah', key: '_price_m2', format: v => formatRp(v), best: 'min', compute: p => p.luas_tanah ? Math.round(p.harga / p.luas_tanah) : 0 },
        ];

        // Compute derived values
        selected.forEach(p => {
            p._price_m2 = p.luas_tanah ? Math.round(p.harga / p.luas_tanah) : 0;
        });

        // Header
        let headHtml = '<th>Kriteria</th>';
        selected.forEach(p => headHtml += `<th>${p.nama}</th>`);
        document.getElementById('tableHead').innerHTML = headHtml;

        // Body
        let bodyHtml = '';
        criteriaRows.forEach(row => {
            bodyHtml += '<tr>';
            bodyHtml += `<td>${row.label}</td>`;

            const values = selected.map(p => row.compute ? row.compute(p) : (p[row.key] || 0));

            selected.forEach((p, i) => {
                const val = row.compute ? row.compute(p) : (p[row.key] || 0);
                let cls = '';

                if (row.best && typeof val === 'number') {
                    const numValues = values.filter(v => typeof v === 'number' && v > 0);
                    if (numValues.length >= 2) {
                        const bestVal = row.best === 'max' ? Math.max(...numValues) : Math.min(...numValues);
                        const worstVal = row.best === 'max' ? Math.min(...numValues) : Math.max(...numValues);
                        if (val === bestVal && val !== worstVal) cls = 'best-value';
                        else if (val === worstVal && val !== bestVal) cls = 'worst-value';
                    }
                }

                bodyHtml += `<td class="${cls}">${row.format(val)}</td>`;
            });

            bodyHtml += '</tr>';
        });

        // Overall score row (simple weighted score)
        const maxH = Math.max(...selected.map(p => p.harga || 1));
        const minH = Math.min(...selected.map(p => p.harga || 1));
        const maxLT = Math.max(...selected.map(p => p.luas_tanah || 1));
        const maxLB = Math.max(...selected.map(p => p.luas_bangunan || 1));
        const maxKT = Math.max(...selected.map(p => p.kamar_tidur || 1));
        const maxKM = Math.max(...selected.map(p => p.kamar_mandi || 1));

        bodyHtml += '<tr><td style="font-weight:800;">⭐ Skor Kelayakan</td>';
        selected.forEach(p => {
            const score = (
                0.30 * (minH / (p.harga || 1)) +
                0.20 * ((p.luas_tanah || 0) / maxLT) +
                0.20 * ((p.luas_bangunan || 0) / maxLB) +
                0.15 * ((p.kamar_tidur || 0) / maxKT) +
                0.15 * ((p.kamar_mandi || 0) / maxKM)
            );
            const pct = Math.round(score * 100);
            bodyHtml += `<td>
                <div class="score-bar-wrap">
                    <div class="score-bar"><div class="score-bar-fill" style="width:${pct}%"></div></div>
                    <span class="score-value">${pct}</span>
                </div>
            </td>`;
        });
        bodyHtml += '</tr>';

        document.getElementById('tableBody').innerHTML = bodyHtml;

        const resultEl = document.getElementById('compareResult');
        resultEl.classList.add('show');
        document.getElementById('compareActions').style.display = 'block';
        resultEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});
</script>
@endpush
