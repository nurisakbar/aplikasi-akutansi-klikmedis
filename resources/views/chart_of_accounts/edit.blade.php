@extends('layouts.base')

@section('page_title', 'Edit Akun')

@section('page_content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Akun</h3>
        </div>
        <div class="card-body">
            <form id="editAccountForm">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code">Kode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" 
                                   id="code" name="code" value="{{ $chart_of_account->code }}" required maxlength="20">
                            <div class="invalid-feedback" id="code-error"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" 
                                   id="name" name="name" value="{{ $chart_of_account->name }}" required maxlength="100">
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
                                @foreach($parentAccounts as $parent)
                                    @php
                                        $indent = '';
                                        if ($parent->level > 1) {
                                            $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $parent->level - 1) . '└─ ';
                                        }
                                    @endphp
                                    <option value="{{ $parent->id }}" {{ $chart_of_account->parent_id == $parent->id ? 'selected' : '' }}>
                                        {!! $indent !!}{{ $parent->code }} - {{ $parent->name }}
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
                                       {{ $chart_of_account->is_active == 1 ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Aktif</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea class="form-control" 
                              id="description" name="description" rows="3">{{ $chart_of_account->description }}</textarea>
                    <div class="invalid-feedback" id="description-error"></div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Update
                    </button>
                    <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('custom_js')
<script>
$(document).ready(function() {
    let accountTypes = {};
    let categoriesByType = {};
    const currentType = '{{ old('type', $chart_of_account->type) }}';
    const currentCategory = '{{ old('category', $chart_of_account->category) }}';

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
                if (currentType) {
                    updateCategories();
                    // Set values after categories are populated
                    setTimeout(function() {
                        $('#type').val(currentType);
                        
                        // Try to set category value
                        if (currentCategory) {
                            $('#category').val(currentCategory);
                            
                            // If category is not found in current type, force set the value
                            if (!$('#category').val()) {
                                $('#category option[value="' + currentCategory + '"]').prop('selected', true);
                            }
                        }
                    }, 200); // Increased timeout to ensure categories are loaded
                }
            },
            error: function(xhr) {
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
            const selected = currentType === value ? 'selected' : '';
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
        
        // Clear existing options but keep the first one
        categorySelect.find('option:not(:first)').remove();
        categorySelect.find('optgroup').remove();

        if (categoriesByType[type]) {
            Object.keys(categoriesByType[type]).forEach(function(value) {
                const label = categoriesByType[type][value];
                const selected = currentCategory === value ? 'selected' : '';
                categorySelect.append(`<option value="${value}" ${selected}>${label}</option>`);
            });
        }
        
        // If current category is not in the current type, show all categories as fallback
        if (currentCategory && (!categoriesByType[type] || !categoriesByType[type][currentCategory])) {
            // Clear and show all categories
            categorySelect.find('option:not(:first)').remove();
            Object.keys(categoriesByType).forEach(function(typeKey) {
                if (categoriesByType[typeKey]) {
                    Object.keys(categoriesByType[typeKey]).forEach(function(value) {
                        const label = categoriesByType[typeKey][value];
                        const selected = currentCategory === value ? 'selected' : '';
                        categorySelect.append(`<option value="${value}" ${selected}>${label}</option>`);
                    });
                }
            });
        }
    }

    // Load data on page load
    loadAccountTypesAndCategories();

    // Form submission handler
    $('#editAccountForm').on('submit', function(e) {
        e.preventDefault();
        updateAccount();
    });

    // Function untuk update account
    function updateAccount() {
        const formData = {
            code: $('#code').val(),
            name: $('#name').val(),
            type: $('#type').val(),
            category: $('#category').val(),
            parent_id: $('#parent_id').val() || null,
            description: $('#description').val(),
            is_active: $('#is_active').val(),
            _token: '{{ csrf_token() }}',
            _method: 'PUT'
        };



        $.ajax({
            url: '{{ route('chart-of-accounts.update', $chart_of_account->id) }}',
            type: 'POST',
            data: formData,
            beforeSend: function() {
                $('.btn-primary').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message || 'Akun berhasil diupdate.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '{{ route('chart-of-accounts.index') }}';
                });
            },
            error: function(xhr) {
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
                $('.btn-primary').prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Update');
            }
        });
    }
});
</script>
@endpush 