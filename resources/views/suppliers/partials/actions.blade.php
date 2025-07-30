<div class="btn-group">
    <a href="{{ route('suppliers.show', $row->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
    <a href="{{ route('suppliers.edit', $row->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
    <button onclick="deleteSupplier('{{ $row->id }}')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
</div> 