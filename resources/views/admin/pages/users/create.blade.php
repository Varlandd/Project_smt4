@extends('admin.layouts.admin')

@section('title', 'Tambah Pengguna — Admin RumahKu')
@section('page-title', 'Tambah Pengguna')

@section('content')
<div class="admin-section">
    <div class="admin-section-header">
        <h3>Form Tambah Pengguna Baru</h3>
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

    <form action="{{ route('admin.users.store') }}" method="POST" style="max-width: 600px;">
        @csrf
        
        <div style="margin-bottom: 20px;">
            <label for="name" style="display: block; margin-bottom: 8px; font-weight: 500;">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="email" style="display: block; margin-bottom: 8px; font-weight: 500;">Alamat Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="role" style="display: block; margin-bottom: 8px; font-weight: 500;">Role User</label>
            <select id="role" name="role" required style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User Kasar (Pencari Rumah)</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin Web</option>
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div>
                <label for="password" style="display: block; margin-bottom: 8px; font-weight: 500;">Password</label>
                <input type="password" id="password" name="password" required minlength="8" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
            </div>
            <div>
                <label for="password_confirmation" style="display: block; margin-bottom: 8px; font-weight: 500;">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
            </div>
        </div>

        <div>
            <button type="submit" style="padding: 10px 20px; background: #10b981; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 1rem;">Simpan Pengguna</button>
        </div>
    </form>
</div>
@endsection
