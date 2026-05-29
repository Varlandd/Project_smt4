@extends('layouts.app')

@section('title', 'Daftar — RumahKu')

@section('content')
<section class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="8.5" cy="7" r="4"/>
                    <line x1="20" y1="8" x2="20" y2="14"/>
                    <line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
            </div>
            <h1 class="auth-title">Daftar di <span class="gradient-text">RumahKu</span></h1>
            <p class="auth-subtitle">Buat akun gratis untuk mendapatkan rekomendasi rumah terbaik</p>
        </div>

        @if($errors->any())
        <div class="auth-alert auth-alert-error">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
        @endif

        <form action="{{ url('/register') }}" method="POST" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </span>
                    <input type="text" id="name" name="name" placeholder="Nama lengkap kamu" value="{{ old('name') }}" required autofocus/>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <div class="input-with-icon">
                    <span class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </span>
                    <input type="email" id="email" name="email" placeholder="email@example.com" value="{{ old('email') }}" required/>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-with-icon" style="position:relative;">
                    <span class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </span>
                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required/>
                    <span onclick="togglePassword('password', this)" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:#9ca3af;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <div class="input-with-icon" style="position:relative;">
                    <span class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </span>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required/>
                    <span onclick="togglePassword('password_confirmation', this)" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);cursor:pointer;color:#9ca3af;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="8.5" cy="7" r="4"/>
                    <line x1="20" y1="8" x2="20" y2="14"/>
                    <line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
                Daftar Sekarang
            </button>
        </form>

        <div class="auth-footer">
            <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function togglePassword(fieldId, icon) {
    const input = document.getElementById(fieldId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
            <line x1="1" y1="1" x2="23" y2="23"/>
        </svg>`;
    } else {
        input.type = 'password';
        icon.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>`;
    }
}
</script>
@endpush