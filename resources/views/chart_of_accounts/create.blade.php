@extends('layouts.base')

@section('page_title', 'Tambah Akun')

@section('page_content')
<div class="container-fluid">
    <form action="{{ route('chart-of-accounts.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Input Akun Baru</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code">Kode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" 
                                   id="code" name="code" value="{{ old('code') }}" required maxlength="20">
                            <div class="invalid-feedback" id="code-error"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" 
                                   id="name" name="name" value="{{ old('name') }}" required maxlength="100">
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type">Tipe <span class="text-danger">*</span></label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="">Pilih Tipe</option>
                            </select>
                            <div class="invalid-feedback" id="type-error"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category">Kategori <span class="text-danger">*</span></label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                            </select>
                            <div class="invalid-feedback" id="category-error"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="parent_id">Parent Akun</label>
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option value="">Pilih Parent (Opsional)</option>
                                @foreach($parentAccounts as $account)
                                    @php
                                        $indent = '';
                                        if ($account->level > 1) {
                                            $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $account->level - 1) . '└─ ';
                                        }
                                    @endphp
                                    <option value="{{ $account->id }}" {{ old('parent_id') == $account->id ? 'selected' : '' }}>
                                        {!! $indent !!}{{ $account->code }} - {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Kosongkan jika ini adalah akun induk (root account)</small>
                            <div class="invalid-feedback" id="parent_id-error"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="is_active">Status</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea class="form-control" 
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    <div class="invalid-feedback" id="description-error"></div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-secondary">
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
    let accountTypes = {};
    let categoriesByType = {};

    // Load account types and categories from server
    function loadAccountTypesAndCategories() {
        $.ajax({
            url: '{{ route('chart-of-accounts.types-categories') }}',
            type: 'GET',
            success: function(response) {
                accountTypes = response.types;
                categoriesByType = response.categoriesByType;
                
                // Populate type dropdown
                populateTypeDropdown();
                
                // Initialize categories if type is already selected
                if ($('#type').val()) {
                    updateCategories();
                    setTimeout(function() {
                        $('#category').val('{{ old('category') }}');
                    }, 100);
                }
            },
            error: function(xhr) {
                console.error('Error loading account types and categories:', xhr);
                Swal.fire('Error!', 'Gagal memuat data tipe dan kategori akun.', 'error');
            }
        });
    }

    // Populate type dropdown
    function populateTypeDropdown() {
        const typeSelect = $('#type');
        typeSelect.find('option:not(:first)').remove();
        
        Object.keys(accountTypes).forEach(function(value) {
            const label = accountTypes[value];
            const selected = '{{ old('type') }}' === value ? 'selected' : '';
            typeSelect.append(`<option value="${value}" ${selected}>${label}</option>`);
        });
    }

    // Type change handler
    $('#type').on('change', function() {
        updateCategories();
    });

    // Function untuk update categories based on type
    function updateCategories() {
        const type = $('#type').val();
        const categorySelect = $('#category');
        categorySelect.find('option:not(:first)').remove();

        if (categoriesByType[type]) {
            Object.keys(categoriesByType[type]).forEach(function(value) {
                const label = categoriesByType[type][value];
                categorySelect.append(`<option value="${value}">${label}</option>`);
            });
        }
    }

    // Load data on page load
    loadAccountTypesAndCategories();

    // Form submission handler
    $('form').on('submit', function(e) {
        e.preventDefault();
        saveAccount();
    });

    // Function untuk save account
    function saveAccount() {
        const formData = {
            code: $('#code').val(),
            name: $('#name').val(),
            type: $('#type').val(),
            category: $('#category').val(),
            parent_id: $('#parent_id').val() || null,
            description: $('#description').val(),
            is_active: $('#is_active').is(':checked') ? 1 : 0,
            _token: '{{ csrf_token() }}'
        };

        console.log('Saving account with data:', formData);

        $.ajax({
            url: '{{ route('chart-of-accounts.store') }}',
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $('.btn-success').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            },
            success: function(response) {
                console.log('Success response:', response);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message || 'Akun berhasil disimpan.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '{{ route('chart-of-accounts.index') }}';
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