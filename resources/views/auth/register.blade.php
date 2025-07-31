@extends('layouts.auth')

@section('title', 'Register - Akuntansi Klik Medis')
@section('card_width', '800px')
@section('header_icon', 'fas fa-user-plus')
@section('header_title', 'Registrasi Perusahaan')
@section('header_subtitle', 'Daftar perusahaan baru untuk menggunakan sistem akuntansi')

@section('step_indicator')
<div class="step-indicator">
    <div class="step active" id="step1-indicator">1</div>
    <div class="step" id="step2-indicator">2</div>
</div>
@endsection

@section('auth_content')
<form id="registerForm" method="POST" action="{{ route('auth.register.post') }}">
    @csrf

    <!-- Step 1: Company Information -->
    <div class="step-content active" id="step1">
        <h5 class="mb-3"><i class="fas fa-building me-2"></i>Informasi Perusahaan</h5>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="company_name" class="form-label required-field">Nama Perusahaan</label>
                    <input type="text"
                           class="form-control @error('company_name') is-invalid @enderror"
                           id="company_name"
                           name="company_name"
                           placeholder="Masukkan nama perusahaan"
                           value="{{ old('company_name') }}"
                           required>
                    @error('company_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="company_email" class="form-label required-field">Email Perusahaan</label>
                    <input type="email"
                           class="form-control @error('company_email') is-invalid @enderror"
                           id="company_email"
                           name="company_email"
                           placeholder="perusahaan@example.com"
                           value="{{ old('company_email') }}"
                           required>
                    @error('company_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="company_address" class="form-label">Alamat Lengkap Perusahaan</label>
            <textarea class="form-control @error('company_address') is-invalid @enderror"
                      id="company_address"
                      name="company_address"
                      placeholder="Masukkan alamat lengkap perusahaan"
                      rows="2">{{ old('company_address') }}</textarea>
            @error('company_address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="company_province" class="form-label">Provinsi</label>
                    <input type="text"
                           class="form-control @error('company_province') is-invalid @enderror"
                           id="company_province"
                           name="company_province"
                           placeholder="Masukkan provinsi"
                           value="{{ old('company_province') }}">
                    @error('company_province')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="company_city" class="form-label">Kota/Kabupaten</label>
                    <input type="text"
                           class="form-control @error('company_city') is-invalid @enderror"
                           id="company_city"
                           name="company_city"
                           placeholder="Masukkan kota/kabupaten"
                           value="{{ old('company_city') }}">
                    @error('company_city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="company_district" class="form-label">Kecamatan</label>
                    <input type="text"
                           class="form-control @error('company_district') is-invalid @enderror"
                           id="company_district"
                           name="company_district"
                           placeholder="Masukkan kecamatan"
                           value="{{ old('company_district') }}">
                    @error('company_district')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="company_postal_code" class="form-label">Kode Pos</label>
                    <input type="text"
                           class="form-control @error('company_postal_code') is-invalid @enderror"
                           id="company_postal_code"
                           name="company_postal_code"
                           placeholder="Masukkan kode pos"
                           value="{{ old('company_postal_code') }}">
                    @error('company_postal_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="company_phone" class="form-label">Nomor Telepon</label>
                    <input type="text"
                           class="form-control @error('company_phone') is-invalid @enderror"
                           id="company_phone"
                           name="company_phone"
                           placeholder="Masukkan nomor telepon"
                           value="{{ old('company_phone') }}">
                    @error('company_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="company_website" class="form-label">Website</label>
                    <input type="url"
                           class="form-control @error('company_website') is-invalid @enderror"
                           id="company_website"
                           name="company_website"
                           placeholder="https://www.example.com"
                           value="{{ old('company_website') }}">
                    @error('company_website')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-success" id="nextStep">
                <i class="fas fa-arrow-right me-2"></i>Lanjut ke Step 2
            </button>
        </div>
    </div>

    <!-- Step 2: Owner Information -->
    <div class="step-content" id="step2">
        <h5 class="mb-3"><i class="fas fa-user me-2"></i>Informasi Pemilik</h5>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="owner_name" class="form-label required-field">Nama Pemilik</label>
                    <input type="text"
                           class="form-control @error('owner_name') is-invalid @enderror"
                           id="owner_name"
                           name="owner_name"
                           placeholder="Masukkan nama pemilik"
                           value="{{ old('owner_name') }}"
                           required>
                    @error('owner_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="owner_email" class="form-label required-field">Email Pemilik</label>
                    <input type="email"
                           class="form-control @error('owner_email') is-invalid @enderror"
                           id="owner_email"
                           name="owner_email"
                           placeholder="pemilik@example.com"
                           value="{{ old('owner_email') }}"
                           required>
                    @error('owner_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="password" class="form-label required-field">Password</label>
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password"
                           name="password"
                           placeholder="Masukkan password"
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label required-field">Konfirmasi Password</label>
                    <input type="password"
                           class="form-control"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Konfirmasi password"
                           required>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" id="prevStep">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Step 1
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
            </button>
        </div>
    </div>
</form>

<div class="text-center mt-3">
    <p class="mb-0">Sudah punya akun?
        <a href="{{ route('auth.login') }}" class="text-decoration-none">Login disini</a>
    </p>
</div>
@endsection

@push('custom_js')
<script>
$(document).ready(function() {
    let currentStep = 1;

    // Next step
    $('#nextStep').on('click', function() {
        if (validateStep1()) {
            $('#step1').removeClass('active');
            $('#step2').addClass('active');
            $('#step1-indicator').removeClass('active').addClass('completed');
            $('#step2-indicator').addClass('active');
            currentStep = 2;
        }
    });

    // Previous step
    $('#prevStep').on('click', function() {
        $('#step2').removeClass('active');
        $('#step1').addClass('active');
        $('#step2-indicator').removeClass('active');
        $('#step1-indicator').removeClass('completed').addClass('active');
        currentStep = 1;
    });

    // Validate step 1
    function validateStep1() {
        let isValid = true;
        const requiredFields = ['company_name', 'company_email'];

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        requiredFields.forEach(field => {
            const input = $(`#${field}`);
            if (!input.val().trim()) {
                input.addClass('is-invalid');
                input.after(`<div class="invalid-feedback">Field ini wajib diisi</div>`);
                isValid = false;
            }
        });

        // Validate email format
        const email = $('#company_email').val();
        if (email && !isValidEmail(email)) {
            $('#company_email').addClass('is-invalid');
            $('#company_email').after(`<div class="invalid-feedback">Format email tidak valid</div>`);
            isValid = false;
        }

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: 'Silakan lengkapi data yang diperlukan'
            });
        }

        return isValid;
    }

    // Email validation
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Form submission
    $('#registerForm').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Disable button and show loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Mendaftar...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = response.redirect;
                    });
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                let errorMessage = 'Terjadi kesalahan saat mendaftar';

                if (response && response.message) {
                    errorMessage = response.message;
                }

                // Handle validation errors
                if (response && response.errors) {
                    Object.keys(response.errors).forEach(field => {
                        const input = $(`[name="${field}"]`);
                        input.addClass('is-invalid');

                        // Show error message
                        const errorDiv = input.siblings('.invalid-feedback');
                        if (errorDiv.length === 0) {
                            input.after(`<div class="invalid-feedback">${response.errors[field][0]}</div>`);
                        } else {
                            errorDiv.text(response.errors[field][0]);
                        }
                    });
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mendaftar',
                    text: errorMessage
                });

                // Re-enable button
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Remove error styling when user starts typing
    $('input, textarea').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').remove();
    });
});
</script>
@endpush
