@extends('admin.layouts.admin')

@section('title', 'Dashboard Admin — RumahKu')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* ── Welcome Banner ── */
    .welcome-banner {
        background: linear-gradient(135deg, #0f766e, #14b8a6);
        border-radius: 16px;
        padding: 28px 32px;
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
        right: -20px;
        top: -30px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        right: 60px;
        bottom: -40px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255,255,255,0.05);
    }
    .welcome-text h2 {
        margin: 0 0 6px;
        font-size: 1.4rem;
        font-weight: 700;
    }
    .welcome-text p {
        margin: 0;
        font-size: 0.9rem;
        color: rgba(255,255,255,0.8);
    }
    .welcome-meta {
        display: flex;
        gap: 16px;
        margin-top: 14px;
        z-index: 1;
    }
    .welcome-meta .wm-item {
        display: flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.15);
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .welcome-meta .wm-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    .wm-dot.online { background: #4ade80; }
    .wm-dot.offline { background: #f87171; }

    /* ── Quick Price Stats ── */
    .price-strip {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .price-item {
        background: #fff;
        border-radius: 12px;
        padding: 18px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .price-item .pi-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .price-item .pi-value {
        font-size: 1.1rem;
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
        gap: 24px;
        margin-bottom: 24px;
    }
    .dash-card {
        background: #fff;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .dash-card h3 {
        margin: 0 0 18px;
        font-size: 1rem;
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
    .dash-table .fav-heart {
        color: #ef4444;
        font-weight: 700;
        font-size: 0.82rem;
    }
    .dash-table .price-col {
        font-weight: 700;
        color: #0f766e;
        white-space: nowrap;
    }
    .dash-table .time-col {
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
    .tipe-rumah { background: #dbeafe; color: #1e40af; }
    .tipe-apartemen { background: #ede9fe; color: #6d28d9; }
    .tipe-ruko { background: #fef3c7; color: #92400e; }
    .tipe-villa { background: #dcfce7; color: #15803d; }
    .tipe-subsidi { background: #fce7f3; color: #be185d; }
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
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    /* ── Two-col bottom ── */
    .dash-grid-equal {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .user-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .user-avatar-sm {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: linear-gradient(135deg, #0f766e, #14b8a6);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.7rem;
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
    .dark-mode .welcome-banner {
        background: linear-gradient(135deg, #134e4a, #0f766e);
    }
    .dark-mode .price-item {
        background: #1e293b;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    .dark-mode .price-item .pi-value { color: #f1f5f9; }
    .dark-mode .price-item .pi-label { color: #94a3b8; }
    .dark-mode .dash-card {
        background: #1e293b;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    .dark-mode .dash-card h3 { color: #f1f5f9; }
    .dark-mode .dash-card h3 a { color: #5eead4; }
    .dark-mode .dash-table thead th {
        color: #94a3b8;
        border-bottom-color: #334155;
    }
    .dark-mode .dash-table tbody td {
        color: #cbd5e1;
        border-bottom-color: #334155;
    }
    .dark-mode .dash-table tbody tr:hover { background: #0f172a; }
    .dark-mode .dash-table .price-col { color: #5eead4; }
    .dark-mode .qa-btn {
        background: #0f172a;
        border-color: #334155;
        color: #cbd5e1;
    }
    .dark-mode .qa-btn:hover {
        border-color: #14b8a6;
        color: #5eead4;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    .dark-mode .tipe-rumah { background: rgba(219,234,254,0.12); color: #93c5fd; }
    .dark-mode .tipe-apartemen { background: rgba(237,233,254,0.12); color: #a78bfa; }
    .dark-mode .tipe-ruko { background: rgba(254,243,199,0.12); color: #fcd34d; }
    .dark-mode .tipe-villa { background: rgba(220,252,231,0.12); color: #86efac; }
    .dark-mode .tipe-subsidi { background: rgba(252,231,243,0.12); color: #f9a8d4; }
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

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div class="welcome-text">
        <h2>👋 Selamat Datang, {{ auth()->user()->name }}!</h2>
        <p>Berikut ringkasan terbaru dari sistem RumahKu hari ini.</p>
        <div class="welcome-meta">
            <div class="wm-item">
                <span class="wm-dot {{ $mlStatus }}"></span>
                ML Engine {{ $mlStatus === 'online' ? 'Aktif' : 'Offline' }}
            </div>
            <div class="wm-item">🏠 {{ $totalRumah }} Properti</div>
            <div class="wm-item">👥 {{ $totalUser }} Pengguna</div>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
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
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-number">{{ $totalUser }}</span>
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
            <span class="admin-stat-number">{{ $totalFavorit }}</span>
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
            <span class="admin-stat-number">{{ $totalAdmin }}</span>
            <span class="admin-stat-label">Total Admin</span>
        </div>
    </div>
</div>

{{-- Price Stats Strip --}}
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

{{-- Main Grid: Chart + Quick Actions --}}
<div class="dash-grid">
    <div class="dash-card">
        <h3>
            📈 Tren Rata-rata Harga
            <a href="{{ route('admin.analitik') }}">Lihat Detail →</a>
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
            <a href="{{ route('admin.analitik') }}" class="qa-btn">
                <div class="qa-icon" style="background: #fef3c7;">🔮</div>
                <span>Prediksi Harga</span>
            </a>
            <a href="{{ route('admin.statistik') }}" class="qa-btn">
                <div class="qa-icon" style="background: #ede9fe;">📊</div>
                <span>Lihat Statistik</span>
            </a>
        </div>
    </div>
</div>

{{-- Top Favorit + Properti Terbaru --}}
<div class="dash-grid-equal">
    <div class="dash-card">
        <h3>
            🏆 Rumah Terpopuler
            <a href="{{ route('admin.rumah.index') }}">Semua →</a>
        </h3>
        @if($topFavorit->where('favorited_by_count', '>', 0)->count() > 0)
        <table class="dash-table">
            <thead>
                <tr><th>Properti</th><th>Lokasi</th><th>❤️</th></tr>
            </thead>
            <tbody>
                @foreach($topFavorit->where('favorited_by_count', '>', 0)->take(5) as $r)
                <tr>
                    <td><strong>{{ Str::limit($r->nama, 22) }}</strong></td>
                    <td>{{ $r->lokasi }}</td>
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
                                'rumah' => 'tipe-rumah', 'apartemen' => 'tipe-apartemen',
                                'ruko' => 'tipe-ruko', 'villa' => 'tipe-villa',
                                'subsidi' => 'tipe-subsidi', default => 'tipe-default'
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

{{-- Pengguna Terbaru --}}
<div class="dash-grid" style="margin-top: 24px;">
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
        <h3>📍 Distribusi Lokasi</h3>
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

    // Mini Trend Line
    new Chart(document.getElementById('miniTrend').getContext('2d'), {
        type: 'line',
        data: {
            labels: trendData.labels,
            datasets: [{
                label: 'Rata-rata (Jt)',
                data: trendData.data,
                borderColor: '#0f766e',
                backgroundColor: 'rgba(15,118,110,0.08)',
                borderWidth: 2.5,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#0f766e',
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    ticks: { callback: v => 'Rp ' + v + 'Jt' },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // Mini Pie (Distribusi Lokasi)
    const pieColors = ['#0f766e','#3b82f6','#f59e0b','#ef4444','#8b5cf6','#ec4899'];
    new Chart(document.getElementById('miniPie').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: lokasiData.map(i => i.lokasi),
            datasets: [{
                data: lokasiData.map(i => i.jumlah),
                backgroundColor: pieColors.slice(0, lokasiData.length),
                hoverOffset: 6,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            cutout: '60%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 11 } } }
            }
        }
    });
});
</script>
@endpush
