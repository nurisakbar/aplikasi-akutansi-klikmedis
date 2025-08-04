@extends('layouts.base')

@section('custom_css')
<style>
    .table-container {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        overflow: hidden;
    }

    .table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .table-title {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .table-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .table-body {
        padding: 1.5rem;
    }

    .filters-section {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 0.375rem;
        margin-bottom: 1.5rem;
    }

    .filter-group {
        display: flex;
        gap: 1rem;
        align-items: end;
        flex-wrap: wrap;
    }

    .filter-item {
        flex: 1;
        min-width: 200px;
    }

    .filter-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #495057;
    }

    .btn-action {
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        margin: 0.125rem;
    }

    @media (max-width: 768px) {
        .table-header {
            flex-direction: column;
            align-items: stretch;
        }

        .table-actions {
            justify-content: center;
        }

        .filter-group {
            flex-direction: column;
        }

        .filter-item {
            min-width: auto;
        }
    }
</style>
@endsection

@section('page_content')
<div class="table-container">
    <div class="table-header">
        <div>
            <h3 class="table-title">
                <i class="fas fa-list me-2"></i>
                @yield('table_title', 'Data')
            </h3>
            <p class="mb-0 mt-1 opacity-75">
                @yield('table_subtitle', 'Kelola data dengan mudah')
            </p>
        </div>
        <div class="table-actions">
            @yield('table_actions')
        </div>
    </div>

    <div class="table-body">
        @yield('filters')

        <div class="table-responsive">
            @yield('table_content')
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
$(document).ready(function() {
    // Initialize DataTable with custom configuration
    const table = $('.datatable').DataTable({
        language: {
            url: '{{ asset('js/datatables-id.json') }}'
        },
        pageLength: 25,
        responsive: true,
        order: [[0, 'desc']],
        columnDefs: [
            {
                targets: -1,
                orderable: false,
                searchable: false
            }
        ]
    });

    // Global delete function
    window.deleteRecord = function(url, message = 'Apakah Anda yakin ingin menghapus data ini?') {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'swal2-popup',
                title: 'swal2-title',
                content: 'swal2-content'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        if (response.success) {
                            Toast.fire({
                                icon: 'success',
                                title: response.message || 'Data berhasil dihapus'
                            });
                            table.ajax.reload();
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: response.message || 'Gagal menghapus data'
                            });
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Terjadi kesalahan saat menghapus data'
                        });
                    }
                });
            }
        });
    };

    // Export functionality
    $('.btn-export').on('click', function() {
        const exportType = $(this).data('type');
        const currentUrl = window.location.href;
        const separator = currentUrl.includes('?') ? '&' : '?';
        const exportUrl = currentUrl + separator + 'export=' + exportType;

        window.location.href = exportUrl;
    });

    // Filter functionality
    $('.filter-input').on('change', function() {
        const filterValue = $(this).val();
        const filterColumn = $(this).data('column');

        if (filterValue) {
            table.column(filterColumn).search(filterValue).draw();
        } else {
            table.column(filterColumn).search('').draw();
        }
    });

    // Date range filter
    $('.date-range-filter').on('change', function() {
        const startDate = $('#filter_date_from').val();
        const endDate = $('#filter_date_to').val();

        if (startDate && endDate) {
            // Custom filtering logic for date range
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const date = new Date(data[$(this).data('date-column')]);
                const start = new Date(startDate);
                const end = new Date(endDate);

                return date >= start && date <= end;
            });
            table.draw();
        } else {
            // Remove custom filter
            $.fn.dataTable.ext.search.pop();
            table.draw();
        }
    });
});
</script>
@endsection
