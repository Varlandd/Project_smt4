@extends('layouts.app')

@section('title', 'RumahKu — Temukan Rumah Impian Sesuai Budget')

@section('content')

{{-- ═══ HERO ═══ --}}
<section id="hero" class="hero">
    <div class="hero-bg">
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
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Data Properti</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Akurasi Prediksi</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">10+</div>
                    <div class="stat-label">Kriteria Filter</div>
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

        <div class="hero-visual">
            <div class="house-card">
                <div class="house-card-image">🏠</div>
                <div class="house-card-content">
                    <div class="house-card-title">Rumah Type 45</div>
                    <div class="house-card-location">📍 Jember, Jawa Timur</div>
                    <div class="house-card-price">Rp 450 Juta</div>
                    <div class="house-card-match">✨ 92% Match</div>
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

{{-- ═══ CTA SECTION ═══ --}}
<section class="section section-cta">
    <div class="container">
        <div class="cta-content">
            <span class="section-tag section-tag-light">Gratis & Mudah</span>
            <h2 class="section-title text-white">Siap Temukan<br/>Rumah Impianmu?</h2>
            <p class="section-desc text-light">Gunakan sistem kami secara gratis untuk mendapatkan rekomendasi rumah terbaik sesuai budget dan preferensi kamu.</p>
            <a href="#contact" class="btn btn-white">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Mulai Pencarian Rumah
            </a>
        </div>
    </div>
</section>

{{-- ═══ CONTACT & FILTER FORM ═══ --}}
<section id="contact" class="section section-light">
    <div class="container">
        <div class="contact-layout">
            
            {{-- Info Kontak --}}
            <div class="contact-info">
                <span class="section-tag">Hubungi Kami</span>
                <h2 class="section-title">Butuh Bantuan?<br/>Tim Kami Siap Membantu</h2>
                <p class="section-desc" style="margin-bottom: 1.5rem;">
                    Hubungi tim pengembang atau langsung coba sistem rekomendasi rumah dengan mengisi form di samping.
                </p>

                <div class="contact-item">
                    <div class="contact-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    <div class="contact-text">
                        <strong>Email</strong>
                        rumahku.project@unej.ac.id
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div class="contact-text">
                        <strong>Kampus</strong>
                        Fakultas Ilmu Komputer<br/>Universitas Jember
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.5A2 2 0 0 1 3.6 1.36h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9a16 16 0 0 0 6.09 6.09l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </div>
                    <div class="contact-text">
                        <strong>WhatsApp</strong>
                        +62 812-3456-7890
                    </div>
                </div>
            </div>

            {{-- Form Pencarian --}}
            <div class="search-form-card">
                <h3 class="form-card-title">🏡 Cari Rumah Impianmu</h3>
                <p class="form-card-subtitle">Isi kriteria rumah yang kamu inginkan, sistem akan memberikan rekomendasi terbaik.</p>

                <form action="{{ route('rumah.search') }}" method="POST" id="searchForm">
                    @csrf

                    <div class="form-group">
                        <label for="nama">Nama Lengkap *</label>
                        <input type="text" id="nama" name="nama" placeholder="Nama kamu" value="{{ old('nama') }}" required/>
                        @error('nama') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" placeholder="email@example.com" value="{{ old('email') }}" required/>
                        </div>
                        <div class="form-group">
                            <label for="phone">No. WhatsApp</label>
                            <input type="tel" id="phone" name="phone" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lokasi">Lokasi/Kota *</label>
                        <select id="lokasi" name="lokasi" required>
                            <option value="">Pilih lokasi</option>
                            @foreach(['Jember','Surabaya','Malang','Banyuwangi','Sidoarjo','Gresik','Mojokerto','Pasuruan'] as $kota)
                                <option value="{{ $kota }}" {{ old('lokasi') == $kota ? 'selected' : '' }}>{{ $kota }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="budget_min">Budget Minimum</label>
                            <select id="budget_min" name="budget_min">
                                <option value="">Tidak dibatasi</option>
                                <option value="100000000">Rp 100 Juta</option>
                                <option value="200000000">Rp 200 Juta</option>
                                <option value="300000000">Rp 300 Juta</option>
                                <option value="400000000">Rp 400 Juta</option>
                                <option value="500000000">Rp 500 Juta</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="budget_max">Budget Maximum</label>
                            <select id="budget_max" name="budget_max">
                                <option value="">Tidak dibatasi</option>
                                <option value="300000000">Rp 300 Juta</option>
                                <option value="500000000">Rp 500 Juta</option>
                                <option value="750000000">Rp 750 Juta</option>
                                <option value="1000000000">Rp 1 Miliar</option>
                                <option value="1500000000">Rp 1.5 Miliar</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tipe">Tipe Rumah</label>
                        <select id="tipe" name="tipe">
                            <option value="">Semua tipe</option>
                            <option value="36">Type 36</option>
                            <option value="45">Type 45</option>
                            <option value="60">Type 60</option>
                            <option value="70">Type 70</option>
                            <option value="90">Type 90+</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Prioritas Fasilitas</label>
                        <div class="checkbox-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="fasilitas[]" value="sekolah"/>
                                <span>Dekat Sekolah</span>
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="fasilitas[]" value="rs"/>
                                <span>Dekat Rumah Sakit</span>
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="fasilitas[]" value="mall"/>
                                <span>Dekat Mall/Pusat Belanja</span>
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="fasilitas[]" value="transportasi"/>
                                <span>Akses Transportasi Umum</span>
                            </label>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="form-success">{{ session('success') }}</div>
                    @endif

                    <button type="submit" class="btn btn-primary btn-block">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="M21 21l-4.35-4.35"/>
                        </svg>
                        Cari Rumah Sekarang
                    </button>
                </form>
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

    // Asumsi: cicilan max 30% dari penghasilan setelah dikurangi cicilan lain
    const maxCicilan = (penghasilan * 0.3) - cicilanLain;
    
    if (maxCicilan <= 0) {
        alert('Cicilan lain terlalu besar! Kurangi cicilan existing terlebih dahulu.');
        return;
    }

    // Hitung budget rumah (asumsi bunga KPR 8% per tahun)
    const bungaBulanan = 0.08 / 12;
    const jumlahBulan = tenor * 12;
    const pokokPinjaman = maxCicilan * ((1 - Math.pow(1 + bungaBulanan, -jumlahBulan)) / bungaBulanan);
    const budgetRumah = pokokPinjaman + uangMuka;

    // Tampilkan hasil
    document.getElementById('resultBudget').textContent = 'Rp ' + budgetRumah.toLocaleString('id-ID', {maximumFractionDigits: 0});
    document.getElementById('resultCicilan').textContent = 'Rp ' + maxCicilan.toLocaleString('id-ID', {maximumFractionDigits: 0});
    document.getElementById('resultSisa').textContent = 'Rp ' + ((penghasilan - maxCicilan - cicilanLain)).toLocaleString('id-ID', {maximumFractionDigits: 0});
    
    document.getElementById('calculatorResult').style.display = 'block';
    document.getElementById('calculatorResult').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
});
</script>
@endpush
