@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-card">
    <div class="auth-header">
        <div style="display: flex; justify-content: center; margin-bottom: 0.5rem;">
            <div class="brand-icon" style="width: 52px; height: 52px; font-size: 1.6rem;">📦</div>
        </div>
        <h1 class="auth-title">InvenTrack <span style="color: #6366f1; font-size: 1.1rem;">Laravel</span></h1>
        <p class="auth-subtitle">Sistem Manajemen Inventaris & Mutasi Stok Terpadu</p>
    </div>

    @if(session('danger'))
        <div class="alert alert-danger">
            <span>{{ session('danger') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            <span>{{ session('info') }}</span>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" id="login-form">
        @csrf
        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
        </div>

        <div style="margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem;" id="login-btn">
                Masuk ke Sistem (Laravel)
            </button>
        </div>
    </form>

    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle); font-size: 0.82rem; color: var(--text-muted);">
        <strong>Akun Bawaan (Database Seeder):</strong><br>
        • <strong>Admin:</strong> <code>admin</code> / <code>admin123</code> (Akses Penuh)<br>
        • <strong>Staff:</strong> <code>staff</code> / <code>staff123</code> (Akses Operasional)
    </div>
</div>
@endsection
