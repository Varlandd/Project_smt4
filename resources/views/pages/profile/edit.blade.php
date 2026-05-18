<div class="profile-edit">
    <h3>Edit Profil</h3>
    <p class="section-desc">Perbarui informasi profil Anda di sini.</p>

    <form action="{{ route('profile.update') }}" method="POST" class="edit-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                class="form-input @error('name') form-input-error @enderror"
                value="{{ old('name', $user->name) }}"
                required
            >
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                class="form-input @error('email') form-input-error @enderror"
                value="{{ old('email', $user->email) }}"
                required
            >
            <p class="form-hint">Email Anda digunakan untuk login dan notifikasi penting.</p>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="button button-primary">Simpan Perubahan</button>
            <a href="{{ route('profile.info') }}" class="button button-secondary">Batal</a>
        </div>
    </form>
</div>

<style>
    .profile-edit h3 {
        color: #1a1a1a;
        margin-bottom: 0.5rem;
        font-size: 1.5rem;
    }

    .section-desc {
        color: #666;
        margin-bottom: 2rem;
        font-size: 0.95rem;
    }

    .edit-form {
        max-width: 500px;
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

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #eee;
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

    .button-secondary {
        background: #f5f5f5;
        color: #333;
    }

    .button-secondary:hover {
        background: #eee;
    }
</style>
