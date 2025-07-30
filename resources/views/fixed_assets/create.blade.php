@extends('layouts.base')

@section('page_title', 'Tambah Aset Tetap')

@section('page_content')
<div class="container-fluid">
    <form method="POST" action="{{ route('fixed-assets.store') }}">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Input Aset Tetap</h3>
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="code">Kode</label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" required>
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="name">Nama</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="category">Kategori</label>
                    <input type="text" name="category" id="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category') }}">
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="acquisition_date">Tgl Perolehan</label>
                    <input type="date" name="acquisition_date" id="acquisition_date" class="form-control @error('acquisition_date') is-invalid @enderror" value="{{ old('acquisition_date', date('Y-m-d')) }}" required>
                    @error('acquisition_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="acquisition_value">Nilai Perolehan</label>
                    <input type="number" name="acquisition_value" id="acquisition_value" class="form-control @error('acquisition_value') is-invalid @enderror" min="1" value="{{ old('acquisition_value') }}" required>
                    @error('acquisition_value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="useful_life">Umur (tahun)</label>
                    <input type="number" name="useful_life" id="useful_life" class="form-control @error('useful_life') is-invalid @enderror" min="1" value="{{ old('useful_life') }}" required>
                    @error('useful_life')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="depreciation_method">Metode Penyusutan</label>
                    <select name="depreciation_method" id="depreciation_method" class="form-control @error('depreciation_method') is-invalid @enderror" required>
                        <option value="straight_line" {{ old('depreciation_method')=='straight_line'?'selected':'' }}>Garis Lurus</option>
                        <option value="declining" {{ old('depreciation_method')=='declining'?'selected':'' }}>Saldo Menurun</option>
                    </select>
                    @error('depreciation_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="residual_value">Nilai Residu</label>
                    <input type="number" name="residual_value" id="residual_value" class="form-control @error('residual_value') is-invalid @enderror" min="0" value="{{ old('residual_value') }}" required>
                    @error('residual_value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group mb-3">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-success">Simpan</button>
                <a href="{{ route('fixed-assets.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection 