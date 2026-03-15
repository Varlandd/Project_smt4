@extends('admin.layouts.admin')

@section('title', 'Statistik — Admin RumahKu')
@section('page-title', 'Statistik')

@push('styles')
<style>
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-top: 24px;
    }
    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .stat-card h3 {
        margin: 0 0 18px;
        color: #1e293b;
        font-size: 1.05rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .full-width { grid-column: 1 / -1; }

    /* Highlight Row */
    .highlight-strip {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-top: 24px;
    }
    .highlight-item {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        text-align: center;
    }
    .highlight-item .hi-value {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f172a;
    }
    .highlight-item .hi-label {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 4px;
    }
    .highlight-item .hi-icon {
        font-size: 1.5rem;
        margin-bottom: 8px;
    }

    /* Table */
    .stat-table {
        width: 100%;
        border-collapse: collapse;
    }
    .stat-table thead th {
        text-align: left;
        padding: 10px 14px;
        font-size: 0.78rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }
    .stat-table tbody td {
        padding: 12px 14px;
        font-size: 0.9rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }
    .stat-table tbody tr:hover {
        background: #f8fafc;
    }
    .stat-table .rank {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.85rem;
    }
    .rank-1 { background: #fef3c7; color: #92400e; }
    .rank-2 { background: #e2e8f0; color: #475569; }
    .rank-3 { background: #fed7aa; color: #9a3412; }
    .rank-default { background: #f1f5f9; color: #64748b; }

    .fav-count {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #fef2f2;
        color: #dc2626;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.82rem;
    }

    .tipe-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    .tipe-rumah { background: #dbeafe; color: #1e40af; }
    .tipe-apartemen { background: #ede9fe; color: #6d28d9; }
    .tipe-ruko { background: #fef3c7; color: #92400e; }
    .tipe-default { background: #f1f5f9; color: #475569; }

    .empty-msg {
        text-align: center;
        padding: 32px 16px;
        color: #94a3b8;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .stat-grid { grid-template-columns: 1fr; }
        .highlight-strip { grid-template-columns: 1fr; }
    }

    /* ── Dark Mode ── */
    .dark-mode .stat-card {
        background: #1e293b;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    .dark-mode .stat-card h3 {
        color: #f1f5f9;
    }
    .dark-mode .highlight-item {
        background: #1e293b;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    .dark-mode .highlight-item .hi-value {
        color: #f1f5f9;
    }
    .dark-mode .highlight-item .hi-label {
        color: #94a3b8;
    }
    .dark-mode .stat-table thead th {
        color: #94a3b8;
        border-bottom-color: #334155;
    }
    .dark-mode .stat-table tbody td {
        color: #cbd5e1;
        border-bottom-color: #334155;
    }
    .dark-mode .stat-table tbody tr:hover {
        background: #0f172a;
    }
    .dark-mode .rank-1 { background: rgba(254,243,199,0.15); color: #fcd34d; }
    .dark-mode .rank-2 { background: rgba(226,232,240,0.1); color: #94a3b8; }
    .dark-mode .rank-3 { background: rgba(254,215,170,0.15); color: #fb923c; }
    .dark-mode .rank-default { background: rgba(241,245,249,0.08); color: #64748b; }
    .dark-mode .fav-count {
        background: rgba(254,242,242,0.1);
        color: #f87171;
    }
    .dark-mode .tipe-rumah { background: rgba(219,234,254,0.12); color: #93c5fd; }
    .dark-mode .tipe-apartemen { background: rgba(237,233,254,0.12); color: #a78bfa; }
    .dark-mode .tipe-ruko { background: rgba(254,243,199,0.12); color: #fcd34d; }
    .dark-mode .tipe-default { background: rgba(241,245,249,0.08); color: #94a3b8; }
    .dark-mode .empty-msg {
        color: #64748b;
    }
</style>
@endpush

@section('content')

{{-- Summary Cards (existing) --}}
<div class="admin-stats-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background: linear-gradient(135deg, #dbeafe, #93c5fd);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#1e40af" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-number">{{ $totalRumah }}</span>
            <span class="admin-stat-label">Total Rumah</span>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background: linear-gradient(135deg, #fce7f3, #f9a8d4);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#be185d" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
            </svg>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-number">{{ $totalUser }}</span>
            <span class="admin-stat-label">User Biasa</span>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background: linear-gradient(135deg, #fef3c7, #fcd34d);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-number">{{ $totalAdmin }}</span>
            <span class="admin-stat-label">Admin</span>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background: linear-gradient(135deg, #dcfce7, #86efac);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-number">{{ $totalFavorit }}</span>
            <span class="admin-stat-label">Total Favorit</span>
        </div>
    </div>
</div>

{{-- Highlight Stats --}}
<div class="highlight-strip">
    <div class="highlight-item">
        <div class="hi-icon">💰</div>
        <div class="hi-value">Rp {{ number_format($avgHarga ?? 0, 0, ',', '.') }}</div>
        <div class="hi-label">Rata-rata Harga Rumah</div>
    </div>
    <div class="highlight-item">
        <div class="hi-icon">📐</div>
        <div class="hi-value">{{ number_format($avgLuasTanah ?? 0, 0) }} m²</div>
        <div class="hi-label">Rata-rata Luas Tanah</div>
    </div>
    <div class="highlight-item">
        <div class="hi-icon">🏗️</div>
        <div class="hi-value">{{ number_format($avgLuasBangunan ?? 0, 0) }} m²</div>
        <div class="hi-label">Rata-rata Luas Bangunan</div>
    </div>
</div>

{{-- Charts Row 1 --}}
<div class="stat-grid">
    <div class="stat-card">
        <h3>📊 Distribusi Rumah per Lokasi</h3>
        <canvas id="chartLokasi" height="260"></canvas>
    </div>
    <div class="stat-card">
        <h3>💰 Rata-rata Harga per Tipe</h3>
        <canvas id="chartTipe" height="260"></canvas>
    </div>
</div>

{{-- Charts Row 2 --}}
<div class="stat-grid">
    <div class="stat-card">
        <h3>🏷️ Distribusi Segmen Harga</h3>
        <canvas id="chartSegmen" height="260"></canvas>
    </div>
    <div class="stat-card">
        <h3>👥 Pertumbuhan Pengguna Baru</h3>
        <canvas id="chartUser" height="260"></canvas>
    </div>
</div>

{{-- Tables --}}
<div class="stat-grid">
    {{-- Top Favorit --}}
    <div class="stat-card">
        <h3>❤️ Rumah Paling Banyak Difavoritkan</h3>
        @if($topFavorit->where('favorited_by_count', '>', 0)->count() > 0)
        <table class="stat-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Properti</th>
                    <th>Lokasi</th>
                    <th>Favorit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topFavorit->where('favorited_by_count', '>', 0) as $i => $r)
                <tr>
                    <td>
                        <span class="rank {{ $i === 0 ? 'rank-1' : ($i === 1 ? 'rank-2' : ($i === 2 ? 'rank-3' : 'rank-default')) }}">
                            {{ $i + 1 }}
                        </span>
                    </td>
                    <td><strong>{{ Str::limit($r->nama, 30) }}</strong></td>
                    <td>{{ $r->lokasi }}</td>
                    <td><span class="fav-count">♥ {{ $r->favorited_by_count }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-msg">Belum ada rumah yang difavoritkan oleh pengguna.</div>
        @endif
    </div>

    {{-- Properti Terbaru --}}
    <div class="stat-card">
        <h3>🆕 Properti Terbaru Ditambahkan</h3>
        @if($rumahTerbaru->count() > 0)
        <table class="stat-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tipe</th>
                    <th>Harga</th>
                    <th>Ditambahkan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rumahTerbaru as $r)
                <tr>
                    <td><strong>{{ Str::limit($r->nama, 25) }}</strong></td>
                    <td>
                        @php
                            $tipeClass = match(strtolower($r->tipe)) {
                                'rumah' => 'tipe-rumah',
                                'apartemen' => 'tipe-apartemen',
                                'ruko' => 'tipe-ruko',
                                default => 'tipe-default'
                            };
                        @endphp
                        <span class="tipe-badge {{ $tipeClass }}">{{ $r->tipe }}</span>
                    </td>
                    <td>Rp {{ number_format($r->harga, 0, ',', '.') }}</td>
                    <td>{{ $r->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-msg">Belum ada data properti.</div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const colors = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316','#06b6d4','#84cc16'];

    // 1. Distribusi per Lokasi (Horizontal Bar)
    const lokasiData = {!! json_encode($perLokasi) !!};
    new Chart(document.getElementById('chartLokasi').getContext('2d'), {
        type: 'bar',
        data: {
            labels: lokasiData.map(i => i.lokasi),
            datasets: [{
                label: 'Jumlah',
                data: lokasiData.map(i => i.jumlah),
                backgroundColor: colors.slice(0, lokasiData.length),
                borderRadius: 6,
                barThickness: 20
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { stepSize: 1 } },
                y: { grid: { display: false } }
            }
        }
    });

    // 2. Rata-rata Harga per Tipe (Vertical Bar)
    const tipeData = {!! json_encode($perTipe) !!};
    new Chart(document.getElementById('chartTipe').getContext('2d'), {
        type: 'bar',
        data: {
            labels: tipeData.map(i => i.tipe || 'Lainnya'),
            datasets: [{
                label: 'Rata-rata Harga',
                data: tipeData.map(i => Math.round(i.avg_harga)),
                backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6'],
                borderRadius: 6,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(0) + 'Jt' },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // 3. Segmen Harga (Doughnut)
    const segmenData = {!! json_encode($segmenHarga) !!};
    new Chart(document.getElementById('chartSegmen').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(segmenData),
            datasets: [{
                data: Object.values(segmenData),
                backgroundColor: ['#10b981','#3b82f6','#f59e0b','#ef4444'],
                hoverOffset: 6,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            cutout: '60%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 14 } }
            }
        }
    });

    // 4. User Growth (Line)
    const userData = {!! json_encode($userPerBulan) !!};
    new Chart(document.getElementById('chartUser').getContext('2d'), {
        type: 'line',
        data: {
            labels: userData.map(i => i.bulan),
            datasets: [{
                label: 'Pengguna Baru',
                data: userData.map(i => i.jumlah),
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139,92,246,0.1)',
                borderWidth: 2.5,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#8b5cf6',
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endpush
