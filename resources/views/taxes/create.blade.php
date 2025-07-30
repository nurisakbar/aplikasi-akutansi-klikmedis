@extends('layouts.base')

@section('page_title', 'Tambah Data Pajak')

@section('page_content')
<div class="container-fluid">
    <form method="POST" action="{{ route('taxes.store') }}">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Input Data Pajak</h3>
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="type">Jenis Pajak</label>
                    <input type="text" name="type" id="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type') }}" required>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="document_number">No. Dokumen</label>
                    <input type="text" name="document_number" id="document_number" class="form-control @error('document_number') is-invalid @enderror" value="{{ old('document_number') }}">
                    @error('document_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="date">Tanggal</label>
                    <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}" required>
                    @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="unpaid" {{ old('status')=='unpaid'?'selected':'' }}>Belum Lunas</option>
                        <option value="paid" {{ old('status')=='paid'?'selected':'' }}>Lunas</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="amount">Nominal</label>
                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" min="1" value="{{ old('amount') }}" required>
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-success">Simpan</button>
                <a href="{{ route('taxes.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection 