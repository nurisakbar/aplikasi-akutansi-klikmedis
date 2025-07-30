@extends('layouts.base')

@section('page_title', 'Tambah Akun')

@section('page_content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tambah Akun Baru</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('chart-of-accounts.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode Akun <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                   value="{{ old('code') }}" required maxlength="20">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Nama Akun <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" required maxlength="100">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Tipe Akun <span class="text-danger">*</span></label>
                            <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                                <option value="">Pilih Tipe</option>
                                <option value="asset" {{ old('type') == 'asset' ? 'selected' : '' }}>Asset</option>
                                <option value="liability" {{ old('type') == 'liability' ? 'selected' : '' }}>Liability</option>
                                <option value="equity" {{ old('type') == 'equity' ? 'selected' : '' }}>Equity</option>
                                <option value="revenue" {{ old('type') == 'revenue' ? 'selected' : '' }}>Revenue</option>
                                <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kategori <span class="text-danger">*</span></label>
                            <select name="category" class="form-control @error('category') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                <optgroup label="Asset">
                                    <option value="current_asset" {{ old('category') == 'current_asset' ? 'selected' : '' }}>Current Asset</option>
                                    <option value="fixed_asset" {{ old('category') == 'fixed_asset' ? 'selected' : '' }}>Fixed Asset</option>
                                    <option value="other_asset" {{ old('category') == 'other_asset' ? 'selected' : '' }}>Other Asset</option>
                                </optgroup>
                                <optgroup label="Liability">
                                    <option value="current_liability" {{ old('category') == 'current_liability' ? 'selected' : '' }}>Current Liability</option>
                                    <option value="long_term_liability" {{ old('category') == 'long_term_liability' ? 'selected' : '' }}>Long Term Liability</option>
                                </optgroup>
                                <optgroup label="Equity">
                                    <option value="equity" {{ old('category') == 'equity' ? 'selected' : '' }}>Equity</option>
                                </optgroup>
                                <optgroup label="Revenue">
                                    <option value="operating_revenue" {{ old('category') == 'operating_revenue' ? 'selected' : '' }}>Operating Revenue</option>
                                    <option value="other_revenue" {{ old('category') == 'other_revenue' ? 'selected' : '' }}>Other Revenue</option>
                                </optgroup>
                                <optgroup label="Expense">
                                    <option value="operating_expense" {{ old('category') == 'operating_expense' ? 'selected' : '' }}>Operating Expense</option>
                                    <option value="other_expense" {{ old('category') == 'other_expense' ? 'selected' : '' }}>Other Expense</option>
                                </optgroup>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Parent Akun</label>
                            <select name="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                                <option value="">Tidak Ada Parent (Root)</option>
                                @foreach($parentAccounts as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->code }} - {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="is_active" class="form-control">
                                <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                    <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 