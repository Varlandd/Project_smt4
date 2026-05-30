<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    // Tampilkan form input OTP
    public function showOtpForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-otp');
    }

    // Verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', session('reset_email'))
                    ->where('otp_code', $request->otp)
                    ->where('otp_expires_at', '>', now())
                    ->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluarsa.']);
        }

        // Tandai OTP sudah diverifikasi
        session(['otp_verified' => true]);

        return redirect()->route('password.reset.form');
    }

    // Tampilkan form password baru
    public function showResetForm()
    {
        if (!session('reset_email') || !session('otp_verified')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-password');
    }

    // Simpan password baru
    public function resetPassword(Request $request)
    {
        if (!session('reset_email') || !session('otp_verified')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', session('reset_email'))->first();

        $user->update([
            'password'       => Hash::make($request->password),
            'otp_code'       => null,
            'otp_expires_at' => null,
        ]);

        // Hapus session
        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('login')
                         ->with('success', 'Password berhasil direset. Silakan login.');
    }
}