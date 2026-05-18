@extends('layouts.app')

@section('title', 'Profil Saya — RumahKu')

@section('content')
<section class="profile-page">
    <div class="page-header">
        <div>
            <p class="page-overline">Akun</p>
            <h1>Profil Saya</h1>
            <p class="page-intro">Lihat informasi akun Anda dan akses pengaturan profil dengan cepat.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="button button-secondary">Kembali ke Dashboard</a>
    </div>

    <div class="profile-container">
        <!-- Sidebar Menu -->
        <aside class="profile-sidebar">
            <div class="profile-menu">
                <a href="{{ route('profile.info') }}" class="profile-menu-item {{ request()->routeIs('profile.info') ? 'active' : '' }}">
                    <span class="menu-icon">👤</span>
                    <span class="menu-label">Profile Info</span>
                </a>
                <a href="{{ route('profile.edit') }}" class="profile-menu-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <span class="menu-icon">✏️</span>
                    <span class="menu-label">Edit Profile</span>
                </a>
                <a href="{{ route('profile.security') }}" class="profile-menu-item {{ request()->routeIs('profile.security') ? 'active' : '' }}">
                    <span class="menu-icon">🔒</span>
                    <span class="menu-label">Security Settings</span>
                </a>
                <a href="{{ route('profile.orders') }}" class="profile-menu-item {{ request()->routeIs('profile.orders') ? 'active' : '' }}">
                    <span class="menu-icon">📋</span>
                    <span class="menu-label">My Orders / History</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="profile-content">
            @php
                $section = request()->route()->getName();
            @endphp

            @if($section === 'profile.edit')
                @include('pages.profile.edit')
            @elseif($section === 'profile.security')
                @include('pages.profile.security')
            @elseif($section === 'profile.orders')
                @include('pages.profile.orders')
            @else
                @include('pages.profile.info')
            @endif
        </main>
    </div>
</section>

<style>
    .profile-page {
        padding: 2rem;
        background-color: #f5f5f5;
        min-height: calc(100vh - 200px);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .page-overline {
        font-size: 0.875rem;
        color: #666;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .page-header h1 {
        margin: 0.5rem 0 0.5rem 0;
        color: #1a1a1a;
    }

    .page-intro {
        margin: 0;
        color: #666;
        font-size: 0.95rem;
    }

    .profile-container {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 2rem;
    }

    .profile-sidebar {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        height: fit-content;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        position: sticky;
        top: 2rem;
    }

    .profile-menu {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .profile-menu-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        color: #333;
        font-weight: 500;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }

    .profile-menu-item:hover {
        background-color: #f0f0f0;
        color: #16a085;
    }

    .profile-menu-item.active {
        background-color: #e6f7f4;
        color: #16a085;
        border-left-color: #16a085;
    }

    .menu-icon {
        font-size: 1.25rem;
    }

    .menu-label {
        font-size: 0.95rem;
    }

    .profile-content {
        background: white;
        border-radius: 8px;
        padding: 2rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    @media (max-width: 768px) {
        .profile-container {
            grid-template-columns: 1fr;
        }

        .profile-sidebar {
            position: static;
        }

        .profile-menu {
            flex-direction: row;
            flex-wrap: wrap;
        }

        .profile-menu-item {
            flex: 1;
            min-width: 120px;
            justify-content: center;
        }
    }
</style>
@endsection
