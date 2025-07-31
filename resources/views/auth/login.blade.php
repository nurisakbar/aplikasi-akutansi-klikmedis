@extends('layouts.auth')

@section('title', 'Login - Akuntansi Klik Medis')
@section('card_width', '400px')
@section('header_icon', 'fas fa-calculator')
@section('header_title', 'Akuntansi Klik Medis')
@section('header_subtitle', 'Silakan login untuk melanjutkan')

@section('auth_content')
<form id="loginForm" method="POST" action="{{ route('auth.login.post') }}">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label required-field">Email</label>
        <input type="email"
               class="form-control @error('email') is-invalid @enderror"
               id="email"
               name="email"
               placeholder="Masukkan email Anda"
               value="{{ old('email') }}"
               required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password" class="form-label required-field">Password</label>
        <input type="password"
               class="form-control @error('password') is-invalid @enderror"
               id="password"
               name="password"
               placeholder="Masukkan password Anda"
               required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary w-100 mb-3">
        <i class="fas fa-sign-in-alt me-2"></i>Login
    </button>

    <div class="text-center">
        <p class="mb-0">Belum punya akun?
            <a href="{{ route('auth.register') }}" class="text-decoration-none">Daftar disini</a>
        </p>
    </div>
</form>
@endsection

@push('custom_js')
<script>
$(document).ready(function() {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Disable button and show loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Loading...');

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
                let errorMessage = 'Terjadi kesalahan saat login';

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
                    title: 'Gagal Login',
                    text: errorMessage
                });

                // Re-enable button
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Remove error styling when user starts typing
    $('input').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').remove();
    });
});
</script>
@endpush
