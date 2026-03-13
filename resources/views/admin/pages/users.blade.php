@extends('admin.layouts.admin')

@section('title', 'Data Pengguna — Admin RumahKu')
@section('page-title', 'Data Pengguna')

@section('content')
<div class="admin-section">
    <div class="admin-section-header">
        <h3>Daftar Pengguna</h3>
        <span class="admin-count">{{ \App\Models\User::count() }} pengguna</span>
    </div>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Bergabung</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\User::latest()->get() as $i => $user)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <div class="table-user">
                            <div class="table-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            {{ $user->name }}
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="role-badge role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
