@extends('layouts.base')

@section('page_title', 'Daftar Jurnal Umum')

@section('page_content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="{{ route('journal-entries.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Jurnal
            </a>
            <button type="button" id="export-excel" class="btn btn-success ml-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
        <div class="col-md-6 text-right">
            <form id="filter-form" class="form-inline float-right">
                <label for="filter-date-from" class="mr-2">Dari</label>
                <input type="date" id="filter-date-from" name="date_from" class="form-control mr-2" autocomplete="off">
                <label for="filter-date-to" class="mr-2">s/d</label>
                <input type="date" id="filter-date-to" name="date_to" class="form-control mr-2" autocomplete="off">
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                <button type="button" id="reset-filter" class="btn btn-light btn-sm ml-2">Reset</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Jurnal</h3>
        </div>
        <div class="card-body">
            <table id="journal-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Referensi</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Jumlah Baris</th>
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
    let table = $('#journal-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('journal-entries.index') }}',
            data: function(d) {
                d.date_from = $('#filter-date-from').val();
                d.date_to = $('#filter-date-to').val();
            }
        },
        columns: [
            {data: 'date', name: 'date'},
            {data: 'reference', name: 'reference'},
            {data: 'description', name: 'description'},
            {data: 'status_badge', name: 'status', orderable: true, searchable: true},
            {data: 'lines_count', name: 'lines_count', orderable: false, searchable: false},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        language: {
            url: '{{ asset('js/datatables-id.json') }}'
        }
    });

    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
    $('#reset-filter').on('click', function() {
        $('#filter-date-from').val('');
        $('#filter-date-to').val('');
        table.ajax.reload();
    });

    $('#journal-table').on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const deleteUrl = $(this).data('url');
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus jurnal ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Terhapus!', response.message, 'success');
                            table.ajax.reload();
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Terjadi kesalahan saat menghapus data.', 'error');
                    }
                });
            }
        });
    });

    $('#export-excel').on('click', function() {
        let dateFrom = $('#filter-date-from').val();
        let dateTo = $('#filter-date-to').val();
        let url = '{{ route('journal-entries.export') }}' + '?date_from=' + encodeURIComponent(dateFrom) + '&date_to=' + encodeURIComponent(dateTo);
        window.location.href = url;
    });

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
});
</script>
@endpush 