<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            {{-- Kolom 1: Branding --}}
            <div class="footer-col footer-about">
                <a href="{{ url('/') }}" class="nav-logo" style="margin-bottom: 1rem; display: inline-flex;">
                    <div class="nav-icon" style="width: 34px; height: 34px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                    </div>
                    <span class="logo-text" style="color: white;">Rumah<strong style="color: #14b8a6;">Ku</strong></span>
                </a>
                <p class="footer-desc">
                    Sistem Pendukung Keputusan berbasis AI yang membantu menemukan hunian impian dengan algoritma MLR & SAW/TOPSIS.
                </p>
                <div class="footer-social">
                    <a href="#" class="social-link">IG</a>
                    <a href="#" class="social-link">FB</a>
                    <a href="#" class="social-link">TW</a>
                    <a href="#" class="social-link">YT</a>
                </div>
            </div>

            {{-- Kolom 2: Navigasi --}}
            <div class="footer-col">
                <h4 class="footer-title">Navigasi</h4>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/#features') }}">Fitur Utama</a></li>
                    <li><a href="{{ url('/#how') }}">Cara Kerja</a></li>
                    <li><a href="{{ url('/#calculator') }}">Kalkulator Budget</a></li>
                    <li><a href="{{ url('/#contact') }}">Hubungi Kami</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Layanan --}}
            <div class="footer-col">
                <h4 class="footer-title">Layanan AI</h4>
                <ul class="footer-links">
                    @auth
                    <li><a href="{{ route('prediksi') }}">Prediksi Harga</a></li>
                    <li><a href="{{ route('rekomendasi') }}">Rekomendasi SAW</a></li>
                    <li><a href="{{ route('bandingkan') }}">Bandingkan Properti</a></li>
                    <li><a href="{{ route('properti.browse') }}">Katalog Rumah</a></li>
                    @else
                    <li><a href="{{ route('login') }}">Prediksi Harga</a></li>
                    <li><a href="{{ route('login') }}">Rekomendasi SAW</a></li>
                    <li><a href="{{ route('login') }}">Bandingkan Properti</a></li>
                    <li><a href="{{ route('register') }}">Daftar Akun</a></li>
                    @endauth
                </ul>
            </div>

            {{-- Kolom 4: Kontak --}}
            <div class="footer-col">
                <h4 class="footer-title">Kontak</h4>
                <ul class="footer-contact">
                    <li>
                        <span>📍</span>
                        <span>Kampus Jember, Jawa Timur</span>
                    </li>
                    <li>
                        <span>📧</span>
                        <span>info@rumahku.id</span>
                    </li>
                    <li>
                        <span>📞</span>
                        <span>+62 812-3456-7890</span>
                    </li>
                    <li>
                        <span>⏰</span>
                        <span>Senin - Jumat, 08:00 - 17:00</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© {{ date('Y') }} Rumah<strong style="color: #14b8a6;">Ku</strong>. Dibuat untuk Tugas Akhir Semester 4.</p>
            <div class="footer-legal">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
