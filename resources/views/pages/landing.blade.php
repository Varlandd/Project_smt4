@extends('layouts.app')

@section('title', 'RumahKu — Temukan Rumah Impian Sesuai Budget')

@section('content')

{{-- ═══ HERO ═══ --}}
<section id="hero" class="hero">
    <div class="hero-bg">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="hero-gradient"></div>
    </div>
    
    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-badge">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 11l3 3L22 4"/>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                </svg>
                Project Akhir — Sistem Pendukung Keputusan
            </div>

            <h1 class="hero-title">
                Temukan <span class="gradient-text">Rumah Impian</span><br/>
                Sesuai Kemampuan Finansial Kamu
            </h1>

            <p class="hero-subtitle">
                Sistem cerdas berbasis Multiple Linear Regression dan SAW/TOPSIS yang merekomendasikan rumah terbaik berdasarkan budget, lokasi, dan preferensi keluarga Anda.
            </p>

            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number">{{ $totalRumah ?? '500+' }}</div>
                    <div class="stat-label">Data Properti</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Akurasi Prediksi</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $totalLokasi ?? '10+' }}</div>
                    <div class="stat-label">Kota Tercakup</div>
                </div>
            </div>

            <div class="hero-actions">
                <a href="#calculator" class="btn btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <rect x="4" y="2" width="16" height="20" rx="2"/>
                        <line x1="8" y1="6" x2="16" y2="6"/>
                        <line x1="8" y1="10" x2="16" y2="10"/>
                        <line x1="8" y1="14" x2="12" y2="14"/>
                    </svg>
                    Hitung Budget Sekarang
                </a>
                <a href="#how" class="btn btn-outline">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M10 15l5-3-5-3v6z"/>
                    </svg>
                    Lihat Cara Kerja
                </a>
            </div>
        </div>

        {{-- ═══ HERO VISUAL: AI Dashboard Preview ═══ --}}
        <div class="hero-visual">
            <div class="dashboard-preview">
                {{-- Card 1: Prediksi AI --}}
                <div class="preview-card preview-card-main">
                    <div class="preview-card-header">
                        <div class="preview-dot green"></div>
                        <span>Prediksi AI — RumahKu</span>
                    </div>
                    <div class="preview-prediction">
                        <div class="preview-label">Estimasi Harga Rumah</div>
                        <div class="preview-price">Rp 850.000.000</div>
                        <div class="preview-range">
                            <span class="range-min">Min: Rp 720Jt</span>
                            <div class="range-bar"><div class="range-fill"></div></div>
                            <span class="range-max">Max: Rp 980Jt</span>
                        </div>
                    </div>
                    <div class="preview-specs">
                        <div class="spec-item">
                            <span class="spec-icon">📐</span>
                            <span>LT 120m²</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-icon">🏗️</span>
                            <span>LB 80m²</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-icon">🛏️</span>
                            <span>3 KT</span>
                        </div>
                        <div class="spec-item">
                            <span class="spec-icon">🚿</span>
                            <span>2 KM</span>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Skor Rekomendasi (Floating) --}}
                <div class="preview-card preview-card-float preview-score">
                    <div class="score-circle">
                        <svg viewBox="0 0 36 36" class="score-svg">
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="url(#scoreGrad)" stroke-width="3" stroke-dasharray="92, 100" stroke-linecap="round"/>
                            <defs><linearGradient id="scoreGrad"><stop offset="0%" stop-color="#0f766e"/><stop offset="100%" stop-color="#14b8a6"/></linearGradient></defs>
                        </svg>
                        <span class="score-text">92</span>
                    </div>
                    <div class="score-info">
                        <div class="score-label">Skor SAW</div>
                        <div class="score-status">Sangat Cocok ✨</div>
                    </div>
                </div>

                {{-- Card 3: Lokasi (Floating) --}}
                <div class="preview-card preview-card-float preview-location">
                    <span class="loc-icon">📍</span>
                    <div>
                        <div class="loc-city">Surabaya</div>
                        <div class="loc-detail">{{ $totalRumah ?? 125 }} properti tersedia</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ FEATURES ═══ --}}
<section id="features" class="section section-light">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Fitur Unggulan</span>
            <h2 class="section-title">Sistem Rekomendasi yang<br/>Memahami Kebutuhan Kamu</h2>
            <p class="section-desc">Kombinasi algoritma machine learning dan metode pengambilan keputusan untuk hasil yang optimal.</p>
        </div>

        <div class="features-grid">
            @php
            $features = [
                ['icon' => 'chart', 'title' => 'Prediksi Harga Akurat', 'desc' => 'Algoritma Multiple Linear Regression memprediksi harga rumah berdasarkan lokasi, luas tanah, bangunan, dan fasilitas sekitar.'],
                ['icon' => 'target', 'title' => 'Rekomendasi Personal', 'desc' => 'Metode SAW/TOPSIS memberikan ranking rumah terbaik yang sesuai dengan budget dan preferensi keluarga Anda.'],
                ['icon' => 'filter', 'title' => 'Filter Cerdas', 'desc' => 'Filter berdasarkan lokasi, range harga, tipe rumah, cicilan KPR, jarak ke fasilitas umum, dan banyak lagi.'],
                ['icon' => 'wallet', 'title' => 'Analisis Kemampuan Finansial', 'desc' => 'Hitung kemampuan cicilan berdasarkan pendapatan keluarga, tanggungan, dan profil finansial lengkap.'],
                ['icon' => 'compare', 'title' => 'Perbandingan Properti', 'desc' => 'Bandingkan hingga 3 properti secara berdampingan dengan skor kelayakan dari sistem.'],
                ['icon' => 'mobile', 'title' => 'Akses Web & Mobile', 'desc' => 'Tersedia dalam versi web dan mobile untuk kemudahan akses kapan saja, di mana saja.'],
            ];
            @endphp

            @foreach($features as $feature)
            <div class="feature-card fade-up">
                <div class="feature-icon feature-icon-{{ $feature['icon'] }}">
                    @include('components.icon-house', ['name' => $feature['icon']])
                </div>
                <h3 class="feature-title">{{ $feature['title'] }}</h3>
                <p class="feature-desc">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ HOW IT WORKS ═══ --}}
<section id="how" class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Cara Kerja</span>
            <h2 class="section-title">Temukan Rumah Impian<br/>dalam 4 Langkah Mudah</h2>
            <p class="section-desc">Proses yang simpel dan cepat untuk mendapatkan rekomendasi rumah terbaik.</p>
        </div>

        <div class="steps-container">
            @php
            $steps = [
                ['num' => '1', 'title' => 'Input Profil Finansial', 'desc' => 'Masukkan data pendapatan, tanggungan, cicilan existing, dan kemampuan uang muka.'],
                ['num' => '2', 'title' => 'Pilih Kriteria & Lokasi', 'desc' => 'Tentukan lokasi, tipe rumah, range harga, dan prioritas fasilitas yang diinginkan.'],
                ['num' => '3', 'title' => 'Sistem Analisis Data', 'desc' => 'Algoritma MLR memprediksi harga dan SAW/TOPSIS meranking properti sesuai kriteria.'],
                ['num' => '4', 'title' => 'Dapatkan Rekomendasi', 'desc' => 'Lihat daftar rumah terekomendasikan dengan skor kelayakan dan estimasi cicilan KPR.'],
            ];
            @endphp

            @foreach($steps as $index => $step)
            <div class="step-item fade-up" style="animation-delay: {{ $index * 0.1 }}s;">
                <div class="step-number">{{ $step['num'] }}</div>
                <div class="step-content">
                    <h3 class="step-title">{{ $step['title'] }}</h3>
                    <p class="step-desc">{{ $step['desc'] }}</p>
                </div>
                @if($index < count($steps) - 1)
                <div class="step-arrow">→</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ PROPERTI TERBARU ═══ --}}
<section id="properti" class="section section-light">
    <div class="container">
        <div class="section-header" style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap;">
            <div>
                <span class="section-tag">Properti Terbaru</span>
                <h2 class="section-title">Rumah Pilihan Terbaik<br/>dari Database Kami</h2>
                <p class="section-desc">Data properti terbaru yang siap dianalisis dan direkomendasikan oleh sistem AI kami.</p>
            </div>
            @auth
            <a href="{{ route('properti.browse') }}" class="btn btn-outline" style="margin-bottom: 1rem;">
                Lihat Semua Properti →
            </a>
            @endauth
        </div>

        <div class="properti-grid">
            @forelse($latestRumah ?? [] as $rumah)
            <div class="properti-card fade-up">
                <div class="properti-image">
                    @php
                        $foto = is_array($rumah->foto) ? ($rumah->foto[0] ?? null) : $rumah->foto;
                    @endphp
                    @if($foto && str_starts_with($foto, 'http'))
                        <img src="{{ $foto }}" alt="{{ $rumah->nama }}" loading="lazy">
                    @elseif($foto)
                        <img src="{{ asset('storage/' . $foto) }}" alt="{{ $rumah->nama }}" loading="lazy">
                    @else
                        <div class="properti-no-img">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                <polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                        </div>
                    @endif
                    <div class="properti-badge">{{ ucfirst($rumah->tipe ?? 'Rumah') }}</div>
                </div>
                <div class="properti-content">
                    <div class="properti-price">Rp {{ number_format($rumah->harga ?? 0, 0, ',', '.') }}</div>
                    <h3 class="properti-name">{{ $rumah->nama ?? 'Properti' }}</h3>
                    <div class="properti-loc">📍 {{ $rumah->lokasi ?? '-' }}</div>
                    <div class="properti-meta">
                        <span>📐 {{ $rumah->luas_tanah ?? '-' }}m²</span>
                        <span>🏗️ {{ $rumah->luas_bangunan ?? '-' }}m²</span>
                        <span>🛏️ {{ $rumah->kamar_tidur ?? '-' }} KT</span>
                        <span>🚿 {{ $rumah->kamar_mandi ?? '-' }} KM</span>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: var(--text-soft);">
                <p>Belum ada data properti. Silakan import data melalui Admin Panel.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

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
                    @auth
                    <a href="{{ route('properti.browse') }}" class="btn btn-primary">Cari Rumah Sesuai Budget</a>
                    @else
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar & Cari Rumah</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ CTA SECTION ═══ --}}
<section class="section section-cta">
    <div class="container">
        <div class="cta-content">
            <span class="section-tag section-tag-light">Gratis & Mudah</span>
            <h2 class="section-title text-white">Siap Temukan<br/>Rumah Impianmu?</h2>
            <p class="section-desc text-light">Gunakan sistem kami secara gratis untuk mendapatkan rekomendasi rumah terbaik sesuai budget dan preferensi kamu.</p>
            @auth
            <a href="{{ route('rekomendasi') }}" class="btn btn-white">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Mulai Rekomendasi AI
            </a>
            @else
            <a href="{{ route('register') }}" class="btn btn-white">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Daftar Gratis Sekarang
            </a>
            @endauth
        </div>
    </div>
</section>

{{-- ═══ CONTACT ═══ --}}
<section id="contact" class="section section-light">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-tag">Hubungi Kami</span>
            <h2 class="section-title">Ada Pertanyaan?<br/>Kami Siap Membantu</h2>
        </div>
        <div class="contact-layout">
            {{-- Form Pesan --}}
            <div class="feedback-card">
                <h3 class="form-card-title">✉️ Kirim Pesan</h3>
                <p class="form-card-subtitle">Sampaikan saran, masukan, atau pertanyaan Anda.</p>

                <form action="{{ route('kontak.send') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama Anda</label>
                        <input type="text" name="nama" placeholder="Masukkan nama" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="nama@email.com" required>
                    </div>
                    <div class="form-group">
                        <label>Subjek</label>
                        <input type="text" name="subjek" placeholder="Judul pesan" required>
                    </div>
                    <div class="form-group">
                        <label>Pesan</label>
                        <textarea name="pesan" rows="4" placeholder="Tuliskan pesan Anda..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Kirim Pesan</button>
                </form>

                @if(session('success'))
                    <div class="form-success" style="margin-top: 1rem;">{{ session('success') }}</div>
                @endif
            </div>

            {{-- Info Kontak --}}
            <div class="contact-info-section">
                <div class="contact-card-item">
                    <div class="contact-card-icon">📧</div>
                    <div>
                        <strong>Email</strong>
                        <p>info@rumahku.id</p>
                    </div>
                </div>
                <div class="contact-card-item">
                    <div class="contact-card-icon">📞</div>
                    <div>
                        <strong>WhatsApp</strong>
                        <p>+62 812-3456-7890</p>
                    </div>
                </div>
                <div class="contact-card-item">
                    <div class="contact-card-icon">📍</div>
                    <div>
                        <strong>Lokasi</strong>
                        <p>Kampus Jember, Jawa Timur</p>
                    </div>
                </div>
                <div class="contact-card-item">
                    <div class="contact-card-icon">⏰</div>
                    <div>
                        <strong>Jam Operasional</strong>
                        <p>Senin - Jumat, 08:00 - 17:00 WIB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.getElementById('hitungBtn')?.addEventListener('click', function() {
    const penghasilan = parseFloat(document.getElementById('penghasilan').value) || 0;
    const uangMuka = parseFloat(document.getElementById('uang_muka').value) || 0;
    const cicilanLain = parseFloat(document.getElementById('cicilan_lain').value) || 0;
    const tenor = parseInt(document.getElementById('tenor').value) || 15;

    if (penghasilan < 1000000) {
        alert('Mohon isi penghasilan dengan benar!');
        return;
    }

    const maxCicilan = (penghasilan * 0.3) - cicilanLain;
    
    if (maxCicilan <= 0) {
        alert('Cicilan lain terlalu besar! Kurangi cicilan existing terlebih dahulu.');
        return;
    }

    const bungaBulanan = 0.08 / 12;
    const jumlahBulan = tenor * 12;
    const pokokPinjaman = maxCicilan * ((1 - Math.pow(1 + bungaBulanan, -jumlahBulan)) / bungaBulanan);
    const budgetRumah = pokokPinjaman + uangMuka;

    document.getElementById('resultBudget').textContent = 'Rp ' + budgetRumah.toLocaleString('id-ID', {maximumFractionDigits: 0});
    document.getElementById('resultCicilan').textContent = 'Rp ' + maxCicilan.toLocaleString('id-ID', {maximumFractionDigits: 0});
    document.getElementById('resultSisa').textContent = 'Rp ' + ((penghasilan - maxCicilan - cicilanLain)).toLocaleString('id-ID', {maximumFractionDigits: 0});
    
    document.getElementById('calculatorResult').style.display = 'block';
    document.getElementById('calculatorResult').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
});
</script>
@endpush
