<div class="profile-security">
    <h3>Pengaturan Keamanan</h3>
    <p class="section-desc">Kelola keamanan akun Anda dengan berbagai opsi keamanan.</p>

    <div class="security-sections">
        <!-- Change Password Section -->
        <div class="security-section">
            <h4>Ubah Password</h4>
            <p>Perbarui password Anda secara berkala untuk keamanan yang lebih baik.</p>
            
            <form action="{{ route('profile.password.update') }}" method="POST" class="security-form">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="current_password">Password Saat Ini</label>
                    <input 
                        type="password" 
                        id="current_password" 
                        name="current_password" 
                        class="form-input @error('current_password') form-input-error @enderror"
                        required
                    >
                    @error('current_password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input @error('password') form-input-error @enderror"
                        required
                    >
                    <p class="form-hint">Minimal 8 karakter, mengandung huruf dan angka.</p>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-input @error('password_confirmation') form-input-error @enderror"
                        required
                    >
                    @error('password_confirmation')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="button button-primary">Ubah Password</button>
            </form>
        </div>

        <!-- Login Activity Section -->
        <div class="security-section">
            <h4>Riwayat Login Terakhir</h4>
            <p>Pantau aktivitas login terbaru Anda.</p>
            
            <div class="login-activity">
                <div class="activity-item">
                    <div class="activity-icon">🖥️</div>
                    <div class="activity-info">
                        <p class="activity-device">Desktop - Chrome</p>
                        <p class="activity-time">Hari ini, 14:32</p>
                        <p class="activity-location">Jakarta, Indonesia</p>
                    </div>
                    <span class="activity-status active">Aktif</span>
                </div>

                <div class="activity-item">
                    <div class="activity-icon">📱</div>
                    <div class="activity-info">
                        <p class="activity-device">Mobile - Safari</p>
                        <p class="activity-time">2 hari lalu</p>
                        <p class="activity-location">Jakarta, Indonesia</p>
                    </div>
                    <span class="activity-status">Inaktif</span>
                </div>

                <div class="activity-item">
                    <div class="activity-icon">🖥️</div>
                    <div class="activity-info">
                        <p class="activity-device">Desktop - Firefox</p>
                        <p class="activity-time">1 minggu lalu</p>
                        <p class="activity-location">Surabaya, Indonesia</p>
                    </div>
                    <span class="activity-status">Inaktif</span>
                </div>
            </div>
        </div>

        <!-- Two-Factor Authentication -->
        <div class="security-section">
            <h4>Autentikasi Dua Faktor</h4>
            <p>Tambahkan lapisan keamanan ekstra pada akun Anda.</p>
            
            <div class="security-status">
                <div class="status-info">
                    <span class="status-label">Status:</span>
                    <span class="status-value disabled">Belum Diaktifkan</span>
                </div>
                <button type="button" class="button button-primary">Aktifkan 2FA</button>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-security h3 {
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        font-size: 1.5rem;
    }

    .section-desc {
        color: #666;
        margin-bottom: 2rem;
        font-size: 0.95rem;
    }

    .security-sections {
        display: grid;
        gap: 2rem;
    }

    .security-section {
        padding: 1.5rem;
        background: #f9f9f9;
        border-radius: 8px;
        border-left: 4px solid #16a085;
    }

    .security-section h4 {
        margin: 0 0 0.5rem 0;
        color: #1a1a1a;
        font-size: 1.1rem;
    }

    .security-section > p {
        margin: 0 0 1.5rem 0;
        color: #666;
        font-size: 0.9rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-input {
        width: 100%;
        max-width: 400px;
        padding: 0.75rem 1rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.95rem;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #16a085;
        box-shadow: 0 0 0 3px rgba(22, 160, 133, 0.1);
    }

    .form-input-error {
        border-color: #e74c3c;
    }

    .form-hint {
        margin: 0.5rem 0 0 0;
        color: #999;
        font-size: 0.85rem;
    }

    .error-message {
        display: block;
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .button {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        font-size: 0.95rem;
    }

    .button-primary {
        background: linear-gradient(135deg, #16a085 0%, #1abc9c 100%);
        color: white;
    }

    .button-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(22, 160, 133, 0.3);
    }

    .security-form {
        max-width: 400px;
    }

    .login-activity {
        display: grid;
        gap: 1rem;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: white;
        border-radius: 6px;
        border: 1px solid #eee;
    }

    .activity-icon {
        font-size: 2rem;
        flex-shrink: 0;
    }

    .activity-info {
        flex: 1;
    }

    .activity-device {
        margin: 0;
        font-weight: 600;
        color: #1a1a1a;
        font-size: 0.95rem;
    }

    .activity-time {
        margin: 0.25rem 0 0 0;
        color: #666;
        font-size: 0.85rem;
    }

    .activity-location {
        margin: 0;
        color: #999;
        font-size: 0.8rem;
    }

    .activity-status {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        background: #eee;
        color: #666;
    }

    .activity-status.active {
        background: #d4edda;
        color: #155724;
    }

    .security-status {
        padding: 1rem;
        background: white;
        border-radius: 6px;
        border: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .status-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .status-label {
        font-weight: 600;
        color: #666;
    }

    .status-value {
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .status-value.disabled {
        background: #fff3cd;
        color: #856404;
    }
</style>
