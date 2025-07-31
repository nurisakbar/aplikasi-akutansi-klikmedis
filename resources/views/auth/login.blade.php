@extends('adminlte::auth.login')

@section('title', 'Login - Akuntansi Klik Medis')

@section('auth_header', 'Akuntansi Klik Medis')

@section('auth_body')
    <form action="{{ route('auth.login.post') }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="Email" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   placeholder="Password">
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

        {{-- Login field --}}
        <div class="row">
            <div class="col-7">
                <div class="icheck-primary">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">
                        Ingat Saya
                    </label>
                </div>
            </div>
            <div class="col-5">
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    <p class="my-0">
        <a href="{{ route('auth.register') }}">
            Daftar akun baru
        </a>
    </p>
@stop

@push('js')
<script>
$(document).ready(function() {
    $('form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        // Disable button and show loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');

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
