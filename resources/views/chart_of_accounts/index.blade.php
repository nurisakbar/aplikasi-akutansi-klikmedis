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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('error') }}
        </div>
    @endif

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

<!-- Form untuk delete (tersembunyi) -->
<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('custom_js')
<script>
$(document).ready(function() {
    // Inisialisasi DataTable
    let table = $('#accounts-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("chart-of-accounts.index") }}',
        columns: [
            {data: 'code_formatted', name: 'code', orderable: true, searchable: true},
            {data: 'name_formatted', name: 'name', orderable: true, searchable: true},
            {data: 'type_formatted', name: 'type', orderable: true, searchable: true},
            {data: 'category_formatted', name: 'category', orderable: true, searchable: true},
            {data: 'parent_formatted', name: 'parent_id', orderable: false, searchable: false},
            {data: 'level', name: 'level', orderable: true, searchable: false},
            {data: 'status_formatted', name: 'is_active', orderable: true, searchable: false},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[0, 'asc']],
        pageLength: 25,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        }
    });

    // Event handler untuk tombol delete
    $('#accounts-table').on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const deleteUrl = $(this).data('url');
        
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
                // Kirim request delete
                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Terhapus!',
                                response.message,
                                'success'
                            );
                            // Reload DataTable
                            table.ajax.reload();
                        } else {
                            Swal.fire(
                                'Gagal!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat menghapus data.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Tampilkan SweetAlert untuk pesan sukses/error dari session
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}'
        });
    @endif

    $('#export-excel').on('click', function() {
        window.location.href = '{{ route('chart-of-accounts.export') }}';
    });
});
</script>
@endpush 