<div class="profile-info">
    <h3>Informasi Profil</h3>
    <p class="section-desc">Lihat informasi akun Anda secara lengkap dan detail.</p>

    <div class="info-grid">
        <div class="info-card">
            <div class="info-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div class="info-details">
                <h4>{{ $user->name }}</h4>
                <p class="info-role">{{ ucfirst($user->role ?? 'Pengguna') }}</p>
            </div>
        </div>

        <div class="info-section">
            <h4>Informasi Akun</h4>
            <div class="info-item">
                <label>Email</label>
                <p>{{ $user->email }}</p>
            </div>
            <div class="info-item">
                <label>Nama Lengkap</label>
                <p>{{ $user->name }}</p>
            </div>
            <div class="info-item">
                <label>Role</label>
                <p>{{ ucfirst($user->role ?? 'User') }}</p>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-info h3 {
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        font-size: 1.5rem;
    }

    .section-desc {
        color: #666;
        margin-bottom: 2rem;
        font-size: 0.95rem;
    }

    .info-grid {
        display: grid;
        gap: 2rem;
    }

    .info-card {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #16a085 0%, #1abc9c 100%);
        border-radius: 8px;
        color: white;
    }

    .info-avatar {
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.3);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: bold;
    }

    .info-details h4 {
        margin: 0 0 0.25rem 0;
        font-size: 1.25rem;
    }

    .info-role {
        margin: 0;
        opacity: 0.9;
        font-size: 0.9rem;
    }

    .info-section {
        padding: 1.5rem;
        background: #f9f9f9;
        border-radius: 8px;
    }

    .info-section h4 {
        color: #1a1a1a;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #16a085;
    }

    .info-item {
        margin-bottom: 1rem;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-item label {
        display: block;
        font-weight: 600;
        color: #666;
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-item p {
        margin: 0;
        color: #1a1a1a;
        font-size: 0.95rem;
    }

    @media (max-width: 768px) {
        .info-card {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
