<div class="btn-group">
    <a href="{{ route('customers.show', $row->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
    <a href="{{ route('customers.edit', $row->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
    <button onclick="deleteCustomer('{{ $row->id }}')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
</div> 