@extends('layouts.base')

@section('page_title', 'Manajemen Pajak')

@section('page_content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="{{ route('taxes.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah
            </a>
            <button type="button" id="export-btn" class="btn btn-success ml-2">
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
    <form id="filter-form" class="row g-3 mb-3">
        <div class="col-md-3">
            <label>Jenis Pajak</label>
            <input type="text" name="type" class="form-control" placeholder="Semua" autocomplete="off">
        </div>
        <div class="col-md-2">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="">- Semua -</option>
                <option value="unpaid">Belum Lunas</option>
                <option value="paid">Lunas</option>
            </select>
        </div>
        <div class="col-md-2">
            <label>Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control">
        </div>
        <div class="col-md-2">
            <label>Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control">
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Daftar Pajak</h3>
        </div>
        <div class="card-body">
            <table id="taxes-table" class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>Jenis Pajak</th>
                        <th>No. Dokumen</th>
                        <th>Tanggal</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection

@push('custom_js')
<script>
$(document).ready(function() {
    var table = $('#taxes-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('taxes.index') }}',
            type: 'GET',
            data: function(d) {
                d.type = $('input[name=type]').val();
                d.status = $('select[name=status]').val();
                d.date_from = $('input[name=date_from]').val();
                d.date_to = $('input[name=date_to]').val();
            }
        },
        columns: [
            { data: 'type', name: 'type' },
            { data: 'document_number', name: 'document_number' },
            { data: 'date', name: 'date' },
            { data: 'amount', name: 'amount', className: 'text-end' },
            { data: 'status', name: 'status' },
            { data: 'description', name: 'description' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });

    $('#export-btn').on('click', function(e) {
        e.preventDefault();
        var params = $.param({
            type: $('input[name=type]').val(),
            status: $('select[name=status]').val(),
            date_from: $('input[name=date_from]').val(),
            date_to: $('input[name=date_to]').val()
        });
        window.location = '{{ route('taxes.export') }}?' + params;
    });

    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let form = $(this).closest('form');
        Swal.fire({
            title: 'Hapus Data Pajak?',
            text: 'Data pajak yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush 