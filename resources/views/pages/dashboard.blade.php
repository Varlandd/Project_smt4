@extends('layouts.app')

@section('title', 'Dashboard — RumahKu')

@section('content')
<section class="dashboard-page">
    <div class="container">
        <div class="dashboard-header">
            <div class="dashboard-welcome">
                <h1>Selamat Datang, <span class="gradient-text">{{ Auth::user()->name }}</span> 👋</h1>
                <p>Gunakan sistem rekomendasi kami untuk menemukan rumah impian sesuai budget kamu.</p>
            </div>
        </div>

        @if(session('success'))
        <div class="form-success">{{ session('success') }}</div>
        @endif

        <div class="dashboard-grid">
            <a href="{{ url('/#calculator') }}" class="dashboard-card">
                <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#1e40af" stroke-width="2">
                        <rect x="4" y="2" width="16" height="20" rx="2"/>
                        <line x1="8" y1="6" x2="16" y2="6"/>
                        <line x1="8" y1="10" x2="16" y2="10"/>
                        <line x1="8" y1="14" x2="12" y2="14"/>
                    </svg>
                </div>
                <h3>Kalkulator Budget</h3>
                <p>Hitung kemampuan finansial kamu untuk beli rumah</p>
            </a>

            <a href="{{ url('/#contact') }}" class="dashboard-card">
                <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #fce7f3, #fbcfe8);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#be185d" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="M21 21l-4.35-4.35"/>
                    </svg>
                </div>
                <h3>Cari Rumah</h3>
                <p>Temukan rumah impian berdasarkan kriteria kamu</p>
            </a>

            <a href="{{ url('/#features') }}" class="dashboard-card">
                <div class="dashboard-card-icon" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <h3>Fitur Sistem</h3>
                <p>Pelajari fitur-fitur unggulan sistem rekomendasi</p>
            </a>
        </div>
    </div>
</section>
@endsection
