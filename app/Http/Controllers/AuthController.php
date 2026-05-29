<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller

{
    // ── Show Login Page ──
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    // ── Process Login ──
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole();
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // ── Show Register Page ──
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.register');
    }

    // ── Process Register ──
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'otp_code'       => $otp,
            'otp_expires_at' => now()->addMinutes(10),
            'is_verified'    => false,
        ]);

        Mail::raw("Kode OTP RumahKu kamu: $otp\n\nBerlaku 10 menit.", function ($msg) use ($user) {
        $msg->to($user->email)->subject('Kode OTP Verifikasi RumahKu');
    });

    // Simpan user id di session
    session(['otp_user_id' => $user->id]);

    return redirect()->route('otp.verify');
}

// ── Show OTP Page ──
public function showOtp()
{
    if (!session('otp_user_id')) {
        return redirect()->route('register');
    }
    return view('auth.otp');
}

// ── Verify OTP ──
public function verifyOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|digits:6',
    ]);

    $user = User::find(session('otp_user_id'));

    if (!$user) {
        return redirect()->route('register')->withErrors(['otp' => 'Sesi expired, silakan daftar ulang.']);
    }

    if ($user->otp_code !== $request->otp) {
        return back()->withErrors(['otp' => 'Kode OTP salah.']);
    }

    if (now()->gt($user->otp_expires_at)) {
        return back()->withErrors(['otp' => 'Kode OTP sudah expired. Minta kode baru.']);
    }

    // OTP benar, verifikasi user
    $user->update([
        'is_verified'    => true,
        'otp_code'       => null,
        'otp_expires_at' => null,
    ]);

    session()->forget('otp_user_id');
    Auth::login($user);

    return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Selamat datang di RumahKu.');
}

// ── Resend OTP ──
public function resendOtp()
{
    $user = User::find(session('otp_user_id'));

    if (!$user) {
        return redirect()->route('register');
    }

    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    $user->update([
        'otp_code'       => $otp,
        'otp_expires_at' => now()->addMinutes(10),
    ]);

    Mail::raw("Kode OTP RumahKu kamu: $otp\n\nBerlaku 10 menit.", function ($msg) use ($user) {
        $msg->to($user->email)->subject('Kode OTP Verifikasi RumahKu');
    });

    return back()->with('success', 'Kode OTP baru telah dikirim ke email kamu.');
}

    // ── Logout ──
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    // ── Redirect based on role ──
    private function redirectByRole()
    {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
}
