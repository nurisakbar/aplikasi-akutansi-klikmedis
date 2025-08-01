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
    <form action="{{ route('login.post') }}" method="post" id="loginForm">
        @csrf

        <!-- Email field -->
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="Email" autofocus required>
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

        <!-- Password field -->
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
                <button type="submit" class="btn btn-primary btn-block">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stop
