@extends('admin.layouts.admin')

@section('title', 'Statistik — Admin RumahKu')
@section('page-title', 'Statistik')

@push('styles')
<style>
    /* ── Summary Grid (6 cards) ── */
    .stat-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-summary-card {
        background: #fff;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        display: flex;
        align-items: center;
        gap: 14px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    .ssc-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .ssc-value {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }
    .ssc-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
        margin-top: 2px;
    }

    /* ── Highlight Strip ── */
    .highlight-strip {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .highlight-item {
        background: #fff;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .highlight-item::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        border-radius: 14px 14px 0 0;
    }
    .highlight-item:nth-child(1)::before { background: linear-gradient(90deg, #0f766e, #14b8a6); }
    .highlight-item:nth-child(2)::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
    .highlight-item:nth-child(3)::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .highlight-item:nth-child(4)::before { background: linear-gradient(90deg, #ef4444, #f87171); }
    .highlight-item:nth-child(5)::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
    .hi-icon { font-size: 1.6rem; margin-bottom: 8px; }
    .hi-value { font-size: 1.2rem; font-weight: 800; color: #0f172a; }
    .hi-label { font-size: 0.78rem; color: #64748b; margin-top: 4px; }

    /* ── Chart Grid ── */
    .chart-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    .chart-card {
        background: #fff;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }
    .chart-card h3 {
        margin: 0 0 16px;
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .chart-card.full { grid-column: 1 / -1; }

    /* ── Data Table ── */
    .data-table-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    .data-card {
        background: #fff;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }
    .data-card h3 {
        margin: 0 0 16px;
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .stat-table {
        width: 100%;
        border-collapse: collapse;
    }
    .stat-table thead th {
        text-align: left;
        padding: 10px 12px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f1f5f9;
    }
    .stat-table tbody td {
        padding: 10px 12px;
        font-size: 0.85rem;
        color: #334155;
        border-bottom: 1px solid #f8fafc;
    }
    .stat-table tbody tr:hover { background: #f8fafc; }

    .rank {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px; height: 28px;
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
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
    }
    .price-col {
        font-weight: 700;
        color: #0f766e;
        white-space: nowrap;
    }
    .time-col {
        color: #94a3b8;
        font-size: 0.78rem;
        white-space: nowrap;
    }
    .tipe-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    .tipe-jual { background: #dbeafe; color: #1e40af; }
    .tipe-sewa { background: #ede9fe; color: #6d28d9; }
    .tipe-default { background: #f1f5f9; color: #475569; }

    .empty-msg {
        text-align: center;
        padding: 28px 16px;
        color: #94a3b8;
        font-size: 0.88rem;
    }

    /* ── Dark Mode ── */
    .dark-mode .stat-summary-card,
    .dark-mode .highlight-item,
    .dark-mode .chart-card,
    .dark-mode .data-card {
        background: #1e293b;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    .dark-mode .ssc-value,
    .dark-mode .hi-value { color: #f1f5f9; }
    .dark-mode .ssc-label,
    .dark-mode .hi-label { color: #94a3b8; }
    .dark-mode .chart-card h3,
    .dark-mode .data-card h3 { color: #f1f5f9; }
    .dark-mode .stat-table thead th { color: #94a3b8; border-bottom-color: #334155; }
    .dark-mode .stat-table tbody td { color: #cbd5e1; border-bottom-color: #334155; }
    .dark-mode .stat-table tbody tr:hover { background: #0f172a; }
    .dark-mode .price-col { color: #5eead4; }
    .dark-mode .rank-1 { background: rgba(254,243,199,0.15); color: #fcd34d; }
    .dark-mode .rank-2 { background: rgba(226,232,240,0.1); color: #94a3b8; }
    .dark-mode .rank-3 { background: rgba(254,215,170,0.15); color: #fb923c; }
    .dark-mode .rank-default { background: rgba(241,245,249,0.08); color: #64748b; }
    .dark-mode .fav-count { background: rgba(254,242,242,0.1); color: #f87171; }
    .dark-mode .tipe-jual { background: rgba(219,234,254,0.12); color: #93c5fd; }
    .dark-mode .tipe-sewa { background: rgba(237,233,254,0.12); color: #a78bfa; }
    .dark-mode .tipe-default { background: rgba(241,245,249,0.08); color: #94a3b8; }

    @media (max-width: 968px) {
        .stat-summary-grid { grid-template-columns: repeat(2, 1fr); }
        .chart-grid { grid-template-columns: 1fr; }
        .data-table-grid { grid-template-columns: 1fr; }
        .highlight-strip { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 600px) {
        .stat-summary-grid { grid-template-columns: 1fr; }
        .highlight-strip { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

{{-- ═══ SUMMARY STAT CARDS ═══ --}}
<div class="stat-summary-grid">
    <div class="stat-summary-card">
        <div class="ssc-icon" style="background: linear-gradient(135deg, #dbeafe, #93c5fd);">🏠</div>
        <div>
            <div class="ssc-value">{{ number_format($totalRumah) }}</div>
            <div class="ssc-label">Total Properti</div>
        </div>
    </div>
    <div class="stat-summary-card">
        <div class="ssc-icon" style="background: linear-gradient(135deg, #fce7f3, #f9a8d4);">👥</div>
        <div>
            <div class="ssc-value">{{ number_format($totalUser) }}</div>
            <div class="ssc-label">Pengguna</div>
        </div>
    </div>
    <div class="stat-summary-card">
        <div class="ssc-icon" style="background: linear-gradient(135deg, #dcfce7, #86efac);">❤️</div>
        <div>
            <div class="ssc-value">{{ number_format($totalFavorit) }}</div>
            <div class="ssc-label">Total Favorit</div>
        </div>
    </div>
    <div class="stat-summary-card">
        <div class="ssc-icon" style="background: linear-gradient(135deg, #fef3c7, #fcd34d);">🛡️</div>
        <div>
            <div class="ssc-value">{{ number_format($totalAdmin) }}</div>
            <div class="ssc-label">Admin</div>
        </div>
    </div>
    <div class="stat-summary-card">
        <div class="ssc-icon" style="background: linear-gradient(135deg, #ede9fe, #c084fc);">📍</div>
        <div>
            <div class="ssc-value">{{ $totalKota }}</div>
            <div class="ssc-label">Kota Tersedia</div>
        </div>
    </div>
    <div class="stat-summary-card">
        <div class="ssc-icon" style="background: linear-gradient(135deg, #e0f2fe, #7dd3fc);">💰</div>
        <div>
            <div class="ssc-value">Rp {{ number_format($avgHarga ?? 0, 0, ',', '.') }}</div>
            <div class="ssc-label">Rata-rata Harga</div>
        </div>
    </div>
</div>

{{-- ═══ HIGHLIGHT METRICS ═══ --}}
<div class="highlight-strip">
    <div class="highlight-item">
        <div class="hi-icon">📈</div>
        <div class="hi-value">Rp {{ number_format($hargaTertinggi ?? 0, 0, ',', '.') }}</div>
        <div class="hi-label">Harga Tertinggi</div>
    </div>
    <div class="highlight-item">
        <div class="hi-icon">📉</div>
        <div class="hi-value">Rp {{ number_format($hargaTerendah ?? 0, 0, ',', '.') }}</div>
        <div class="hi-label">Harga Terendah</div>
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
    <div class="highlight-item">
        <div class="hi-icon">🛏️</div>
        <div class="hi-value">{{ number_format($avgKamarTidur ?? 0, 1) }} KT / {{ number_format($avgKamarMandi ?? 0, 1) }} KM</div>
        <div class="hi-label">Rata-rata Kamar</div>
    </div>
</div>

{{-- ═══ CHARTS ROW 1: Distribusi Kota + Harga per Kota ═══ --}}
<div class="chart-grid">
    <div class="chart-card">
        <h3>📊 Distribusi Properti per Kota</h3>
        <canvas id="chartKota" height="280"></canvas>
    </div>
    <div class="chart-card">
        <h3>💰 Rata-rata Harga per Kota</h3>
        <canvas id="chartHargaKota" height="280"></canvas>
    </div>
</div>

{{-- ═══ CHARTS ROW 2: Segmen Harga + Kamar Tidur ═══ --}}
<div class="chart-grid">
    <div class="chart-card">
        <h3>🏷️ Distribusi Segmen Harga</h3>
        <canvas id="chartSegmen" height="280"></canvas>
    </div>
    <div class="chart-card">
        <h3>🛏️ Distribusi Jumlah Kamar Tidur</h3>
        <canvas id="chartKamar" height="280"></canvas>
    </div>
</div>

{{-- ═══ CHARTS ROW 3: User Growth + Harga per Tipe ═══ --}}
<div class="chart-grid">
    <div class="chart-card">
        <h3>👥 Pertumbuhan Pengguna Baru</h3>
        <canvas id="chartUser" height="280"></canvas>
    </div>
    <div class="chart-card">
        <h3>📊 Rata-rata Harga per Tipe</h3>
        <canvas id="chartTipe" height="280"></canvas>
    </div>
</div>

{{-- ═══ DATA TABLES ═══ --}}
<div class="data-table-grid">
    {{-- Top Favorit --}}
    <div class="data-card">
        <h3>❤️ Properti Paling Difavoritkan</h3>
        @if($topFavorit->where('favorited_by_count', '>', 0)->count() > 0)
        <table class="stat-table">
            <thead>
                <tr><th>#</th><th>Nama Properti</th><th>Kota</th><th>Harga</th><th>Favorit</th></tr>
            </thead>
            <tbody>
                @foreach($topFavorit->where('favorited_by_count', '>', 0) as $i => $r)
                <tr>
                    <td>
                        <span class="rank {{ $i === 0 ? 'rank-1' : ($i === 1 ? 'rank-2' : ($i === 2 ? 'rank-3' : 'rank-default')) }}">
                            {{ $i + 1 }}
                        </span>
                    </td>
                    <td><strong>{{ Str::limit($r->nama, 28) }}</strong></td>
                    <td>{{ $r->kota ?? '-' }}</td>
                    <td class="price-col">Rp {{ number_format($r->harga, 0, ',', '.') }}</td>
                    <td><span class="fav-count">♥ {{ $r->favorited_by_count }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-msg">Belum ada rumah yang difavoritkan oleh pengguna.</div>
        @endif
    </div>

    {{-- Properti Termahal --}}
    <div class="data-card">
        <h3>💎 Top 5 Properti Termahal</h3>
        @if($rumahTermahal->count() > 0)
        <table class="stat-table">
            <thead>
                <tr><th>#</th><th>Nama</th><th>Kota</th><th>Harga</th></tr>
            </thead>
            <tbody>
                @foreach($rumahTermahal as $i => $r)
                <tr>
                    <td>
                        <span class="rank {{ $i === 0 ? 'rank-1' : ($i === 1 ? 'rank-2' : ($i === 2 ? 'rank-3' : 'rank-default')) }}">
                            {{ $i + 1 }}
                        </span>
                    </td>
                    <td><strong>{{ Str::limit($r->nama, 28) }}</strong></td>
                    <td>{{ $r->kota ?? '-' }}</td>
                    <td class="price-col">Rp {{ number_format($r->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-msg">Belum ada data properti.</div>
        @endif
    </div>
</div>

{{-- Properti Terbaru --}}
<div class="data-table-grid">
    <div class="data-card" style="grid-column: 1 / -1;">
        <h3>🆕 Properti Terbaru Ditambahkan</h3>
        @if($rumahTerbaru->count() > 0)
        <table class="stat-table">
            <thead>
                <tr><th>Nama</th><th>Kota</th><th>Tipe</th><th>Harga</th><th>Luas</th><th>Kamar</th><th>Ditambahkan</th></tr>
            </thead>
            <tbody>
                @foreach($rumahTerbaru as $r)
                <tr>
                    <td><strong>{{ Str::limit($r->nama, 25) }}</strong></td>
                    <td>{{ $r->kota ?? '-' }}</td>
                    <td>
                        @php
                            $tipeClass = match(strtolower($r->tipe ?? '')) {
                                'jual' => 'tipe-jual',
                                'sewa' => 'tipe-sewa',
                                default => 'tipe-default'
                            };
                        @endphp
                        <span class="tipe-badge {{ $tipeClass }}">{{ $r->tipe ?? '-' }}</span>
                    </td>
                    <td class="price-col">Rp {{ number_format($r->harga, 0, ',', '.') }}</td>
                    <td>{{ $r->luas_tanah ?? 0 }}/{{ $r->luas_bangunan ?? 0 }} m²</td>
                    <td>{{ $r->kamar_tidur ?? 0 }} KT / {{ $r->kamar_mandi ?? 0 }} KM</td>
                    <td class="time-col">{{ $r->created_at->diffForHumans() }}</td>
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
    const colors = ['#0f766e','#3b82f6','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316','#06b6d4','#84cc16'];
    const isDark = document.body.classList.contains('dark-mode');
    const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.04)';
    const textColor = isDark ? '#94a3b8' : '#64748b';

    // 1. Distribusi per Kota (Horizontal Bar)
    const kotaData = {!! json_encode($perLokasi) !!};
    new Chart(document.getElementById('chartKota').getContext('2d'), {
        type: 'bar',
        data: {
            labels: kotaData.map(i => i.lokasi),
            datasets: [{
                label: 'Jumlah Properti',
                data: kotaData.map(i => i.jumlah),
                backgroundColor: colors.slice(0, kotaData.length),
                borderRadius: 8,
                barThickness: 28
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.parsed.x} properti`
                    }
                }
            },
            scales: {
                x: { grid: { color: gridColor }, ticks: { stepSize: 50, color: textColor } },
                y: { grid: { display: false }, ticks: { color: textColor, font: { weight: 600 } } }
            }
        }
    });

    // 2. Rata-rata Harga per Kota (Vertical Bar)
    const hargaKota = {!! json_encode($perKotaHarga) !!};
    new Chart(document.getElementById('chartHargaKota').getContext('2d'), {
        type: 'bar',
        data: {
            labels: hargaKota.map(i => i.kota),
            datasets: [{
                label: 'Rata-rata Harga',
                data: hargaKota.map(i => Math.round(i.avg_harga)),
                backgroundColor: ['#0f766e','#3b82f6','#f59e0b','#ef4444','#8b5cf6','#ec4899'],
                borderRadius: 8,
                barThickness: 36
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + (ctx.parsed.y / 1000000000).toFixed(2) + ' M'
                    }
                }
            },
            scales: {
                y: {
                    ticks: { callback: v => 'Rp ' + (v/1000000000).toFixed(1) + 'M', color: textColor },
                    grid: { color: gridColor }
                },
                x: { grid: { display: false }, ticks: { color: textColor, font: { weight: 600 } } }
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
                hoverOffset: 8,
                borderWidth: 2,
                borderColor: isDark ? '#1e293b' : '#fff'
            }]
        },
        options: {
            responsive: true,
            cutout: '55%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, padding: 16, font: { size: 12 }, color: textColor }
                }
            }
        }
    });

    // 4. Distribusi Kamar Tidur (Polar Area)
    const kamarData = {!! json_encode($kamarTidur) !!};
    new Chart(document.getElementById('chartKamar').getContext('2d'), {
        type: 'polarArea',
        data: {
            labels: kamarData.map(i => i.kamar + ' KT'),
            datasets: [{
                data: kamarData.map(i => i.jumlah),
                backgroundColor: colors.slice(0, kamarData.length).map(c => c + '99'),
                borderColor: colors.slice(0, kamarData.length),
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, padding: 14, font: { size: 11 }, color: textColor }
                }
            },
            scales: {
                r: {
                    ticks: { display: false },
                    grid: { color: gridColor }
                }
            }
        }
    });

    // 5. User Growth (Line)
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
                pointRadius: 5,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, color: textColor }, grid: { color: gridColor } },
                x: { grid: { display: false }, ticks: { color: textColor } }
            }
        }
    });

    // 6. Rata-rata Harga per Tipe (Vertical Bar)
    const tipeData = {!! json_encode($perTipe) !!};
    new Chart(document.getElementById('chartTipe').getContext('2d'), {
        type: 'bar',
        data: {
            labels: tipeData.map(i => (i.tipe || 'Lainnya') + ' (' + i.jumlah + ')'),
            datasets: [{
                label: 'Rata-rata Harga',
                data: tipeData.map(i => Math.round(i.avg_harga)),
                backgroundColor: ['#0f766e','#3b82f6','#f59e0b','#ef4444','#8b5cf6'],
                borderRadius: 8,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + (ctx.parsed.y / 1000000000).toFixed(2) + ' M'
                    }
                }
            },
            scales: {
                y: {
                    ticks: { callback: v => 'Rp ' + (v/1000000000).toFixed(1) + 'M', color: textColor },
                    grid: { color: gridColor }
                },
                x: { grid: { display: false }, ticks: { color: textColor } }
            }
        }
    });
});
</script>
@endpush
