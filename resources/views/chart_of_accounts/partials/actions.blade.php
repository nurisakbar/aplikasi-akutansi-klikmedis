@php
    $showUrl = route('chart-of-accounts.show', $account->id);
    $editUrl = route('chart-of-accounts.edit', $account->id);
@endphp

<div class="btn-group" role="group">
    <a href="{{ route('chart-of-accounts.show', $account->id) }}" class="btn btn-sm btn-info" title="View">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('chart-of-accounts.edit', $account->id) }}" class="btn btn-sm btn-primary" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
    <button type="button" class="btn btn-sm btn-danger btn-delete" 
            data-id="{{ $account->id }}" title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</div>

 