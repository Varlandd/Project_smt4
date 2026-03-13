@extends('admin.layouts.admin')

@section('title', 'Edit Pengguna — Admin RumahKu')
@section('page-title', 'Edit Pengguna')

@section('content')
<div class="admin-section">
    <div class="admin-section-header">
        <h3>Edit Form Pengguna: {{ $user->name }}</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="padding: 8px 15px; background: #e5e7eb; color: #374151; border-radius: 6px; text-decoration: none; font-weight: 500;">Kembali</a>
    </div>

    @if($errors->any())
        <div style="background-color: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" style="max-width: 600px;">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 20px;">
            <label for="name" style="display: block; margin-bottom: 8px; font-weight: 500;">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="email" style="display: block; margin-bottom: 8px; font-weight: 500;">Alamat Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="role" style="display: block; margin-bottom: 8px; font-weight: 500;">Role User</label>
            <select id="role" name="role" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User Biasa (Pencari Rumah)</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin Web</option>
            </select>
        </div>

        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 30px 0;">
        <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 15px;">Abaikan kolom password di bawah ini jika Anda tidak ingin mengubah password pengguna ini.</p>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div>
                <label for="password" style="display: block; margin-bottom: 8px; font-weight: 500;">Password Baru</label>
                <input type="password" id="password" name="password" minlength="8" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
            </div>
            <div>
                <label for="password_confirmation" style="display: block; margin-bottom: 8px; font-weight: 500;">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" minlength="8" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
            </div>
        </div>

        <div>
            <button type="submit" style="padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 1rem;">Update Pengguna</button>
        </div>
    </form>
</div>
@endsection
