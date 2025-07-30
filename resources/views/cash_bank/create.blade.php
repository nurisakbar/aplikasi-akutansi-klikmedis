@extends('layouts.base')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Tambah Transaksi Kas & Bank</h1>
    <form method="POST" action="{{ route('cash-bank.store') }}" enctype="multipart/form-data" class="row g-3">
        @csrf
        <div class="col-md-4">
            <label>Akun</label>
            <select name="account_id" class="form-control" required>
                <option value="">- Pilih Akun -</option>
                @foreach(\App\Models\ChartOfAccount::all() as $acc)
                    <option value="{{ $acc->id }}">{{ $acc->code }} - {{ $acc->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Tanggal</label>
            <input type="date" name="date" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label>Tipe</label>
            <select name="type" class="form-control" required>
                <option value="in">Masuk</option>
                <option value="out">Keluar</option>
                <option value="transfer">Transfer</option>
            </select>
        </div>
        <div class="col-md-2">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="draft">Draft</option>
                <option value="posted">Posted</option>
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
        <div class="col-md-6">
            <label>Bukti (jpg, png, pdf, max 2MB)</label>
            <input type="file" name="bukti" class="form-control">
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('cash-bank.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection 