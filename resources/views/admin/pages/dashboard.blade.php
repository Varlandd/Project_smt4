@extends('admin.layouts.admin')

@section('title', 'Dashboard Admin — RumahKu')
@section('page-title', 'Dashboard')

@section('content')
<div class="admin-stats-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background: linear-gradient(135deg, #dbeafe, #93c5fd);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#1e40af" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-number">{{ \App\Models\Rumah::count() }}</span>
            <span class="admin-stat-label">Total Rumah</span>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background: linear-gradient(135deg, #fce7f3, #f9a8d4);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#be185d" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-number">{{ \App\Models\User::count() }}</span>
            <span class="admin-stat-label">Total Pengguna</span>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background: linear-gradient(135deg, #dcfce7, #86efac);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-number">{{ \DB::table('favorits')->count() }}</span>
            <span class="admin-stat-label">Total Favorit</span>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background: linear-gradient(135deg, #fef3c7, #fcd34d);">
            <svg viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
        </div>
        <div class="admin-stat-info">
            <span class="admin-stat-number">{{ \App\Models\User::where('role', 'admin')->count() }}</span>
            <span class="admin-stat-label">Total Admin</span>
        </div>
    </div>
</div>

<div class="admin-section">
    <div class="admin-section-header">
        <h3>Pengguna Terbaru</h3>
    </div>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Bergabung</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                <tr>
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
                    <td>{{ $user->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
