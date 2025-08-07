@extends('layouts.base')

@section('page_title', 'Edit Transaksi Kas & Bank')

@section('page_content')
<div class="container-fluid">
    <form id="cash-bank-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Transaksi Kas & Bank</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="accountancy_chart_of_account_id">Akun <span class="text-danger">*</span></label>
                            <select class="form-control" id="accountancy_chart_of_account_id" name="accountancy_chart_of_account_id" required>
                                <option value="">Pilih Akun</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('accountancy_chart_of_account_id', $cashBank->accountancy_chart_of_account_id) == $account->id ? 'selected' : '' }}>
                                        {{ $account->code }} - {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="accountancy_chart_of_account_id-error"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" 
                                   id="date" name="date" value="{{ old('date', $cashBank->date->format('Y-m-d')) }}" required>
                            <div class="invalid-feedback" id="date-error"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type">Tipe <span class="text-danger">*</span></label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="">Pilih Tipe</option>
                                @foreach($transactionTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('type', $cashBank->type->value) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="type-error"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="amount">Nominal <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" 
                                   id="amount" name="amount" value="{{ old('amount', $cashBank->amount) }}" required min="1">
                            <div class="invalid-feedback" id="amount-error"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                @foreach($transactionStatuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $cashBank->status->value) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="status-error"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bukti">Bukti</label>
                            @if($cashBank->bukti)
                                <div class="mb-2">
                                    <a href="{{ asset('storage/cash_bank_attachments/' . $cashBank->bukti) }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-download"></i> Download Bukti Saat Ini
                                    </a>
                                </div>
                            @endif
                            <input type="file" class="form-control" 
                                   id="bukti" name="bukti" accept=".jpg,.jpeg,.png,.pdf">
                            <small class="form-text text-muted">Format: JPG, PNG, PDF (Max: 2MB). Kosongkan jika tidak ingin mengubah bukti.</small>
                            <div class="invalid-feedback" id="bukti-error"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea class="form-control" 
                              id="description" name="description" rows="3">{{ old('description', $cashBank->description) }}</textarea>
                    <div class="invalid-feedback" id="description-error"></div>
                </div>

                <div class="form-group">
                    <label for="reference">Referensi</label>
                    <input type="text" class="form-control" 
                           id="reference" name="reference" value="{{ old('reference', $cashBank->reference) }}">
                    <div class="invalid-feedback" id="reference-error"></div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="{{ route('cash-bank.index') }}" class="btn btn-secondary">
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
    $('#cash-bank-form').on('submit', function(e) {
        e.preventDefault();
        updateCashBankTransaction(this);
    });

    // Function untuk update cash bank transaction
    function updateCashBankTransaction(formElement) {
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        // Prepare form data
        const formData = new FormData(formElement);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');

        // Show loading
        $('.btn-success').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: '{{ route('cash-bank.update', $cashBank->id) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Success response:', response);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message || 'Transaksi kas/bank berhasil diperbarui.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '{{ route('cash-bank.index') }}';
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
                    Swal.fire('Error!', 'Terjadi kesalahan saat memperbarui data.', 'error');
                }
            },
            complete: function() {
                $('.btn-success').prop('disabled', false).html('<i class="fas fa-save"></i> Update');
            }
        });
    }
});
</script>
@endpush 