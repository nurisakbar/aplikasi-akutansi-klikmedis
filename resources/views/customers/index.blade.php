@extends('layouts.base')

@section('page_title', 'Data Customers')

@section('page_content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-3">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users"></i> Data Customers
            </h1>
            <p class="text-muted">Kelola data customer perusahaan</p>
        </div>
    </div>

    <!-- Action Buttons Row -->
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="{{ route('customers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Customer
            </a>
            <button type="button" id="export-excel" class="btn btn-success ml-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
    </div>

    <!-- Filter Row -->
    <div class="row mb-3">
        <div class="col-12">
            <form id="filter-form" class="form-inline">
                <div class="form-group mr-3">
                    <label for="filter-search" class="mr-2">Cari Customer</label>
                    <input type="text" id="filter-search" name="search" 
                           class="form-control form-control-sm" 
                           placeholder="Nama atau email customer" 
                           value="{{ request('search') }}">
                </div>
                <div class="form-group mr-3">
                    <label for="filter-status" class="mr-2">Status</label>
                    <select id="filter-status" name="status" class="form-control form-control-sm">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>Ditahan</option>
                    </select>
                </div>
                <div class="form-group mr-2">
                    <button type="submit" class="btn btn-secondary btn-sm">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
                <div class="form-group">
                    <button type="button" id="reset-filter" class="btn btn-light btn-sm">
                        <i class="fas fa-undo"></i> Reset Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- DataTable -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Customer</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="customers-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="8%">No</th>
                            <th width="15%">Nama Customer</th>
                            <th width="12%">Email</th>
                            <th width="10%">Telepon</th>
                            <th width="15%">Alamat</th>
                            <th width="10%">NPWP</th>
                            <th width="10%">Batas Kredit</th>
                            <th width="8%">Status</th>
                            <th width="12%">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom_css')
<style>
    .form-inline .form-group {
        margin-bottom: 0.5rem;
    }
    .table th {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
</style>
@endpush

@push('custom_js')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#customers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("customers.index") }}',
            data: function(d) {
                d.search = $('#filter-search').val();
                d.status = $('#filter-status').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'address', name: 'address'},
            {data: 'npwp', name: 'npwp'},
            {data: 'credit_limit_formatted', name: 'credit_limit', orderable: true, searchable: false},
            {data: 'status_badge', name: 'status', orderable: true, searchable: true},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[1, 'asc']],
        pageLength: 25,
        responsive: true,
        language: {
            url: '{{ asset("js/datatables-id.json") }}'
        }
    });

    // Filter form submission
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        table.draw();
    });

    // Reset filter
    $('#reset-filter').on('click', function() {
        $('#filter-search').val('');
        $('#filter-status').val('');
        table.draw();
    });

    // Export Excel
    $('#export-excel').on('click', function() {
        var search = $('#filter-search').val();
        var status = $('#filter-status').val();
        
        var url = '{{ route("customers.export") }}?';
        if (search) url += 'search=' + encodeURIComponent(search) + '&';
        if (status) url += 'status=' + encodeURIComponent(status);
        
        window.location.href = url;
    });

    // Delete confirmation with SweetAlert
    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus customer "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url('customers') }}/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire(
                            'Berhasil!',
                            response.message,
                            'success'
                        ).then(() => {
                            table.draw();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus customer.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>
@endpush
