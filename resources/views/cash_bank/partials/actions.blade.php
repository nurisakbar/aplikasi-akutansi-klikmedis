@php
    $editUrl = route('cash-bank.edit', $transaction->id);
@endphp

<div class="btn-group" role="group">
    @if($transaction->isDraft())
        <a href="{{ $editUrl }}" class="btn btn-sm btn-primary" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
        <button type="button" class="btn btn-sm btn-danger btn-delete" 
                data-id="{{ $transaction->id }}" title="Hapus">
            <i class="fas fa-trash"></i>
        </button>
    @endif
</div> 