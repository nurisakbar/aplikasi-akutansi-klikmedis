@extends('adminlte::auth.register')

@section('title', 'Register - Akuntansi Klik Medis')

@section('auth_header', 'Registrasi Perusahaan')

@section('auth_body')
    <form action="{{ route('auth.register.post') }}" method="post" id="registerForm">
        @csrf

        <!-- Step 1: Company Information -->
        <div class="step-content active" id="step1">
            <h6 class="text-center mb-3"><i class="fas fa-building"></i> Informasi Perusahaan</h6>

            {{-- Company Name field --}}
            <div class="input-group mb-3">
                <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror"
                       value="{{ old('company_name') }}" placeholder="Nama Perusahaan" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-building"></span>
                    </div>
                </div>
                @error('company_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Company Email field --}}
            <div class="input-group mb-3">
                <input type="email" name="company_email" class="form-control @error('company_email') is-invalid @enderror"
                       value="{{ old('company_email') }}" placeholder="Email Perusahaan" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
                @error('company_email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Company Address field --}}
            <div class="input-group mb-3">
                <textarea name="company_address" class="form-control @error('company_address') is-invalid @enderror"
                          placeholder="Alamat Perusahaan" rows="2">{{ old('company_address') }}</textarea>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-map-marker-alt"></span>
                    </div>
                </div>
                @error('company_address')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Province and City --}}
            <div class="row">
                <div class="col-6">
                    <div class="input-group mb-3">
                        <input type="text" name="company_province" class="form-control @error('company_province') is-invalid @enderror"
                               value="{{ old('company_province') }}" placeholder="Provinsi">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-map"></span>
                            </div>
                        </div>
                        @error('company_province')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="input-group mb-3">
                        <input type="text" name="company_city" class="form-control @error('company_city') is-invalid @enderror"
                               value="{{ old('company_city') }}" placeholder="Kota/Kabupaten">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-city"></span>
                            </div>
                        </div>
                        @error('company_city')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- District and Postal Code --}}
            <div class="row">
                <div class="col-6">
                    <div class="input-group mb-3">
                        <input type="text" name="company_district" class="form-control @error('company_district') is-invalid @enderror"
                               value="{{ old('company_district') }}" placeholder="Kecamatan">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-map-pin"></span>
                            </div>
                        </div>
                        @error('company_district')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="input-group mb-3">
                        <input type="text" name="company_postal_code" class="form-control @error('company_postal_code') is-invalid @enderror"
                               value="{{ old('company_postal_code') }}" placeholder="Kode Pos">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-mail-bulk"></span>
                            </div>
                        </div>
                        @error('company_postal_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Phone and Website --}}
            <div class="row">
                <div class="col-6">
                    <div class="input-group mb-3">
                        <input type="text" name="company_phone" class="form-control @error('company_phone') is-invalid @enderror"
                               value="{{ old('company_phone') }}" placeholder="Nomor Telepon">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-phone"></span>
                            </div>
                        </div>
                        @error('company_phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-6">
                    <div class="input-group mb-3">
                        <input type="url" name="company_website" class="form-control @error('company_website') is-invalid @enderror"
                               value="{{ old('company_website') }}" placeholder="Website">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-globe"></span>
                            </div>
                        </div>
                        @error('company_website')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-primary btn-block" id="nextStep">
                <i class="fas fa-arrow-right"></i> Lanjut ke Step 2
            </button>
        </div>

        <!-- Step 2: Owner Information -->
        <div class="step-content" id="step2">
            <h6 class="text-center mb-3"><i class="fas fa-user"></i> Informasi Pemilik</h6>

            {{-- Owner Name field --}}
            <div class="input-group mb-3">
                <input type="text" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror"
                       value="{{ old('owner_name') }}" placeholder="Nama Pemilik" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
                @error('owner_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Owner Email field --}}
            <div class="input-group mb-3">
                <input type="email" name="owner_email" class="form-control @error('owner_email') is-invalid @enderror"
                       value="{{ old('owner_email') }}" placeholder="Email Pemilik" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
                @error('owner_email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Password field --}}
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                       placeholder="Password" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Password confirmation field --}}
            <div class="input-group mb-3">
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="Konfirmasi Password" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <button type="button" class="btn btn-secondary btn-block" id="prevStep">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </button>
                </div>
                <div class="col-6">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-user-plus"></i> Daftar
                    </button>
                </div>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    <p class="my-0">
        <a href="{{ route('auth.login') }}">
            Sudah punya akun? Login disini
        </a>
    </p>
@stop

@push('css')
<style>
/* Custom width untuk form register */
.login-box {
    width: 600px !important;
    max-width: 90vw !important;
}

.login-card-body {
    padding: 2rem !important;
}

.step-content {
    display: none;
}
.step-content.active {
    display: block;
}

/* Responsive untuk mobile */
@media (max-width: 768px) {
    .login-box {
        width: 95vw !important;
        margin: 0 auto !important;
    }

    .login-card-body {
        padding: 1.5rem !important;
    }
}

/* Responsive untuk tablet */
@media (min-width: 769px) and (max-width: 1024px) {
    .login-box {
        width: 80vw !important;
        max-width: 700px !important;
    }
}
</style>
@endpush

@push('js')
<script>
$(document).ready(function() {
    let currentStep = 1;

    // Next step
    $('#nextStep').on('click', function() {
        if (validateStep1()) {
            $('#step1').removeClass('active');
            $('#step2').addClass('active');
            currentStep = 2;
        }
    });

    // Previous step
    $('#prevStep').on('click', function() {
        $('#step2').removeClass('active');
        $('#step1').addClass('active');
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
            const input = $(`[name="${field}"]`);
            if (!input.val().trim()) {
                input.addClass('is-invalid');
                isValid = false;
            }
        });

        // Validate email format
        const email = $('[name="company_email"]').val();
        if (email && !isValidEmail(email)) {
            $('[name="company_email"]').addClass('is-invalid');
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
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mendaftar...');

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

                        // Show error message from AJAX response
                        const errorDiv = input.siblings('.invalid-feedback');
                        if (errorDiv.length === 0) {
                            input.after(`<span class="invalid-feedback" role="alert"><strong>${response.errors[field][0]}</strong></span>`);
                        } else {
                            errorDiv.html(`<strong>${response.errors[field][0]}</strong>`);
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
