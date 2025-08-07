@extends('layouts.base')

@section('page_title', 'Tambah Customer')

@section('page_content')
<div class="container-fluid">
    <form id="customer-form">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Customer Baru</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama Customer <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" 
                                   id="name" name="name" 
                                   value="{{ old('name') }}" required>
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company_name">Nama Perusahaan</label>
                            <input type="text" class="form-control" 
                                   id="company_name" name="company_name" 
                                   value="{{ old('company_name') }}">
                            <div class="invalid-feedback" id="company_name-error"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" 
                                   id="email" name="email" 
                                   value="{{ old('email') }}">
                            <div class="invalid-feedback" id="email-error"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Telepon</label>
                            <input type="text" class="form-control" 
                                   id="phone" name="phone" 
                                   value="{{ old('phone') }}">
                            <div class="invalid-feedback" id="phone-error"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Alamat</label>
                    <textarea class="form-control" 
                              id="address" name="address" rows="3">{{ old('address') }}</textarea>
                    <div class="invalid-feedback" id="address-error"></div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="npwp">NPWP</label>
                            <input type="text" class="form-control" 
                                   id="npwp" name="npwp" 
                                   value="{{ old('npwp') }}"
                                   placeholder="Contoh: 12.345.678.9-123.456">
                            <div class="invalid-feedback" id="npwp-error"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="credit_limit">Batas Kredit</label>
                            <input type="number" class="form-control" 
                                   id="credit_limit" name="credit_limit" 
                                   value="{{ old('credit_limit', 0) }}" min="0">
                            <div class="invalid-feedback" id="credit_limit-error"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                @foreach($customerStatuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', 'active') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="status-error"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_person">Contact Person</label>
                            <input type="text" class="form-control" 
                                   id="contact_person" name="contact_person" 
                                   value="{{ old('contact_person') }}">
                            <div class="invalid-feedback" id="contact_person-error"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="payment_terms">Syarat Pembayaran</label>
                    <input type="text" class="form-control" 
                           id="payment_terms" name="payment_terms" 
                           value="{{ old('payment_terms') }}"
                           placeholder="Contoh: Net 30, Net 60, Cash">
                    <div class="invalid-feedback" id="payment_terms-error"></div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

@push('custom_js')
<script>
$(document).ready(function() {
    // Form submission handler
    $('#customer-form').on('submit', function(e) {
        e.preventDefault();
        saveCustomer(this);
    });

    // Function untuk save customer
    function saveCustomer(formElement) {
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        // Prepare form data
        const formData = new FormData(formElement);
        formData.append('_token', '{{ csrf_token() }}');

        // Show loading
        $('.btn-success').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: '{{ route('customers.store') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Success response:', response);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message || 'Customer berhasil ditambahkan.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '{{ route('customers.index') }}';
                });
            },
            error: function(xhr) {
                console.log('Error response:', xhr);
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    console.log('Validation errors:', errors);
                    
                    Object.keys(errors).forEach(function(field) {
                        $(`#${field}`).addClass('is-invalid');
                        $(`#${field}-error`).text(errors[field][0]);
                    });
                } else {
                    Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan data.', 'error');
                }
            },
            complete: function() {
                $('.btn-success').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
            }
        });
    }
});
</script>
@endpush
