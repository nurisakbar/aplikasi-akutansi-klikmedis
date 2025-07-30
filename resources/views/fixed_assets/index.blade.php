@extends('layouts.base')

@section('page_title', 'Aset Tetap')

@section('page_content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Aset Tetap</h3>
            <a href="{{ route('fixed-assets.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>
        <div class="card-body">
            <table id="assets-table" class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Tgl Perolehan</th>
                        <th>Nilai Perolehan</th>
                        <th>Umur</th>
                        <th>Metode</th>
                        <th>Nilai Residu</th>
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
    var table = $('#assets-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('fixed-assets.index') }}',
            type: 'GET'
        },
        columns: [
            { data: 'code', name: 'code' },
            { data: 'name', name: 'name' },
            { data: 'category', name: 'category' },
            { data: 'acquisition_date', name: 'acquisition_date' },
            { data: 'acquisition_value', name: 'acquisition_value', className: 'text-end' },
            { data: 'useful_life', name: 'useful_life' },
            { data: 'depreciation_method', name: 'depreciation_method' },
            { data: 'residual_value', name: 'residual_value', className: 'text-end' },
            { data: 'description', name: 'description' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });
});

function deleteAsset(id) {
    Swal.fire({
        title: 'Hapus Data Aset?',
        text: 'Data aset yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            var form = $('#delete-form');
            form.attr('action', '/fixed-assets/' + id);
            form.submit();
        }
    });
}
</script>
@endpush 