@php
    $showUrl = route('journal-entries.show', $entry->id);
    $editUrl = route('journal-entries.edit', $entry->id);
@endphp

<div class="btn-group" role="group">
    <a href="{{ $showUrl }}" class="btn btn-sm btn-info" title="Detail">
        <i class="fas fa-eye"></i>
    </a>
    @if($entry->isDraft())
        <a href="{{ $editUrl }}" class="btn btn-sm btn-primary" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
        <button type="button" class="btn btn-sm btn-danger btn-delete" 
                data-id="{{ $entry->id }}" title="Hapus">
            <i class="fas fa-trash"></i>
        </button>
    @endif
</div> 