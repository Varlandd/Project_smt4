<nav class="navbar">
    <a href="{{ url('/') }}" class="nav-logo">
        <div class="nav-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </div>
        <span class="logo-text">Rumah<strong>Ku</strong></span>
    </a>

    <div class="nav-links">
        <a href="{{ url('/#features') }}">Fitur</a>
        <a href="{{ url('/#how') }}">Cara Kerja</a>
        <a href="{{ url('/#calculator') }}">Kalkulator</a>
        <a href="{{ url('/#contact') }}" class="nav-cta">Cari Rumah</a>

        {{-- Auth Links --}}
        @auth
            <div class="nav-user" id="navUser">
                <div class="nav-user-wrapper">
                    <a href="{{ route('profile') }}" class="nav-user-link">
                        <div class="nav-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        <span class="nav-user-name">{{ Auth::user()->name }}</span>
                        @if(Auth::user()->isAdmin())
                            <span class="admin-badge">Admin</span>
                        @endif
                    </a>
                    <button class="nav-user-btn" id="userDropdownBtn" type="button" aria-label="Buka menu pengguna">
                        <svg class="nav-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </button>
                </div>
                <div class="dropdown-menu" id="userDropdownMenu">
                    <a href="{{ route('profile') }}" class="dropdown-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/>
                            <path d="M6 20v-1c0-2.21 3.58-4 6-4s6 1.79 6 4v1"/>
                        </svg>
                        Profil Saya
                    </a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7"/>
                                <rect x="14" y="3" width="7" height="7"/>
                                <rect x="14" y="14" width="7" height="7"/>
                                <rect x="3" y="14" width="7" height="7"/>
                            </svg>
                            Admin Panel
                        </a>
                    @endif
                    <a href="{{ route('properti.browse') }}" class="dropdown-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="M21 21l-4.35-4.35"/>
                        </svg>
                        Jelajahi Properti
                    </a>
                    <a href="{{ route('favorit.index') }}" class="dropdown-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                        Favorit Saya
                    </a>
                    {{-- HIDDEN: Prediksi Harga, Rekomendasi, Bandingkan dropdown items
                    <a href="{{ route('prediksi') }}" class="dropdown-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                        Prediksi Harga
                    </a>
                    <a href="{{ route('rekomendasi') }}" class="dropdown-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        Rekomendasi
                    </a>
                    <a href="{{ route('bandingkan') }}" class="dropdown-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                        Bandingkan
                    </a>
                    --}}
                    <a href="{{ route('ml.test') }}" class="dropdown-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                        </svg>
                        Test ML
                    </a>
                    <a href="{{ route('dashboard') }}" class="dropdown-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        Dashboard
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item dropdown-item-danger">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="nav-auth">
                <a href="{{ route('login') }}" class="nav-btn-login">Masuk</a>
                <a href="{{ route('register') }}" class="nav-btn-register">Daftar</a>
            </div>
        @endauth
    </div>

    <button class="nav-hamburger" id="hamburgerBtn" aria-label="Menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="6" x2="21" y2="6"/>
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
        </svg>
    </button>
</nav>

<div class="mobile-menu" id="mobileMenu">
    <a href="{{ url('/#features') }}">Fitur</a>
    <a href="{{ url('/#how') }}">Cara Kerja</a>
    <a href="{{ url('/#calculator') }}">Kalkulator</a>
    <a href="{{ url('/#contact') }}">Cari Rumah</a>

    @auth
        <div class="mobile-divider"></div>
        <a href="{{ route('profile') }}">👤 Profil Saya</a>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}">🛡️ Admin Panel</a>
        @endif
        <a href="{{ route('ml.test') }}">🧪 Test ML</a>
        <a href="{{ route('dashboard') }}">📊 Dashboard</a>
        <form action="{{ route('logout') }}" method="POST" style="margin:0;padding:0;">
            @csrf
            <button type="submit" class="mobile-logout-btn">🚪 Keluar</button>
        </form>
    @else
        <div class="mobile-divider"></div>
        <a href="{{ route('login') }}">Masuk</a>
        <a href="{{ route('register') }}" class="mobile-register">Daftar</a>
    @endauth
</div>
