@php
    $showUrl = route('chart-of-accounts.show', $account->id);
    $editUrl = route('chart-of-accounts.edit', $account->id);
@endphp

<div class="btn-group" role="group">
    <a href="{{ $showUrl }}" class="btn btn-sm btn-info" title="View">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ $editUrl }}" class="btn btn-sm btn-primary" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <button type="button" class="btn btn-sm btn-danger" 
            onclick="deleteRecord('{{ $account->id }}')" title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</div>

@push('scripts')
<script>
function deleteRecord(id) {
    if (confirm('Are you sure you want to delete this record?')) {
        $.ajax({
            url: `{{ route('chart-of-accounts.index') }}/${id}`,
            type: 'DELETE',
            data: {
                "_token": "{{ csrf_token() }}"
            },
            success: function(result) {
                // Refresh the DataTable
                $('#accounts-table').DataTable().ajax.reload();
                // Show success message
                alert('Record deleted successfully');
            },
            error: function(xhr) {
                // Show error message
                alert('Error deleting record');
            }
        });
    }
}
</script>
@endpush 