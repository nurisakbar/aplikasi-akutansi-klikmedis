@extends('layouts.base')

@section('page_title', 'Chart of Accounts')

@section('page_content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-6">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#accountModal" id="btnAdd">
                    <i class="fas fa-plus"></i> Tambah Akun
                </button>
                <button type="button" id="export-excel" class="btn btn-success ml-2">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Akun</h3>
            </div>
            <div class="card-body">
                <table id="accounts-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>Kategori</th>
                            <th>Parent</th>
                            <th>Level</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal untuk Create/Edit -->
    <div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="accountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="accountModalLabel">Tambah Akun Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="accountForm">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" id="account_id" name="account_id">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">Kode <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="code" name="code" required maxlength="20">
                                    <div class="invalid-feedback" id="code-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required maxlength="100">
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
                                        <option value="asset">Asset</option>
                                        <option value="liability">Liability</option>
                                        <option value="equity">Equity</option>
                                        <option value="revenue">Revenue</option>
                                        <option value="expense">Expense</option>
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
                                    </select>
                                    <div class="invalid-feedback" id="parent_id-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active">Status</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                                        <label class="custom-control-label" for="is_active">Aktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            <div class="invalid-feedback" id="description-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSave">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Form untuk delete (tersembunyi) -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('custom_js')
    <script>
        $(document).ready(function() {
            console.log('Document ready');

            // Inisialisasi DataTable
            let table = $('#accounts-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('chart-of-accounts.index') }}'
                },
                columns: [{
                        data: 'code_formatted',
                        name: 'code',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'name_formatted',
                        name: 'name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'type_formatted',
                        name: 'type',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'category_formatted',
                        name: 'category',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'parent_formatted',
                        name: 'parent_id',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'level',
                        name: 'level',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'status_formatted',
                        name: 'is_active',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [0, 'asc']
                ],
                pageLength: 25,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                }
            });

            console.log('DataTable initialized');

            // Load parent accounts
            loadParentAccounts();

            // Type change handler
            $('#type').on('change', function() {
                updateCategories();
            });

            // Modal events
            $('#accountModal').on('show.bs.modal', function (e) {
                const button = $(e.relatedTarget);
                const isEdit = button.hasClass('btn-edit');

                if (isEdit) {
                    const accountId = button.data('id');
                    loadAccountData(accountId);
                    $('#accountModalLabel').text('Edit Akun');
                } else {
                    resetForm();
                    $('#accountModalLabel').text('Tambah Akun Baru');
                }
            });

            // Form submission
            $('#accountForm').on('submit', function(e) {
                e.preventDefault();
                saveAccount();
            });

            // Event handler untuk tombol delete dengan class btn-delete
            $('#accounts-table').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const accountId = $(this).data('id');

                if (!accountId) {
                    Swal.fire('Error!', 'ID akun tidak ditemukan.', 'error');
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus akun ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteAccountAjax(accountId);
                    }
                });
            });

            // Function untuk load parent accounts
            function loadParentAccounts() {
                const parentSelect = $('#parent_id');
                parentSelect.find('option:not(:first)').remove();

                $.ajax({
                    url: '{{ route('chart-of-accounts.index') }}',
                    type: 'GET',
                    data: { parent_only: true },
                    success: function(response) {
                        console.log('Parent accounts loaded:', response.data);
                        if (response.data && response.data.length > 0) {
                            response.data.forEach(function(account) {
                                parentSelect.append(`<option value="${account.id}">${account.code} - ${account.name}</option>`);
                            });
                        }
                    },
                    error: function() {
                        console.log('Failed to load parent accounts');
                    }
                });
            }

            // Function untuk update categories based on type
            function updateCategories() {
                const type = $('#type').val();
                const categorySelect = $('#category');
                categorySelect.find('option:not(:first)').remove();

                console.log('Updating categories for type:', type);

                const categories = {
                    'asset': [
                        { value: 'current_asset', label: 'Current Asset' },
                        { value: 'fixed_asset', label: 'Fixed Asset' },
                        { value: 'other_asset', label: 'Other Asset' }
                    ],
                    'liability': [
                        { value: 'current_liability', label: 'Current Liability' },
                        { value: 'long_term_liability', label: 'Long Term Liability' }
                    ],
                    'equity': [
                        { value: 'equity', label: 'Equity' }
                    ],
                    'revenue': [
                        { value: 'operating_revenue', label: 'Operating Revenue' },
                        { value: 'other_revenue', label: 'Other Revenue' }
                    ],
                    'expense': [
                        { value: 'operating_expense', label: 'Operating Expense' },
                        { value: 'other_expense', label: 'Other Expense' }
                    ]
                };

                if (categories[type]) {
                    console.log('Adding categories:', categories[type]);
                    categories[type].forEach(function(category) {
                        categorySelect.append(`<option value="${category.value}">${category.label}</option>`);
                    });
                } else {
                    console.log('No categories found for type:', type);
                }
            }

            // Function untuk load account data for edit
            function loadAccountData(accountId) {
                $.ajax({
                    url: `{{ route('chart-of-accounts.index') }}/${accountId}/edit`,
                    type: 'GET',
                    success: function(response) {
                        $('#account_id').val(accountId);
                        $('#code').val(response.code);
                        $('#name').val(response.name);
                        $('#type').val(response.type).trigger('change');

                        // Wait for categories to load then set value
                        setTimeout(function() {
                            $('#category').val(response.category);
                        }, 100);

                        $('#parent_id').val(response.parent_id);
                        $('#description').val(response.description);
                        $('#is_active').prop('checked', response.is_active);
                    },
                    error: function() {
                        Swal.fire('Error!', 'Gagal memuat data akun.', 'error');
                    }
                });
            }

            // Function untuk reset form
            function resetForm() {
                $('#accountForm')[0].reset();
                $('#account_id').val('');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            }

            // Function untuk save account
            function saveAccount() {
                const accountId = $('#account_id').val();
                const url = accountId ?
                    `{{ route('chart-of-accounts.index') }}/${accountId}` :
                    '{{ route('chart-of-accounts.store') }}';
                const method = accountId ? 'PUT' : 'POST';

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

                if (method === 'PUT') {
                    formData._method = 'PUT';
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        $('#btnSave').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').text('');
                    },
                    success: function(response) {
                        console.log('Success response:', response);
                        $('#accountModal').modal('hide');
                        table.ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Akun berhasil disimpan.',
                            timer: 2000,
                            showConfirmButton: false
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
                        $('#btnSave').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
                    }
                });
            }

            // Function untuk delete dengan AJAX
            function deleteAccountAjax(accountId) {
                $.ajax({
                    url: '{{ route('chart-of-accounts.destroy', ':id') }}'.replace(':id', accountId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Sedang memproses permintaan Anda',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'Akun berhasil dihapus.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            // Reload DataTable
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Gagal menghapus akun.'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat menghapus data.';

                        if (xhr.status === 404) {
                            errorMessage = 'Data tidak ditemukan.';
                        } else if (xhr.status === 403) {
                            errorMessage = 'Anda tidak memiliki izin untuk menghapus data ini.';
                        } else if (xhr.status === 422) {
                            errorMessage = 'Data tidak dapat dihapus karena masih digunakan.';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    }
                });
            }

            // Tampilkan SweetAlert untuk pesan sukses/error dari session
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}'
                });
            @endif

            // Export Excel handler
            $('#export-excel').on('click', function() {
                window.location.href = '{{ route('chart-of-accounts.export') }}';
            });
        });
    </script>
@endpush
