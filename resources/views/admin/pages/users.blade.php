@extends('admin.layouts.admin')

@section('title', 'Data Pengguna — Admin RumahKu')
@section('page-title', 'Data Pengguna')

@section('content')
<div class="admin-section">
    <div class="admin-section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>Daftar Pengguna</h3>
        <div style="display: flex; gap: 15px; align-items: center;">
            <span class="admin-count">{{ $users->total() }} pengguna</span>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary" style="padding: 8px 15px; background: #4f46e5; color: white; border-radius: 6px; text-decoration: none; font-weight: 500;">+ Tambah Pengguna</a>
        </div>
    </div>
    
    @if(session('success'))
        <div style="background-color: #d1fae5; color: #065f46; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="background-color: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="admin-table-wrapper" style="overflow-x: auto;">
        <table class="admin-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #e5e7eb; text-align: left;">
                    <th style="padding: 12px;">#</th>
                    <th style="padding: 12px;">Nama</th>
                    <th style="padding: 12px;">Email</th>
                    <th style="padding: 12px;">Role</th>
                    <th style="padding: 12px;">Bergabung</th>
                    <th style="padding: 12px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $user)
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 12px;">{{ $users->firstItem() + $i }}</td>
                    <td style="padding: 12px;">
                        <div class="table-user" style="display: flex; align-items: center; gap: 10px;">
                            <div class="table-avatar" style="width: 32px; height: 32px; background: #e5e7eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #4b5563;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span style="font-weight: 500;">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="padding: 12px; color: #6b7280;">{{ $user->email }}</td>
                    <td style="padding: 12px;">
                        <span class="role-badge role-{{ $user->role }}" style="background: {{ $user->role == 'admin' ? '#fee2e2' : '#d1fae5' }}; color: {{ $user->role == 'admin' ? '#991b1b' : '#065f46' }}; padding: 4px 8px; border-radius: 4px; font-size: 0.875rem;">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td style="padding: 12px; color: #6b7280;">{{ $user->created_at->format('d M Y') }}</td>
                    <td style="padding: 12px; text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('admin.users.edit', $user->id) }}" style="color: #f59e0b; text-decoration: none; font-size: 0.875rem;">Edit</a>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: #ef4444; background: none; border: none; cursor: pointer; font-size: 0.875rem; padding: 0;">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="empty-state" style="padding: 20px; text-align: center; color: #6b7280;">Belum ada data pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>
@endsection
