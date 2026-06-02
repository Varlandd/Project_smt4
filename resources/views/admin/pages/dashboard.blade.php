@extends('admin.layouts.admin')

@section('title', 'Dashboard Admin — RumahKu')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* ── Welcome Banner ── */
    .welcome-banner {
        background: linear-gradient(135deg, #0f766e 0%, #14b8a6 50%, #2dd4bf 100%);
        border-radius: 18px;
        padding: 32px 36px;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        right: -30px; top: -40px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,0.07);
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        right: 80px; bottom: -50px;
        width: 140px; height: 140px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
    }
    .welcome-text h2 {
        margin: 0 0 6px;
        font-size: 1.45rem;
        font-weight: 800;
    }
    .welcome-text p {
        margin: 0;
        font-size: 0.9rem;
        color: rgba(255,255,255,0.8);
    }
    .welcome-meta {
        display: flex;
        gap: 12px;
        margin-top: 16px;
        z-index: 1;
        flex-wrap: wrap;
    }
    .wm-item {
        display: flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        padding: 7px 16px;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .wm-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
    }
    .wm-dot.online { background: #4ade80; box-shadow: 0 0 6px rgba(74,222,128,0.6); }
    .wm-dot.offline { background: #f87171; box-shadow: 0 0 6px rgba(248,113,113,0.6); }

    /* ── Price Stats Strip ── */
    .price-strip {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .price-item {
        background: #fff;
        border-radius: 14px;
        padding: 20px 22px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        display: flex;
        align-items: center;
        gap: 14px;
        transition: transform 0.2s;
    }
    .price-item:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
    .price-item .pi-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .price-item .pi-value {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }
    .price-item .pi-label {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 2px;
    }

    /* ── Dashboard Grid ── */
    .dash-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    .dash-grid-equal {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    .dash-card {
        background: #fff;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }
    .dash-card h3 {
        margin: 0 0 16px;
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .dash-card h3 a {
        margin-left: auto;
        font-size: 0.78rem;
        color: #0f766e;
        text-decoration: none;
        font-weight: 600;
    }
    .dash-card h3 a:hover { text-decoration: underline; }

    /* ── Tables ── */
    .dash-table {
        width: 100%;
        border-collapse: collapse;
    }
    .dash-table thead th {
        text-align: left;
        padding: 8px 12px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f1f5f9;
    }
    .dash-table tbody td {
        padding: 10px 12px;
        font-size: 0.85rem;
        color: #334155;
        border-bottom: 1px solid #f8fafc;
    }
    .dash-table tbody tr:hover { background: #f8fafc; }
    .fav-heart {
        color: #ef4444;
        font-weight: 700;
        font-size: 0.82rem;
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
    .tipe-badge-sm {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 5px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: capitalize;
    }
    .tipe-jual { background: #dbeafe; color: #1e40af; }
    .tipe-sewa { background: #ede9fe; color: #6d28d9; }
    .tipe-default { background: #f1f5f9; color: #64748b; }

    /* ── Quick Actions ── */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    .qa-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 16px;
        border-radius: 12px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
        color: #334155;
        background: #f8fafc;
    }
    .qa-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-color: #0f766e;
        color: #0f766e;
    }
    .qa-btn .qa-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    /* ── User Row ── */
    .user-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .user-avatar-sm {
        width: 32px; height: 32px;
        border-radius: 9px;
        background: linear-gradient(135deg, #0f766e, #14b8a6);
        color: white;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800;
        font-size: 0.72rem;
        flex-shrink: 0;
    }
    .role-badge-sm {
        padding: 2px 8px;
        border-radius: 5px;
        font-size: 0.7rem;
        font-weight: 700;
    }
    .role-admin-sm { background: #fef3c7; color: #92400e; }
    .role-user-sm  { background: #dbeafe; color: #1e40af; }

    .empty-msg {
        text-align: center;
        padding: 24px;
        color: #94a3b8;
        font-size: 0.88rem;
    }

    /* ── Dark Mode ── */
    .dark-mode .welcome-banner { background: linear-gradient(135deg, #134e4a 0%, #0f766e 50%, #115e59 100%); }
    .dark-mode .price-item { background: #1e293b; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
    .dark-mode .price-item .pi-value { color: #f1f5f9; }
    .dark-mode .price-item .pi-label { color: #94a3b8; }
    .dark-mode .dash-card { background: #1e293b; box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
    .dark-mode .dash-card h3 { color: #f1f5f9; }
    .dark-mode .dash-card h3 a { color: #5eead4; }
    .dark-mode .dash-table thead th { color: #94a3b8; border-bottom-color: #334155; }
    .dark-mode .dash-table tbody td { color: #cbd5e1; border-bottom-color: #334155; }
    .dark-mode .dash-table tbody tr:hover { background: #0f172a; }
    .dark-mode .price-col { color: #5eead4; }
    .dark-mode .qa-btn { background: #0f172a; border-color: #334155; color: #cbd5e1; }
    .dark-mode .qa-btn:hover { border-color: #14b8a6; color: #5eead4; }
    .dark-mode .tipe-jual { background: rgba(219,234,254,0.12); color: #93c5fd; }
    .dark-mode .tipe-sewa { background: rgba(237,233,254,0.12); color: #a78bfa; }
    .dark-mode .tipe-default { background: rgba(241,245,249,0.08); color: #94a3b8; }
    .dark-mode .role-admin-sm { background: rgba(254,243,199,0.15); color: #fcd34d; }
    .dark-mode .role-user-sm  { background: rgba(219,234,254,0.1); color: #93c5fd; }

    @media (max-width: 968px) {
        .dash-grid { grid-template-columns: 1fr; }
        .dash-grid-equal { grid-template-columns: 1fr; }
        .price-strip { grid-template-columns: 1fr; }
        .welcome-meta { flex-wrap: wrap; }
    }
</style>
@endpush

@section('content')

{{-- ═══ Welcome Banner ═══ --}}
<div class="welcome-banner">
    <div class="welcome-text">
        <h2>👋 Selamat Datang, {{ auth()->user()->name }}!</h2>
        <p>Berikut ringkasan terbaru dari sistem RumahKu hari ini.</p>
        <div class="welcome-meta">
            <div class="wm-item">
                <span class="wm-dot {{ $mlStatus }}"></span>
                ML Engine {{ $mlStatus === 'online' ? 'Aktif' : 'Offline' }}
            </div>
            <div class="wm-item">🏠 {{ number_format($totalRumah) }} Properti</div>
            <div class="wm-item">👥 {{ number_format($totalUser) }} Pengguna</div>
            @if($unreadPesan > 0)
            <div class="wm-item" style="background: rgba(239,68,68,0.25);">
                ✉️ {{ $unreadPesan }} Pesan Baru
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ═══ Stat Cards ═══ --}}
<div class="admin-stats-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background: linear-gradient(135deg, #dbeafe, #93c5fd);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#1e40af" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-number">{{ number_format($totalRumah) }}</span>
            <span class="admin-stat-label">Total Properti</span>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background: linear-gradient(135deg, #fce7f3, #f9a8d4);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#be185d" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-number">{{ number_format($totalUser) }}</span>
            <span class="admin-stat-label">Total Pengguna</span>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background: linear-gradient(135deg, #dcfce7, #86efac);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-number">{{ number_format($totalFavorit) }}</span>
            <span class="admin-stat-label">Total Favorit</span>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background: linear-gradient(135deg, #fef3c7, #fcd34d);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-number">{{ number_format($totalAdmin) }}</span>
            <span class="admin-stat-label">Total Admin</span>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background: linear-gradient(135deg, #ede9fe, #c084fc);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#6d28d9" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-number">{{ number_format($totalPesan) }}</span>
            <span class="admin-stat-label">Pesan Masuk @if($unreadPesan > 0) <small style="color: #dc2626;">({{ $unreadPesan }} Baru)</small> @endif</span>
        </div>
    </div>
</div>

{{-- ═══ Price Stats Strip ═══ --}}
<div class="price-strip">
    <div class="price-item">
        <div class="pi-icon" style="background: #dcfce7;">📈</div>
        <div>
            <div class="pi-value">Rp {{ number_format($hargaTertinggi ?? 0, 0, ',', '.') }}</div>
            <div class="pi-label">Harga Tertinggi</div>
        </div>
    </div>
    <div class="price-item">
        <div class="pi-icon" style="background: #fef3c7;">📉</div>
        <div>
            <div class="pi-value">Rp {{ number_format($hargaTerendah ?? 0, 0, ',', '.') }}</div>
            <div class="pi-label">Harga Terendah</div>
        </div>
    </div>
    <div class="price-item">
        <div class="pi-icon" style="background: #dbeafe;">💰</div>
        <div>
            <div class="pi-value">Rp {{ number_format($avgHarga ?? 0, 0, ',', '.') }}</div>
            <div class="pi-label">Rata-rata Harga</div>
        </div>
    </div>
</div>

{{-- ═══ Main Grid: Chart + Quick Actions ═══ --}}
<div class="dash-grid">
    <div class="dash-card">
        <h3>
            📈 Penambahan Properti Baru
        </h3>
        <canvas id="miniTrend" height="120"></canvas>
    </div>
    <div class="dash-card">
        <h3>⚡ Aksi Cepat</h3>
        <div class="quick-actions">
            <a href="{{ route('admin.rumah.create') }}" class="qa-btn">
                <div class="qa-icon" style="background: #dcfce7;">🏠</div>
                <span>Tambah Rumah</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="qa-btn">
                <div class="qa-icon" style="background: #dbeafe;">👥</div>
                <span>Kelola User</span>
            </a>
            <a href="{{ route('admin.pesan.index') }}" class="qa-btn">
                <div class="qa-icon" style="background: #fef3c7;">✉️</div>
                <span>Pesan Masuk</span>
            </a>
            <a href="{{ route('admin.statistik') }}" class="qa-btn">
                <div class="qa-icon" style="background: #ede9fe;">📊</div>
                <span>Lihat Statistik</span>
            </a>
        </div>
    </div>
</div>

{{-- ═══ Top Favorit + Properti Terbaru ═══ --}}
<div class="dash-grid-equal">
    <div class="dash-card">
        <h3>
            🏆 Properti Terpopuler
            <a href="{{ route('admin.rumah.index') }}">Semua →</a>
        </h3>
        @if($topFavorit->where('favorited_by_count', '>', 0)->count() > 0)
        <table class="dash-table">
            <thead>
                <tr><th>Properti</th><th>Kota</th><th>❤️</th></tr>
            </thead>
            <tbody>
                @foreach($topFavorit->where('favorited_by_count', '>', 0)->take(5) as $r)
                <tr>
                    <td><strong>{{ Str::limit($r->nama, 22) }}</strong></td>
                    <td>{{ $r->kota ?? '-' }}</td>
                    <td><span class="fav-heart">♥ {{ $r->favorited_by_count }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-msg">Belum ada rumah yang difavoritkan.</div>
        @endif
    </div>

    <div class="dash-card">
        <h3>
            🆕 Properti Terbaru
            <a href="{{ route('admin.rumah.index') }}">Semua →</a>
        </h3>
        @if($rumahTerbaru->count() > 0)
        <table class="dash-table">
            <thead>
                <tr><th>Nama</th><th>Tipe</th><th>Harga</th></tr>
            </thead>
            <tbody>
                @foreach($rumahTerbaru as $r)
                <tr>
                    <td><strong>{{ Str::limit($r->nama, 20) }}</strong></td>
                    <td>
                        @php
                            $tc = match(strtolower($r->tipe ?? '')) {
                                'jual' => 'tipe-jual', 'sewa' => 'tipe-sewa',
                                default => 'tipe-default'
                            };
                        @endphp
                        <span class="tipe-badge-sm {{ $tc }}">{{ $r->tipe ?? '-' }}</span>
                    </td>
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

{{-- ═══ Pengguna Terbaru + Distribusi Kota ═══ --}}
<div class="dash-grid" style="margin-top: 4px;">
    <div class="dash-card">
        <h3>
            👥 Pengguna Terbaru
            <a href="{{ route('admin.users.index') }}">Semua →</a>
        </h3>
        <table class="dash-table">
            <thead>
                <tr><th>Nama</th><th>Email</th><th>Role</th><th>Bergabung</th></tr>
            </thead>
            <tbody>
                @foreach($userTerbaru as $u)
                <tr>
                    <td>
                        <div class="user-row">
                            <div class="user-avatar-sm">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                            <strong>{{ $u->name }}</strong>
                        </div>
                    </td>
                    <td>{{ $u->email }}</td>
                    <td><span class="role-badge-sm role-{{ $u->role }}-sm">{{ ucfirst($u->role) }}</span></td>
                    <td class="time-col">{{ $u->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="dash-card">
        <h3>📍 Distribusi Kota</h3>
        <canvas id="miniPie" height="200"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const trendData = {!! json_encode($trendData) !!};
    const lokasiData = {!! json_encode($perLokasi) !!};
    const isDark = document.body.classList.contains('dark-mode');
    const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.04)';
    const textColor = isDark ? '#94a3b8' : '#64748b';

    // Mini Trend Line
    new Chart(document.getElementById('miniTrend').getContext('2d'), {
        type: 'line',
        data: {
            labels: trendData.labels,
            datasets: [{
                label: 'Properti Ditambahkan',
                data: trendData.data,
                borderColor: '#0f766e',
                backgroundColor: 'rgba(15,118,110,0.08)',
                borderWidth: 2.5,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#0f766e',
                pointRadius: 5,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: textColor },
                    grid: { color: gridColor }
                },
                x: { grid: { display: false }, ticks: { color: textColor } }
            }
        }
    });

    // Mini Pie (Distribusi Kota)
    const pieColors = ['#0f766e','#3b82f6','#f59e0b','#ef4444','#8b5cf6','#ec4899'];
    new Chart(document.getElementById('miniPie').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: lokasiData.map(i => i.lokasi),
            datasets: [{
                data: lokasiData.map(i => i.jumlah),
                backgroundColor: pieColors.slice(0, lokasiData.length),
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
                    labels: { usePointStyle: true, padding: 14, font: { size: 12 }, color: textColor }
                }
            }
        }
    });
});
</script>
@endpush
