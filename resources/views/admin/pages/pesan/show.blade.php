@extends('admin.layouts.admin')

@section('title', 'Detail Pesan — Admin RumahKu')

@section('content')
<div class="admin-header">
    <div class="header-left">
        <a href="{{ route('admin.pesan.index') }}" class="back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali ke Daftar Pesan
        </a>
        <h1 class="admin-title">Detail Pesan dari {{ $p->nama }}</h1>
    </div>
</div>

<div class="admin-card">
    <div class="message-detail">
        <div class="detail-header">
            <div class="sender-info">
                <h3>{{ $p->subjek }}</h3>
                <p>Dari: <strong>{{ $p->nama }}</strong> ({{ $p->email }})</p>
                <span class="message-date">{{ $p->created_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="status-indicator">
                <span class="badge badge-success">SUDAH DIBACA</span>
            </div>
        </div>

        <div class="detail-body">
            <p>{{ $p->pesan }}</p>
        </div>

        <div class="detail-footer">
            <a href="mailto:{{ $p->email }}?subject=Re: {{ $p->subjek }}" class="admin-btn btn-primary">
                Balas via Email
            </a>
            <form action="{{ route('admin.pesan.destroy', $p->_id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="admin-btn btn-danger-outline">Hapus Pesan</button>
            </form>
        </div>
    </div>
</div>

<style>
    .message-detail { padding: 10px; }
    .detail-header { border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-start; }
    .detail-header h3 { font-size: 20px; margin-bottom: 8px; color: #111827; }
    .sender-info p { color: #6b7280; font-size: 14px; }
    .message-date { display: block; margin-top: 4px; font-size: 12px; color: #9ca3af; }
    .detail-body { min-height: 200px; line-height: 1.8; color: #374151; font-size: 16px; white-space: pre-wrap; }
    .detail-footer { border-top: 1px solid #eee; padding-top: 24px; margin-top: 24px; display: flex; gap: 12px; }
    .back-link { display: flex; align-items: center; gap: 8px; color: #0f766e; font-weight: 600; text-decoration: none; margin-bottom: 12px; font-size: 14px; }
    .back-link svg { width: 16px; height: 16px; }
    .btn-danger-outline { background: transparent; border: 1px solid #dc2626; color: #dc2626; }
    .btn-danger-outline:hover { background: #fee2e2; }
</style>
@endsection
