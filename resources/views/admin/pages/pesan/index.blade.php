@extends('admin.layouts.admin')

@section('title', 'Pesan Masuk — Admin RumahKu')

@section('content')
<div class="admin-header">
    <div class="header-left">
        <h1 class="admin-title">Pesan & Feedback User</h1>
        <p class="admin-subtitle">Dengar apa yang dikatakan oleh pengguna aplikasi Anda.</p>
    </div>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Pengirim</th>
                    <th>Email</th>
                    <th>Subjek</th>
                    <th>Tanggal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesan as $p)
                <tr class="{{ $p->status === 'unread' ? 'row-unread' : '' }}">
                    <td>
                        <span class="badge {{ $p->status === 'unread' ? 'badge-danger' : 'badge-success' }}">
                            {{ strtoupper($p->status) }}
                        </span>
                    </td>
                    <td><strong>{{ $p->nama }}</strong></td>
                    <td>{{ $p->email }}</td>
                    <td>{{ $p->subjek }}</td>
                    <td>{{ $p->created_at->diffForHumans() }}</td>
                    <td class="text-center">
                        <div class="table-actions">
                            <a href="{{ route('admin.pesan.show', $p->_id) }}" class="action-btn btn-view" title="Baca">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            <form action="{{ route('admin.pesan.destroy', $p->_id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" title="Hapus">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada pesan masuk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .row-unread { background-color: rgba(220, 38, 38, 0.02); }
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 10px; fontWeight: bold; }
    .badge-danger { background: #fee2e2; color: #dc2626; }
    .badge-success { background: #dcfce7; color: #16a34a; }
</style>
@endsection
