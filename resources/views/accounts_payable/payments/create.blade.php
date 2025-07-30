@extends('layouts.base')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Pembayaran Hutang</h1>
    <div class="card mb-3">
        <div class="card-body">
            <b>Supplier:</b> {{ optional($payable->supplier)->name }}<br>
            <b>Nominal Sisa:</b> {{ number_format($payable->amount,0,',','.') }}<br>
            <b>Status:</b> {{ $payable->status == 'paid' ? 'Lunas' : 'Belum Lunas' }}
        </div>
    </div>
    <form method="POST" action="{{ route('accounts-payable.payments.store', $payable->id) }}" class="row g-3">
        @csrf
        <div class="col-md-3">
            <label>Tanggal</label>
            <input type="date" name="date" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label>Nominal</label>
            <input type="number" name="amount" class="form-control" min="1" max="{{ $payable->amount }}" required>
        </div>
        <div class="col-md-6">
            <label>Deskripsi</label>
            <input type="text" name="description" class="form-control">
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary">Simpan Pembayaran</button>
            <a href="{{ route('accounts-payable.show', $payable->id) }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection 