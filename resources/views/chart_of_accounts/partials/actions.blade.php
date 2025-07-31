@php
    $showUrl = route('chart-of-accounts.show', $account->id);
    $editUrl = route('chart-of-accounts.edit', $account->id);
@endphp

<div class="btn-group" role="group">
    <a href="{{ route('chart-of-accounts.show', $account->id) }}" class="btn btn-sm btn-info" title="View">
        <i class="fas fa-eye"></i>
    </a>
    <button type="button" class="btn btn-sm btn-primary btn-edit" 
            data-toggle="modal" data-target="#accountModal" 
            data-id="{{ $account->id }}" title="Edit">
        <i class="fas fa-edit"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger btn-delete" 
            data-id="{{ $account->id }}" title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</div>

 