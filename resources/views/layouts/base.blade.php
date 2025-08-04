@extends('adminlte::page')

@section('title', config('app.name', 'Akuntansi Klik Medis'))

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Minimal custom CSS for consistency with AdminLTE */
        .content-wrapper {
            background-color: #f4f6f9;
        }

        .form-control:focus {
            border-color: #3c8dbc;
            box-shadow: 0 0 0 0.2rem rgba(60, 141, 188, 0.25);
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        /* Custom SweetAlert styling to match AdminLTE */
        .swal2-popup {
            border-radius: 0.25rem;
        }

        .swal2-title {
            color: #343a40;
        }

        .swal2-content {
            color: #6c757d;
        }

        .swal2-confirm {
            background-color: #3c8dbc !important;
            border: none !important;
        }

        .swal2-cancel {
            background-color: #6c757d !important;
            border: none !important;
        }
    </style>
    @stack('custom_css')
@endsection

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">@yield('page_title', 'Chart of Accounts')</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('chart-of-accounts.index') }}">Home</a></li>
                    @yield('breadcrumb')
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fas fa-check"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fas fa-exclamation-triangle"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('page_content')
    </div>
@endsection

@section('js')
    <script>
        // Custom SweetAlert configuration to match AdminLTE
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'swal2-popup',
                title: 'swal2-title',
                content: 'swal2-content'
            }
        });

        // Auto-hide alerts after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });

        // Global AJAX error handler
        $(document).ajaxError(function(event, xhr, settings) {
            if (xhr.status === 401) {
                Swal.fire({
                    icon: 'error',
                    title: 'Sesi Berakhir',
                    text: 'Silakan login kembali',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'swal2-popup',
                        title: 'swal2-title',
                        content: 'swal2-content'
                    }
                }).then(() => {
                    window.location.href = '{{ route("login") }}';
                });
            }
        });

        // Flash Messages with SweetAlert
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3c8dbc',
                customClass: {
                    popup: 'swal2-popup',
                    title: 'swal2-title',
                    content: 'swal2-content'
                }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545',
                customClass: {
                    popup: 'swal2-popup',
                    title: 'swal2-title',
                    content: 'swal2-content'
                }
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: '{{ session('warning') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ffc107',
                customClass: {
                    popup: 'swal2-popup',
                    title: 'swal2-title',
                    content: 'swal2-content'
                }
            });
        @endif

        @if(session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: '{{ session('info') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#17a2b8',
                customClass: {
                    popup: 'swal2-popup',
                    title: 'swal2-title',
                    content: 'swal2-content'
                }
            });
        @endif
    </script>
    @stack('custom_js')
@endsection