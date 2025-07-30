@extends('layouts.base')

@section('page_title', 'Daftar Supplier')

@section('page_content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Daftar Supplier</h3>
            <a href="{{ route('suppliers.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah Supplier
            </a>
        </div>
        <div class="card-body">
            <table id="suppliers-table" class="table table-bordered table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
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
$(function() {
    var table = $('#suppliers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('suppliers.index') }}",
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    window.deleteSupplier = function(supplierId) {
        Swal.fire({
            title: 'Hapus Supplier?',
            text: 'Data supplier yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#delete-form');
                form.attr('action', '/suppliers/' + supplierId);
                form.submit();
            }
        });
    }
});
</script>
@endpush 