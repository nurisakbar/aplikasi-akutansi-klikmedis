@extends('layouts.form')

@section('page_title', 'Tambah Customer')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
    <li class="breadcrumb-item active">Tambah Customer</li>
@endsection

@section('form_title', 'Tambah Customer Baru')
@section('form_subtitle', 'Masukkan informasi customer dengan lengkap')
@section('form_action', route('customers.store'))
@section('submit_text', 'Simpan Customer')

@section('form_content')
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name" class="form-label required-field">Nama Customer</label>
            <input type="text"
                   class="form-control @error('name') is-invalid @enderror"
                   id="name"
                   name="name"
                   value="{{ old('name') }}"
                   placeholder="Masukkan nama customer"
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
                   value="{{ old('email') }}"
                   placeholder="customer@example.com"
                   required>
            @error('email')
                <div class="error-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="phone" class="form-label">Nomor Telepon</label>
            <input type="text"
                   class="form-control @error('phone') is-invalid @enderror"
                   id="phone"
                   name="phone"
                   value="{{ old('phone') }}"
                   placeholder="08123456789">
            @error('phone')
                <div class="error-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="address" class="form-label">Alamat</label>
            <textarea class="form-control @error('address') is-invalid @enderror"
                      id="address"
                      name="address"
                      rows="3"
                      placeholder="Masukkan alamat customer">{{ old('address') }}</textarea>
            @error('address')
                <div class="error-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="tax_number" class="form-label">NPWP</label>
            <input type="text"
                   class="form-control @error('tax_number') is-invalid @enderror"
                   id="tax_number"
                   name="tax_number"
                   value="{{ old('tax_number') }}"
                   placeholder="12.345.678.9-123.000">
            @error('tax_number')
                <div class="error-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="credit_limit" class="form-label">Batas Kredit</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number"
                       class="form-control @error('credit_limit') is-invalid @enderror"
                       id="credit_limit"
                       name="credit_limit"
                       value="{{ old('credit_limit') }}"
                       placeholder="0"
                       step="0.01">
            </div>
            @error('credit_limit')
                <div class="error-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
@endsection

@section('cancel_url', route('customers.index'))
