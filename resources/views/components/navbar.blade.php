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
        @auth
        <a href="{{ route('properti.browse') }}">Jelajahi Properti</a>
        <a href="{{ route('favorit.index') }}">Favorit Saya</a>
        <a href="{{ route('ml.test') }}">Rekomendasi</a>
        <a href="{{ route('rekomendasi.finansial') }}">Rekomendasi Finansial</a>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        @else
        <a href="{{ url('/#features') }}">Fitur</a>
        <a href="{{ url('/#how') }}">Cara Kerja</a>
        <a href="{{ url('/#calculator') }}">Kalkulator</a>
        <a href="{{ route('login') }}" class="nav-cta">Cari Rumah</a>
        @endauth

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
    @auth
    <a href="{{ route('properti.browse') }}">🔍 Jelajahi Properti</a>
    <a href="{{ route('favorit.index') }}">❤️ Favorit Saya</a>
    <a href="{{ route('ml.test') }}">🧪 Rekomendasi</a>
    <a href="{{ route('rekomendasi.finansial') }}">💰 Rekomendasi Finansial</a>
    <a href="{{ route('dashboard') }}">📊 Dashboard</a>
    <div class="mobile-divider"></div>
    <a href="{{ route('profile') }}">👤 Profil Saya</a>
    @if(Auth::user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}">🛡️ Admin Panel</a>
    @endif
    <form action="{{ route('logout') }}" method="POST" style="margin:0;padding:0;">
        @csrf
        <button type="submit" class="mobile-logout-btn">🚪 Keluar</button>
    </form>
    @else
    <a href="{{ url('/#features') }}">Fitur</a>
    <a href="{{ url('/#how') }}">Cara Kerja</a>
    <a href="{{ url('/#calculator') }}">Kalkulator</a>
    <a href="{{ route('login') }}">Cari Rumah</a>
    <div class="mobile-divider"></div>
    <a href="{{ route('login') }}">Masuk</a>
    <a href="{{ route('register') }}" class="mobile-register">Daftar</a>
    @endauth
</div>
