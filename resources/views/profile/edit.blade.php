@extends('layouts.form')

@section('page_title', 'Edit Profil')
@section('breadcrumb')
    <li class="breadcrumb-item active">Edit Profil</li>
@endsection

@section('form_title', 'Edit Profil')
@section('form_subtitle', 'Perbarui informasi profil Anda')
@section('form_action', route('profile.update'))
@section('form_method')
    @method('PUT')
@endsection
@section('submit_text', 'Update Profil')

@section('form_content')
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name" class="form-label required-field">Nama Lengkap</label>
            <input type="text"
                   class="form-control @error('name') is-invalid @enderror"
                   id="name"
                   name="name"
                   value="{{ old('name', $user->name) }}"
                   placeholder="Masukkan nama lengkap"
                   required>
            @error('name')
                <div class="error-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="email" class="form-label required-field">Email</label>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email', $user->email) }}"
                   placeholder="email@example.com"
                   required>
            @error('email')
                <div class="error-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<hr class="my-4">

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="current_password" class="form-label">Password Saat Ini</label>
            <input type="password"
                   class="form-control @error('current_password') is-invalid @enderror"
                   id="current_password"
                   name="current_password"
                   placeholder="Masukkan password saat ini">
            @error('current_password')
                <div class="error-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="new_password" class="form-label">Password Baru</label>
            <input type="password"
                   class="form-control @error('new_password') is-invalid @enderror"
                   id="new_password"
                   name="new_password"
                   placeholder="Masukkan password baru">
            @error('new_password')
                <div class="error-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
            <input type="password"
                   class="form-control"
                   id="new_password_confirmation"
                   name="new_password_confirmation"
                   placeholder="Konfirmasi password baru">
        </div>
    </div>
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Catatan:</strong> Kosongkan field password jika tidak ingin mengubah password.
</div>
@endsection

@section('cancel_url', route('chart-of-accounts.index'))
