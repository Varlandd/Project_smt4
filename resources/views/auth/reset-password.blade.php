@extends('layouts.app')

@section('content')
<section style="min-height: 70vh; display: flex; justify-content: center; align-items: center; padding: 40px 16px; background: #f8fafc;">
    <div style="width: 100%; max-width: 420px; background: white; padding: 32px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.08);">
        
        <h2 style="font-size: 26px; font-weight: 800; text-align: center; margin-bottom: 8px; color: #111827;">
            Password Baru
        </h2>

        <p style="text-align: center; color: #6b7280; font-size: 14px; margin-bottom: 24px;">
            Buat password baru untuk akun RumahKu kamu.
        </p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            {{-- Password Baru --}}
            <div style="margin-bottom: 18px;">
                <label for="password" style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">
                    Password Baru
                </label>

                <div class="input-with-icon">
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           style="width: 100%; padding: 12px 40px 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; outline: none; box-sizing: border-box;">

                    <button type="button"
                            class="toggle-password"
                            onclick="togglePassword('password', this)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>

                @error('password')
                    <p style="color: #ef4444; font-size: 13px; margin-top: 6px;">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div style="margin-bottom: 22px;">
                <label for="password_confirmation" style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">
                    Konfirmasi Password
                </label>

                <div class="input-with-icon">
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           required
                           style="width: 100%; padding: 12px 40px 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; outline: none; box-sizing: border-box;">

                    <button type="button"
                            class="toggle-password"
                            onclick="togglePassword('password_confirmation', this)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit"
                    style="width: 100%; background: #0f766e; color: white; padding: 13px 16px; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; display: flex; justify-content: center; align-items: center;">
                Simpan Password Baru
            </button>
        </form>

        <div style="text-align: center; margin-top: 22px;">
            <a href="{{ route('login') }}"
               style="color: #0f766e; text-decoration: none; font-size: 13px; font-weight: 500;">
                Kembali ke Login
            </a>
        </div>

    </div>
</section>
@endsection