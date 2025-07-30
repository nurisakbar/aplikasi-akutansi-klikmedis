@extends('layouts.base')

@section('page_title', 'Daftar Customer')

@section('page_content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Daftar Customer</h3>
            <a href="{{ route('customers.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah Customer
            </a>
        </div>
        <div class="card-body">
            <table id="customers-table" class="table table-bordered table-hover mb-0">
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
    var table = $('#customers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('customers.index') }}",
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    window.deleteCustomer = function(customerId) {
        Swal.fire({
            title: 'Hapus Customer?',
            text: 'Data customer yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#delete-form');
                form.attr('action', '/customers/' + customerId);
                form.submit();
            }
        });
    }
});
</script>
@endpush 