@extends('layouts.base')

@section('page_title', 'Edit Data Beban')

@push('custom_css')
<link rel="stylesheet" href="/vendor/select2.min.css">
<style>
.select2-container .select2-selection--single {
    height: 38px !important;
    padding: 6px 12px;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    font-size: 1rem;
    background-color: #fff;
    box-shadow: none;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 24px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
    right: 10px;
}
.select2-container--default .select2-selection--single:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}
</style>
@endpush

@section('page_content')
<div class="container-fluid">
    <form method="POST" action="{{ route('expenses.update', $expense->id) }}">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Data Beban</h3>
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="type">Jenis Beban</label>
                    <select name="type" id="type" class="form-control select2 @error('type') is-invalid @enderror" required></select>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="document_number">No. Dokumen</label>
                    <input type="text" name="document_number" id="document_number" class="form-control @error('document_number') is-invalid @enderror" value="{{ old('document_number', $expense->document_number) }}">
                    @error('document_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="date">Tanggal</label>
                    <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $expense->date) }}" required>
                    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="unpaid" {{ old('status', $expense->status)=='unpaid'?'selected':'' }}>Belum Lunas</option>
                        <option value="paid" {{ old('status', $expense->status)=='paid'?'selected':'' }}>Lunas</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="amount">Nominal</label>
                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" min="1" value="{{ old('amount', $expense->amount) }}" required>
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $expense->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-success">Update</button>
                <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection

@push('custom_js')
<script src="/vendor/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#type').select2({
        placeholder: 'Pilih atau ketik jenis beban',
        tags: true,
        ajax: {
            url: '{{ route('expenses.types') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.map(function(item) {
                        return { id: item, text: item };
                    })
                };
            },
            cache: true
        },
        minimumInputLength: 0,
        allowClear: true,
        width: '100%'
    });
    // Set value dari data lama
    var currentType = @json(old('type', $expense->type));
    if (currentType) {
        var option = new Option(currentType, currentType, true, true);
        $('#type').append(option).trigger('change');
    }
});
</script>
@endpush 