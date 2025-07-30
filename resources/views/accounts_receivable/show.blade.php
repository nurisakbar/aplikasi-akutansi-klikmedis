@extends('layouts.base')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Detail Piutang Usaha</h1>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4"><b>Customer:</b> {{ optional($receivable->customer)->name }}</div>
                <div class="col-md-4"><b>Tanggal:</b> {{ $receivable->date }}</div>
                <div class="col-md-4"><b>Jatuh Tempo:</b> {{ $receivable->due_date }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><b>Status:</b> {{ $receivable->status == 'paid' ? 'Lunas' : 'Belum Lunas' }}</div>
                <div class="col-md-4"><b>Nominal:</b> {{ number_format($receivable->amount,0,',','.') }}</div>
                <div class="col-md-4"><b>Deskripsi:</b> {{ $receivable->description }}</div>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <a href="{{ route('accounts-receivable.payments.create', $receivable->id) }}" class="btn btn-success">
            <i class="fas fa-money-bill-wave"></i> Tambah Pembayaran
        </a>
        <a href="{{ route('accounts-receivable.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
    <div class="card">
        <div class="card-header"><b>Histori Pembayaran</b></div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nominal</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receivable->payments as $pay)
                    <tr>
                        <td>{{ $pay->date }}</td>
                        <td class="text-end">{{ number_format($pay->amount,0,',','.') }}</td>
                        <td>{{ $pay->description }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center">Belum ada pembayaran</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 