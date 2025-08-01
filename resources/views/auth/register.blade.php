@extends('adminlte::auth.login')

@section('title', 'Register - Akuntansi Klik Medis')

@section('auth_header', 'Registrasi Perusahaan')

@section('adminlte_css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .input-group-text {
            border-radius: 6px;
            background-color: #f8f9fa;
        }

        .btn-primary {
            border-radius: 6px;
            padding: 12px 20px;
            font-weight: 500;
            transition: all 0.15s ease-in-out;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        }

        /* Section styling */
        .section-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e9ecef;
        }

        .section-header {
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #dee2e6;
        }

        .section-header h6 {
            margin: 0;
            font-weight: 600;
            color: #495057;
        }

        /* Error styling */
        .alert {
            border-radius: 6px;
            margin-bottom: 1rem;
        }

        .invalid-feedback {
            font-size: 0.875rem;
        }

        /* Password requirements styling */
        .password-requirements {
            background: #e7f3ff;
            border: 1px solid #b3d7ff;
            border-radius: 4px;
            padding: 0.5rem 0.75rem;
        }

        .password-requirements small {
            color: #0066cc !important;
        }

        /* Loading spinner */
        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-box {
                width: 95vw !important;
            }

            .login-card-body {
                padding: 1.5rem !important;
            }

            .section-container {
                padding: 1rem;
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
    <form action="{{ route('register.post') }}" method="post" id="registerForm">
        @csrf

        <!-- Informasi Perusahaan -->
        <div class="section-container">
            <div class="section-header">
                <h6><i class="fas fa-building text-primary me-2"></i> Informasi Perusahaan</h6>
            </div>

            <!-- Company Name -->
            <div class="input-group mb-3">
                <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror"
                       value="{{ old('company_name') }}" placeholder="Nama Perusahaan" required>
                <div class="input-group-append">
                    <div class="input-group-text"><span class="fas fa-building"></span></div>
                </div>
                @error('company_name')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <!-- Company Email -->
            <div class="input-group mb-3">
                <input type="email" name="company_email" class="form-control @error('company_email') is-invalid @enderror"
                       value="{{ old('company_email') }}" placeholder="Email Perusahaan" required>
                <div class="input-group-append">
                    <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                </div>
                @error('company_email')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <!-- Company Address -->
            <div class="input-group mb-3">
                <textarea name="company_address" class="form-control @error('company_address') is-invalid @enderror"
                          placeholder="Alamat Perusahaan" rows="2">{{ old('company_address') }}</textarea>
                <div class="input-group-append">
                    <div class="input-group-text"><span class="fas fa-map-marker-alt"></span></div>
                </div>
                @error('company_address')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <!-- Province and City -->
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <input type="text" name="company_province" class="form-control @error('company_province') is-invalid @enderror"
                               value="{{ old('company_province') }}" placeholder="Provinsi">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-map"></span></div>
                        </div>
                        @error('company_province')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <input type="text" name="company_city" class="form-control @error('company_city') is-invalid @enderror"
                               value="{{ old('company_city') }}" placeholder="Kota/Kabupaten">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-city"></span></div>
                        </div>
                        @error('company_city')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- District and Postal Code -->
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <input type="text" name="company_district" class="form-control @error('company_district') is-invalid @enderror"
                               value="{{ old('company_district') }}" placeholder="Kecamatan">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-map-pin"></span></div>
                        </div>
                        @error('company_district')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <input type="text" name="company_postal_code" class="form-control @error('company_postal_code') is-invalid @enderror"
                               value="{{ old('company_postal_code') }}" placeholder="Kode Pos">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-mail-bulk"></span></div>
                        </div>
                        @error('company_postal_code')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Phone and Website -->
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <input type="text" name="company_phone" class="form-control @error('company_phone') is-invalid @enderror"
                               value="{{ old('company_phone') }}" placeholder="Nomor Telepon">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-phone"></span></div>
                        </div>
                        @error('company_phone')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <input type="url" name="company_website" class="form-control @error('company_website') is-invalid @enderror"
                               value="{{ old('company_website') }}" placeholder="Website">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-globe"></span></div>
                        </div>
                        @error('company_website')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Pemilik -->
        <div class="section-container">
            <div class="section-header">
                <h6><i class="fas fa-user text-success me-2"></i> Informasi Pemilik</h6>
            </div>

            <!-- Owner Name -->
            <div class="input-group mb-3">
                <input type="text" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror"
                       value="{{ old('owner_name') }}" placeholder="Nama Pemilik" required>
                <div class="input-group-append">
                    <div class="input-group-text"><span class="fas fa-user"></span></div>
                </div>
                @error('owner_name')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <!-- Owner Email -->
            <div class="input-group mb-3">
                <input type="email" name="owner_email" class="form-control @error('owner_email') is-invalid @enderror"
                       value="{{ old('owner_email') }}" placeholder="Email Pemilik" required>
                <div class="input-group-append">
                    <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                </div>
                @error('owner_email')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <!-- Password -->
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                       placeholder="Password" required>
                <div class="input-group-append">
                    <div class="input-group-text"><span class="fas fa-lock"></span></div>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="password-requirements mb-3">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> Password harus mengandung minimal 8 karakter dengan kombinasi huruf besar, huruf kecil, angka, dan simbol.
                </small>
            </div>

            <!-- Password Confirmation -->
            <div class="input-group mb-3">
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="Konfirmasi Password" required>
                <div class="input-group-append">
                    <div class="input-group-text"><span class="fas fa-lock"></span></div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
        </button>
    </form>
@stop

@section('auth_footer')
    <p class="my-0 mx-auto">
        Sudah punya akun?
        <a href="{{ route('login') }}">
            Login disini
        </a>
    </p>
@stop

@section('adminlte_js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            const $form = $('#registerForm');
            const $submitBtn = $form.find('button[type="submit"]');
            const originalBtnText = $submitBtn.html();

            // Handle form submission
            $form.on('submit', function(e) {
                e.preventDefault();

                // Clear previous errors
                clearErrors();

                // Show loading state
                setLoadingState(true);

                // Submit form via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                .done(function(response) {
                    if (response.success) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Pendaftaran Berhasil!',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            // Redirect to dashboard
                            window.location.href = response.redirect;
                        });
                    } else {
                        showErrorMessage(response.message);
                    }
                })
                .fail(function(xhr) {
                    if (xhr.status === 422) {
                        // Validation errors
                        const response = xhr.responseJSON;
                        if (response.errors) {
                            showValidationErrors(response.errors);
                        } else {
                            showErrorMessage(response.message || 'Data yang dimasukkan tidak valid.');
                        }
                    } else {
                        // General error
                        const response = xhr.responseJSON;
                        showErrorMessage(response?.message || 'Terjadi kesalahan saat memproses pendaftaran.');
                    }
                })
                .always(function() {
                    setLoadingState(false);
                });
            });

            /**
             * Set loading state
             */
            function setLoadingState(loading) {
                if (loading) {
                    $submitBtn.prop('disabled', true)
                             .html('<i class="fas fa-spinner fa-spin me-2"></i> Sedang Memproses...');
                } else {
                    $submitBtn.prop('disabled', false)
                             .html(originalBtnText);
                }
            }

            /**
             * Clear all error states
             */
            function clearErrors() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('.alert-danger').remove();
            }

            /**
             * Show validation errors
             */
            function showValidationErrors(errors) {
                $.each(errors, function(field, messages) {
                    const $input = $(`[name="${field}"]`);
                    const $inputGroup = $input.closest('.input-group');

                    // Add error class
                    $input.addClass('is-invalid');

                    // Add error message
                    const errorMessage = Array.isArray(messages) ? messages[0] : messages;
                    $inputGroup.append(`<span class="invalid-feedback d-block" role="alert"><strong>${errorMessage}</strong></span>`);

                    // Focus on first error field
                    if (Object.keys(errors)[0] === field) {
                        $input.focus();
                    }
                });
            }

            /**
             * Show general error message
             */
            function showErrorMessage(message) {
                const errorAlert = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Error!</strong> ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                $form.prepend(errorAlert);

                // Scroll to top to show error
                $('html, body').animate({
                    scrollTop: $form.offset().top - 100
                }, 500);
            }

            // Clear error state when user starts typing
            $form.find('input, textarea').on('input', function() {
                $(this).removeClass('is-invalid');
                $(this).closest('.input-group').find('.invalid-feedback').remove();
            });

            // Show/hide password
            const $passwordToggle = `
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            `;

            // Add password toggle buttons
            $('input[name="password"], input[name="password_confirmation"]').each(function() {
                const $input = $(this);
                const $inputGroup = $input.closest('.input-group');

                // Add toggle button after the existing append
                $inputGroup.find('.input-group-append').after($passwordToggle.replace('togglePassword', 'togglePassword_' + Math.random().toString(36).substr(2, 9)));
            });

            // Handle password toggle
            $(document).on('click', '[id^="togglePassword"]', function() {
                const $btn = $(this);
                const $input = $btn.closest('.input-group').find('input');
                const $icon = $btn.find('i');

                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    $input.attr('type', 'password');
                    $icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
@stop
