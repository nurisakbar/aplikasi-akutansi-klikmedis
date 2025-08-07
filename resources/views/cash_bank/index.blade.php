@extends('layouts.base')

@section('page_title', 'Transaksi Kas & Bank')

@section('page_content')
<div class="container-fluid">
    <!-- Action Buttons Row -->
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="{{ route('cash-bank.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Transaksi
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
                    <label for="filter-account" class="mr-2">Akun</label>
                    <select id="filter-account" name="account_id" class="form-control form-control-sm">
                        <option value="">Semua Akun</option>
                        @foreach(\App\Models\AccountancyChartOfAccount::where('type', 'asset')->where('category', 'current_asset')->get() as $acc)
                            <option value="{{ $acc->id }}" {{ request('account_id') == $acc->id ? 'selected' : '' }}>
                                {{ $acc->code }} - {{ $acc->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mr-3">
                    <label for="filter-date-from" class="mr-2">Dari</label>
                    <input type="date" id="filter-date-from" name="date_from" 
                           class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>
                <div class="form-group mr-3">
                    <label for="filter-date-to" class="mr-2">s/d</label>
                    <input type="date" id="filter-date-to" name="date_to" 
                           class="form-control form-control-sm" value="{{ request('date_to') }}">
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
            <h3 class="card-title">Daftar Transaksi Kas & Bank</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="cash-bank-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="10%">Tanggal</th>
                            <th width="15%">Akun</th>
                            <th width="8%">Tipe</th>
                            <th width="20%">Deskripsi</th>
                            <th width="8%">Status</th>
                            <th width="10%">Nominal</th>
                            <th width="10%">Bukti</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom_js')
<script>
$(document).ready(function() {
    // Initialize DataTable
    let table = $('#cash-bank-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('cash-bank.index') }}',
            data: function(d) {
                d.account_id = $('#filter-account').val();
                d.date_from = $('#filter-date-from').val();
                d.date_to = $('#filter-date-to').val();
            }
        },
        columns: [
            {data: 'date', name: 'date'},
            {data: 'account_name', name: 'account_name'},
            {data: 'type_badge', name: 'type', orderable: true, searchable: true},
            {data: 'description', name: 'description'},
            {data: 'status_badge', name: 'status', orderable: true, searchable: true},
            {data: 'amount_formatted', name: 'amount', orderable: true, searchable: false},
            {data: 'attachment', name: 'attachment', orderable: false, searchable: false},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        language: {
            url: '{{ asset('js/datatables-id.json') }}'
        }
    });

    // Filter form submission
    $('#filter-form').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
    
    // Reset filter
    $('#reset-filter').on('click', function() {
        $('#filter-account').val('');
        $('#filter-date-from').val('');
        $('#filter-date-to').val('');
        table.ajax.reload();
    });

    // Export Excel
    $('#export-excel').on('click', function() {
        let accountId = $('#filter-account').val();
        let dateFrom = $('#filter-date-from').val();
        let dateTo = $('#filter-date-to').val();
        
        let url = '{{ route('cash-bank.export') }}' + '?account_id=' + encodeURIComponent(accountId) + '&date_from=' + encodeURIComponent(dateFrom) + '&date_to=' + encodeURIComponent(dateTo);
        window.location.href = url;
    });

    // Delete confirmation
    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id');
        
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus transaksi ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url('cash-bank') }}/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat menghapus data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: message
                        });
                    }
                });
            }
        });
    });

    // Success/Error messages
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