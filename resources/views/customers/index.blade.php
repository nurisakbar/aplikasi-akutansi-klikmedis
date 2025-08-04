@extends('layouts.table')

@section('page_title', 'Data Customers')
@section('breadcrumb')
    <li class="breadcrumb-item active">Customers</li>
@endsection

@section('table_title', 'Data Customers')
@section('table_subtitle', 'Kelola data customer perusahaan')
@section('table_actions')
    <a href="{{ route('customers.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Tambah Customer
    </a>
    <button class="btn btn-warning btn-export" data-type="excel">
        <i class="fas fa-file-excel me-2"></i>Export Excel
    </button>
@endsection

@section('filters')
<div class="filters-section">
    <div class="filter-group">
        <div class="filter-item">
            <label class="filter-label">Cari Customer</label>
            <input type="text" class="form-control filter-input" id="search_customer" placeholder="Nama atau email customer">
        </div>
        <div class="filter-item">
            <label class="filter-label">Status</label>
            <select class="form-control filter-input" id="filter_status">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Tidak Aktif</option>
            </select>
        </div>
    </div>
</div>
@endsection

@section('table_content')
<table class="table table-striped datatable" id="customers-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Customer</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>NPWP</th>
            <th>Batas Kredit</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <!-- Data will be loaded via AJAX -->
    </tbody>
</table>
@endsection

@section('custom_js')
<script>
$(document).ready(function() {
    const table = $('#customers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("customers.index") }}',
            type: 'GET'
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'address', name: 'address'},
            {data: 'tax_number', name: 'tax_number'},
            {
                data: 'credit_limit',
                name: 'credit_limit',
                render: function(data) {
                    return data ? 'Rp ' + parseFloat(data).toLocaleString('id-ID') : '-';
                }
            },
            {
                data: 'status',
                name: 'status',
                render: function(data) {
                    const statusClass = data === 'active' ? 'status-active' : 'status-inactive';
                    const statusText = data === 'active' ? 'Aktif' : 'Tidak Aktif';
                    return `<span class="status-badge ${statusClass}">${statusText}</span>`;
                }
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group" role="group">
                            <a href="${row.show_url}" class="btn btn-info btn-sm btn-action" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="${row.edit_url}" class="btn btn-warning btn-sm btn-action" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteRecord('${row.delete_url}', 'Apakah Anda yakin ingin menghapus customer ${row.name}?')"
                                    class="btn btn-danger btn-sm btn-action" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[1, 'asc']],
        language: {
            url: '{{ asset('js/datatables-id.json') }}'
        }
    });

    // Search functionality
    $('#search_customer').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Status filter
    $('#filter_status').on('change', function() {
        const status = $(this).val();
        table.column(7).search(status).draw();
    });
});
</script>
@endsection
