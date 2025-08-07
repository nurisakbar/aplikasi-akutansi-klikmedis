@extends('layouts.base')

@section('page_title', 'Tambah Jurnal Umum')

@section('page_content')
<div class="container-fluid">
    <form id="journal-entry-form" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Input Jurnal Umum</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                            <div class="invalid-feedback" id="date-error"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="reference">Referensi</label>
                            <input type="text" name="reference" id="reference" class="form-control" value="{{ old('reference') }}">
                            <div class="invalid-feedback" id="reference-error"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    <div class="invalid-feedback" id="description-error"></div>
                </div>

                <div class="form-group">
                    <label for="attachment">Lampiran (Attachment)</label>
                    <input type="file" name="attachment" id="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <small class="form-text text-muted">Format yang diizinkan: PDF, JPG, JPEG, PNG. Maksimal 2MB.</small>
                    <div class="invalid-feedback" id="attachment-error"></div>
                </div>

                <hr>
                <h5>Baris Jurnal</h5>
                <table class="table table-bordered" id="lines-table">
                    <thead>
                        <tr>
                            <th>Akun <span class="text-danger">*</span></th>
                            <th>Debit <span class="text-danger">*</span></th>
                            <th>Kredit <span class="text-danger">*</span></th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="lines[0][chart_of_account_id]" class="form-control chart-account-select" required>
                                    <option value="">Pilih Akun</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </td>
                            <td>
                                <input type="number" name="lines[0][debit]" class="form-control debit-input" step="0.01" min="0" required>
                                <div class="invalid-feedback"></div>
                            </td>
                            <td>
                                <input type="number" name="lines[0][credit]" class="form-control credit-input" step="0.01" min="0" required>
                                <div class="invalid-feedback"></div>
                            </td>
                            <td>
                                <input type="text" name="lines[0][description]" class="form-control">
                                <div class="invalid-feedback"></div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm btn-remove-line">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-secondary btn-sm" id="add-line">
                    <i class="fas fa-plus"></i> Tambah Baris
                </button>
                <div class="mt-3">
                    <strong>Total Debit: <span id="total-debit">0.00</span></strong> | 
                    <strong>Total Kredit: <span id="total-credit">0.00</span></strong>
                    <div id="balance-status" class="mt-1"></div>
                </div>
                <div class="invalid-feedback" id="lines-error"></div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary">
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
    let lineIndex = 1;

    // Function untuk menambah baris baru
    $('#add-line').on('click', function() {
        let row = `<tr>
            <td>
                <select name="lines[${lineIndex}][chart_of_account_id]" class="form-control chart-account-select" required>
                    <option value="">Pilih Akun</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback"></div>
            </td>
            <td>
                <input type="number" name="lines[${lineIndex}][debit]" class="form-control debit-input" step="0.01" min="0" required>
                <div class="invalid-feedback"></div>
            </td>
            <td>
                <input type="number" name="lines[${lineIndex}][credit]" class="form-control credit-input" step="0.01" min="0" required>
                <div class="invalid-feedback"></div>
            </td>
            <td>
                <input type="text" name="lines[${lineIndex}][description]" class="form-control">
                <div class="invalid-feedback"></div>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm btn-remove-line">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        </tr>`;
        $('#lines-table tbody').append(row);
        lineIndex++;
        updateTotals();
    });

    // Function untuk menghapus baris
    $(document).on('click', '.btn-remove-line', function() {
        if ($('#lines-table tbody tr').length > 1) {
            $(this).closest('tr').remove();
            updateTotals();
        } else {
            Swal.fire('Peringatan!', 'Minimal harus ada satu baris jurnal.', 'warning');
        }
    });

    // Function untuk update total debit dan kredit
    function updateTotals() {
        let totalDebit = 0;
        let totalCredit = 0;

        $('.debit-input').each(function() {
            totalDebit += parseFloat($(this).val()) || 0;
        });

        $('.credit-input').each(function() {
            totalCredit += parseFloat($(this).val()) || 0;
        });

        $('#total-debit').text(totalDebit.toFixed(2));
        $('#total-credit').text(totalCredit.toFixed(2));

        const balanceStatus = $('#balance-status');
        if (Math.abs(totalDebit - totalCredit) < 0.01) {
            balanceStatus.html('<span class="text-success"><i class="fas fa-check-circle"></i> Debit dan Kredit seimbang</span>');
        } else {
            balanceStatus.html('<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Debit dan Kredit tidak seimbang</span>');
        }
    }

    // Event listener untuk input debit dan kredit
    $(document).on('input', '.debit-input, .credit-input', function() {
        updateTotals();
    });

    // Form submission handler
    $('#journal-entry-form').on('submit', function(e) {
        e.preventDefault();
        saveJournalEntry(this);
    });

    // Function untuk save journal entry
    function saveJournalEntry(formElement) {
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        // Validate totals
        let totalDebit = 0;
        let totalCredit = 0;

        $('.debit-input').each(function() {
            totalDebit += parseFloat($(this).val()) || 0;
        });

        $('.credit-input').each(function() {
            totalCredit += parseFloat($(this).val()) || 0;
        });

        if (Math.abs(totalDebit - totalCredit) >= 0.01) {
            Swal.fire('Error!', 'Total debit dan kredit harus seimbang.', 'error');
            return;
        }

        // Prepare form data
        const formData = new FormData(formElement);
        formData.append('_token', '{{ csrf_token() }}');

        // Show loading
        $('.btn-success').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: '{{ route('journal-entries.store') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Success response:', response);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message || 'Jurnal berhasil disimpan.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '{{ route('journal-entries.index') }}';
                });
            },
            error: function(xhr) {
                console.log('Error response:', xhr);
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    console.log('Validation errors:', errors);
                    
                    Object.keys(errors).forEach(function(field) {
                        if (field.startsWith('lines.')) {
                            // Handle line errors
                            const parts = field.split('.');
                            const lineIndex = parts[1];
                            const fieldName = parts[2];
                            const selector = `[name="lines[${lineIndex}][${fieldName}]"]`;
                            $(selector).addClass('is-invalid');
                            $(selector).siblings('.invalid-feedback').text(errors[field][0]);
                        } else {
                            // Handle main form errors
                            $(`#${field}`).addClass('is-invalid');
                            $(`#${field}-error`).text(errors[field][0]);
                        }
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

    // Initialize totals on page load
    updateTotals();
});
</script>
@endpush 