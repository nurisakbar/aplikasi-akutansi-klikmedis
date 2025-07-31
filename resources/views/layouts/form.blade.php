@extends('layouts.base')

@section('custom_css')
<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        text-align: center;
    }

    .form-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .required-field::after {
        content: " *";
        color: #dc3545;
    }

    .error-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }

    @media (max-width: 768px) {
        .form-actions {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('page_content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h3 class="mb-0">
                <i class="fas fa-edit me-2"></i>
                @yield('form_title', 'Form')
            </h3>
            <p class="mb-0 mt-2 opacity-75">
                @yield('form_subtitle', 'Silakan isi data dengan lengkap')
            </p>
        </div>

        <div class="form-body">
            <form id="mainForm" method="POST" action="@yield('form_action')" enctype="multipart/form-data">
                @csrf
                @yield('form_method')

                @yield('form_content')

                <div class="form-actions">
                    <a href="@yield('cancel_url', 'javascript:history.back()')" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>@yield('submit_text', 'Simpan')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
$(document).ready(function() {
    // Form submission with AJAX
    $('#mainForm').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();

        // Disable button and show loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...');

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message || 'Data berhasil disimpan'
                    }).then(() => {
                        window.location.href = response.redirect || '{{ url()->previous() }}';
                    });
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                let errorMessage = 'Terjadi kesalahan saat menyimpan data';

                if (response && response.message) {
                    errorMessage = response.message;
                }

                // Handle validation errors
                if (response && response.errors) {
                    // Clear previous errors
                    $('.is-invalid').removeClass('is-invalid');
                    $('.error-feedback').remove();

                    Object.keys(response.errors).forEach(field => {
                        const input = $(`[name="${field}"]`);
                        input.addClass('is-invalid');

                        // Show error message
                        const errorDiv = input.siblings('.error-feedback');
                        if (errorDiv.length === 0) {
                            input.after(`<div class="error-feedback">${response.errors[field][0]}</div>`);
                        } else {
                            errorDiv.text(response.errors[field][0]);
                        }
                    });
                }

                Toast.fire({
                    icon: 'error',
                    title: errorMessage
                });

                // Re-enable button
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Remove error styling when user starts typing
    $('input, textarea, select').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.error-feedback').remove();
    });
});
</script>
@endsection
