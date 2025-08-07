@extends('adminlte::auth.login')

@section('title', 'Login - Akuntansi Klik Medis')

@section('auth_header', 'Login ke Sistem Akuntansi')

@section('adminlte_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* Override AdminLTE default width untuk login - lebih kecil dari register */
        .login-box {
            width: 450px !important;
            max-width: 95vw !important;
        }

        .login-card-body {
            padding: 2rem !important;
        }

        .form-control {
            border-radius: 6px;
            padding: 12px 15px;
        }

        .input-group-text {
            border-radius: 6px;
        }

        .btn-primary {
            border-radius: 6px;
            padding: 12px 20px;
        }

        /* Styling untuk error messages */
        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-box {
                width: 95vw !important;
            }

            .login-card-body {
                padding: 1.5rem !important;
            }
        }

        @media (min-width: 1200px) {
            .login-box {
                width: 500px !important;
            }
        }
    </style>
@endsection

@section('auth_body')
    <form id="loginForm" method="POST">
        @csrf

        <!-- Email field -->
        <div class="form-group mb-3">
            <div class="input-group">
                <input type="email" name="email" id="email" class="form-control"
                       value="{{ old('email') }}" placeholder="Email" autofocus>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="invalid-feedback" id="email-error"></div>
        </div>

        <!-- Password field -->
        <div class="form-group mb-3">
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control"
                       placeholder="Password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="invalid-feedback" id="password-error"></div>
        </div>

        <!-- Remember Me & Submit -->
        <div class="row">
            <div class="col-7">
                <div class="icheck-primary">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">
                        Ingat Saya
                    </label>
                </div>
            </div>
            <div class="col-5">
                <button type="submit" class="btn btn-primary btn-block" id="loginBtn">
                    <i class="fas fa-sign-in-alt me-1"></i>
                    Masuk
                </button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    {{-- Register link --}}
    <p class="my-0 mx-auto">
        Belum punya akun?
        <a href="{{ route('register') }}">
            Daftar disini
        </a>
    </p>

    {{-- Forgot Password link --}}
    {{-- <p class="my-0 mx-auto mt-2">
        <a href="{{ route('password.request') }}">
            Lupa password?
        </a>
    </p> --}}
@stop

@section('adminlte_js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        // Form submission handler
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            login();
        });

        // Function untuk login
        function login() {
            const formData = {
                email: $('#email').val(),
                password: $('#password').val(),
                remember: $('#remember').is(':checked') ? 1 : 0,
                _token: '{{ csrf_token() }}'
            };

            $.ajax({
                url: '{{ route('login.post') }}',
                type: 'POST',
                data: formData,
                beforeSend: function() {
                    // Disable button dan tampilkan loading
                    $('#loginBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Masuk...');
                    
                    // Clear previous errors
                    $('.form-control').removeClass('is-invalid');
                    $('.invalid-feedback').hide().text('');
                },
                success: function(response) {
                    if (response.success) {
                        // Success - tampilkan SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Login berhasil.',
                            timer: 2000,
                            showConfirmButton: false,
                            allowOutsideClick: false
                        }).then(() => {
                            window.location.href = response.redirect || '{{ route('chart-of-accounts.index') }}';
                        });
                    } else {
                        // Failed - tampilkan SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Login!',
                            text: response.message || 'Email atau password salah.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Validation errors - tampilkan di bawah input
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            Object.keys(errors).forEach(function(field) {
                                const fieldElement = $(`#${field}`);
                                const errorElement = $(`#${field}-error`);
                                
                                fieldElement.addClass('is-invalid');
                                errorElement.text(errors[field][0]).show();
                            });
                        } else {
                            // General validation error
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Data yang diberikan tidak valid.',
                                confirmButtonText: 'OK'
                            });
                        }
                    } else {
                        // Server error - tampilkan SweetAlert
                        const response = xhr.responseJSON;
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response?.message || 'Terjadi kesalahan saat login.',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                complete: function() {
                    // Re-enable button
                    $('#loginBtn').prop('disabled', false).html('<i class="fas fa-sign-in-alt me-1"></i> Masuk');
                }
            });
        }

        // Clear errors when user starts typing
        $('.form-control').on('input', function() {
            $(this).removeClass('is-invalid');
            $(`#${this.id}-error`).hide().text('');
        });
    });
    </script>
@stop
