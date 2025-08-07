@php
    $editUrl = route('customers.edit', $customer->id);
    $showUrl = route('customers.show', $customer->id);
@endphp

<div class="btn-group" role="group">
    <a href="{{ $showUrl }}" class="btn btn-sm btn-info" title="Detail">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ $editUrl }}" class="btn btn-sm btn-primary" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <button type="button" class="btn btn-sm btn-danger btn-delete" 
            data-id="{{ $customer->id }}" 
            data-name="{{ $customer->name }}" 
            title="Hapus">
        <i class="fas fa-trash"></i>
    </button>
</div> 