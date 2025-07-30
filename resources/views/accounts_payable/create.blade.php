@extends('layouts.base')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Tambah Hutang Usaha</h1>
    <form method="POST" action="{{ route('accounts-payable.store') }}" class="row g-3">
        @csrf
        <div class="col-md-4">
            <label>Supplier</label>
            <select name="supplier_id" class="form-control" required>
                <option value="">- Pilih Supplier -</option>
                @foreach(\App\Models\Supplier::all() as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Tanggal</label>
            <input type="date" name="date" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label>Jatuh Tempo</label>
            <input type="date" name="due_date" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="unpaid">Belum Lunas</option>
                <option value="paid">Lunas</option>
            </select>
        </div>
        <div class="col-md-2">
            <label>Nominal</label>
            <input type="number" name="amount" class="form-control" min="1" required>
        </div>
        <div class="col-md-12">
            <label>Deskripsi</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('accounts-payable.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection 