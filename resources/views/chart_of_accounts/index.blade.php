@extends('layouts.base')

@section('page_title', 'Chart of Accounts')

@section('page_content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-md-6">
                <a href="{{ route('chart-of-accounts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Akun
                </a>
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
                    url: '{{ asset('js/datatables-id.json') }}'
                }
            });

            console.log('DataTable initialized');

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
