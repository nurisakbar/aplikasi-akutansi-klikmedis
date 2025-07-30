<div class="btn-group">
    <a href="{{ route('fixed-assets.show', $row->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
    <a href="{{ route('fixed-assets.edit', $row->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
    <button onclick="deleteAsset('{{ $row->id }}')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
</div> 