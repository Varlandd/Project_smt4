@extends('layouts.app')

@section('title', 'Admin Dashboard — RumahKu')

@section('content')
<section class="dashboard-page">
    <div class="container">
        <div class="dashboard-header">
            <div class="dashboard-welcome">
                <h1>Admin Panel <span class="gradient-text">RumahKu</span> 🛡️</h1>
                <p>Kelola data rumah, pengguna, dan sistem rekomendasi dari sini.</p>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #e0e7ff, #c7d2fe);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#4338ca" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <h3>Data Rumah</h3>
                <p>Kelola data properti dan informasi rumah</p>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #fed7aa, #fdba74);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#9a3412" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h3>Data Pengguna</h3>
                <p>Kelola akun pengguna dan hak akses</p>
            </div>

            <div class="dashboard-card">
                <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10"/>
                        <line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                </div>
                <h3>Statistik</h3>
                <p>Lihat statistik penggunaan sistem</p>
            </div>
        </div>
    </div>
</section>
@endsection
