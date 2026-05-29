@extends('layouts.app')

@section('title', 'Verifikasi OTP — RumahKu')

@push('styles')
<style>
    #otp {
        letter-spacing: 6px;
        font-size: 1.1rem;
        text-align: center;
        padding: 11px 14px;
    }
</style>
@endpush

@section('content')
<section class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.14 12"/>
                    <rect x="5" y="2" width="14" height="20" rx="2"/>
                    <line x1="12" y1="18" x2="12" y2="18"/>
                </svg>
            </div>
            <h1 class="auth-title">Verifikasi <span class="gradient-text">OTP</span></h1>
            <p class="auth-subtitle">Masukkan kode 6 digit yang dikirim ke email kamu</p>
        </div>

        @if($errors->any())
        <div class="auth-alert auth-alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        @if(session('success'))
        <div class="auth-alert auth-alert-success">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ url('/verify-otp') }}" method="POST" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="otp">Kode OTP</label>
                <div class="input-with-icon">
                    <input type="text" id="otp" name="otp" placeholder="000000"
                    maxlength="6" required autofocus style="letter-spacing: 6px; font-size: 1.1rem; text-align: center;"/>    
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Verifikasi Sekarang
            </button>
        </form>

        <div class="auth-footer">
            <p>Tidak menerima kode?
                <form action="{{ route('otp.resend') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none;border:none;color:var(--primary);font-weight:700;cursor:pointer;">
                        Kirim ulang
                    </button>
                </form>
            </p>
        </div>
    </div>
</section>
@endsection