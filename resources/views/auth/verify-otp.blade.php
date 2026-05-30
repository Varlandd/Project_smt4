@extends('layouts.app')

@section('content')
<section style="min-height: 70vh; display: flex; justify-content: center; align-items: center; padding: 40px 16px; background: #f8fafc;">
    <div style="width: 100%; max-width: 420px; background: white; padding: 32px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.08);">
        <h2 style="font-size: 26px; font-weight: 800; text-align: center; margin-bottom: 8px; color: #111827;">
            Verifikasi OTP
        </h2>

        <p style="text-align: center; color: #6b7280; font-size: 14px; margin-bottom: 24px;">
            Masukkan kode OTP yang sudah dikirim ke email kamu.
        </p>

        <form method="POST" action="{{ route('password.otp.verify') }}">
            @csrf

            <div style="margin-bottom: 18px;">
                <label for="otp" style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #374151;">
                    Kode OTP
                </label>

                <input type="text"
                       id="otp"
                       name="otp"
                       maxlength="6"
                       required
                       autofocus
                       style="width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 14px; outline: none; box-sizing: border-box;">

                @error('otp')
                    <p style="color: #ef4444; font-size: 13px; margin-top: 6px;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    style="width: 100%; background: #0f766e; color: white; padding: 13px 16px; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; display: flex; justify-content: center; align-items: center;">
                Verifikasi
            </button>
        </form>

        <div style="text-align: center; margin-top: 22px;">
            <a href="{{ route('password.request') }}"
               style="color: #0f766e; text-decoration: none; font-size: 13px; font-weight: 500; display: inline-block;">
                Kirim Ulang OTP
            </a>
        </div>
    </div>
</section>
@endsection