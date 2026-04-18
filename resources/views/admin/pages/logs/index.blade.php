@extends('admin.layouts.admin')

@section('title', 'Log Aktivitas — Admin RumahKu')

@section('content')
<div class="admin-header">
    <div class="header-left">
        <h1 class="admin-title">Log Aktivitas Sistem</h1>
        <p class="admin-subtitle">Pantau perubahan data yang dilakukan oleh Admin.</p>
    </div>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Admin</th>
                    <th>Aksi</th>
                    <th>Objek</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="font-size: 13px; color: #6b7280;">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td><strong>{{ $log->user_name }}</strong></td>
                    <td>
                        <span class="badge-action badge-{{ strtolower($log->aksi) }}">
                            {{ $log->aksi }}
                        </span>
                    </td>
                    <td><span class="text-mute">{{ $log->tipe_objek }}</span></td>
                    <td style="font-size: 14px;">{{ $log->deskripsi }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada aktivitas tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="card-footer">
        {{ $logs->links() }}
    </div>
</div>

<style>
    .badge-action { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 800; display: inline-block; }
    .badge-create { background: #dcfce7; color: #16a34a; }
    .badge-update { background: #fef9c3; color: #a16207; }
    .badge-delete { background: #fee2e2; color: #dc2626; }
    .text-mute { color: #9ca3af; font-size: 12px; }
    .card-footer { padding: 20px; border-top: 1px solid #eee; }
</style>
@endsection
