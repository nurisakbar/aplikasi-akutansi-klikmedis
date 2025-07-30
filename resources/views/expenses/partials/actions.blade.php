<a href="{{ route('expenses.show', $row->id) }}" class="btn btn-info btn-sm" title="Lihat"><i class="fas fa-eye"></i></a>
<a href="{{ route('expenses.edit', $row->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
<form action="{{ route('expenses.destroy', $row->id) }}" method="POST" style="display:inline-block;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm btn-delete" title="Hapus"><i class="fas fa-trash"></i></button>
</form> 