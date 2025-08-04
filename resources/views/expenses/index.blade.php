@extends('layouts.base')

@section('page_content')
<div class="container-fluid">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm">
            <li class="breadcrumb-item"><a href="/">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Manajemen Beban</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header d-flex flex-nowrap justify-content-between align-items-center" style="gap:0.5rem; overflow-x:auto;">
            <h3 class="card-title mb-0">Manajemen Beban</h3>
            <form id="filter-form" class="d-flex flex-nowrap align-items-center mb-0" style="gap:0.5rem;">
                <select name="type" id="filter-type" class="form-control select2" style="min-width:180px">
                    @if(request('type'))
                        <option value="{{ request('type') }}" selected>{{ request('type') }}</option>
                    @endif
                </select>
                <select name="status" class="form-control" style="min-width:120px">
                    <option value="">- Semua -</option>
                    <option value="unpaid" {{ request('status')=='unpaid'?'selected':'' }}>Belum Lunas</option>
                    <option value="paid" {{ request('status')=='paid'?'selected':'' }}>Lunas</option>
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control" style="min-width:130px">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control" style="min-width:130px">
                <button class="btn btn-primary" type="submit">Filter</button>
            </form>
            <div class="d-flex flex-nowrap align-items-center" style="gap:0.5rem;">
                <button type="button" id="export-btn" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <a href="{{ route('expenses.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="expenses-table">
                <thead>
                    <tr>
                        <th>Jenis Beban</th>
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

@push('custom_css')
<link rel="stylesheet" href="/vendor/select2.min.css">
<style>
.select2-container .select2-selection--single {
    height: 38px !important;
    padding: 6px 12px;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    font-size: 1rem;
    background-color: #fff;
    box-shadow: none;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 24px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
    right: 10px;
}
.select2-container--default .select2-selection--single:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}
</style>
@endpush

@push('custom_js')
<script src="/vendor/select2.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#expenses-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('expenses.index') }}',
            type: 'GET',
            data: function(d) {
                d.type = $('#filter-type').val();
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
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
        ],
        language: {
            url: '{{ asset('js/datatables-id.json') }}'
        }
    });

    $('#filter-type').select2({
        placeholder: 'Pilih atau ketik jenis beban',
        tags: true,
        ajax: {
            url: '{{ route('expenses.types') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.map(function(item) {
                        return { id: item, text: item };
                    })
                };
            },
            cache: true
        },
        minimumInputLength: 0,
        allowClear: true,
        width: '100%'
    });

    // Reload DataTables saat filter disubmit
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });

    // Export Excel sesuai filter
    $('#export-btn').on('click', function(e) {
        e.preventDefault();
        var params = $.param({
            type: $('#filter-type').val(),
            status: $('select[name=status]').val(),
            date_from: $('input[name=date_from]').val(),
            date_to: $('input[name=date_to]').val()
        });
        window.location = '{{ route('expenses.export') }}?' + params;
    });

    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        let form = $(this).closest('form');
        Swal.fire({
            title: 'Hapus Data?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
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