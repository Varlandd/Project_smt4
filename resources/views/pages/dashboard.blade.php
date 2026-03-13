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

        {{-- ═══ CALCULATOR SECTION ═══ --}}
<section id="calculator" class="section section-dark">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-tag section-tag-light">Kalkulator Budget</span>
            <h2 class="section-title text-white">Hitung Kemampuan<br/>Beli Rumah Kamu</h2>
            <p class="section-desc text-light">Isi data finansial untuk mengetahui range harga rumah yang sesuai dengan kemampuan kamu.</p>
        </div>

        <div class="calculator-card">
            <form id="calculatorForm" class="calculator-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="penghasilan">Penghasilan Keluarga/Bulan</label>
                        <div class="input-with-icon">
                            <span class="input-prefix">Rp</span>
                            <input type="number" id="penghasilan" name="penghasilan" placeholder="10.000.000" step="100000" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="uang_muka">Uang Muka Tersedia</label>
                        <div class="input-with-icon">
                            <span class="input-prefix">Rp</span>
                            <input type="number" id="uang_muka" name="uang_muka" placeholder="50.000.000" step="1000000"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cicilan_lain">Cicilan Bulanan Lain</label>
                        <div class="input-with-icon">
                            <span class="input-prefix">Rp</span>
                            <input type="number" id="cicilan_lain" name="cicilan_lain" placeholder="2.000.000" step="100000"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tenor">Tenor KPR (Tahun)</label>
                        <select id="tenor" name="tenor">
                            <option value="5">5 Tahun</option>
                            <option value="10">10 Tahun</option>
                            <option value="15" selected>15 Tahun</option>
                            <option value="20">20 Tahun</option>
                        </select>
                    </div>
                </div>

                <button type="button" class="btn btn-primary btn-block" id="hitungBtn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 11 12 14 22 4"/>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                    Hitung Kemampuan Saya
                </button>
            </form>

            <div class="calculator-result" id="calculatorResult" style="display: none;">
                <div class="result-header">
                    <h3>📊 Hasil Analisis Budget</h3>
                </div>
                <div class="result-grid">
                    <div class="result-item">
                        <div class="result-label">Budget Rumah</div>
                        <div class="result-value" id="resultBudget">-</div>
                    </div>
                    <div class="result-item">
                        <div class="result-label">Cicilan/Bulan</div>
                        <div class="result-value" id="resultCicilan">-</div>
                    </div>
                    <div class="result-item">
                        <div class="result-label">Sisa Pendapatan</div>
                        <div class="result-value" id="resultSisa">-</div>
                    </div>
                </div>
                <div class="result-action">
                    <a href="#contact" class="btn btn-primary">
                        Cari Rumah Sesuai Budget
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

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
