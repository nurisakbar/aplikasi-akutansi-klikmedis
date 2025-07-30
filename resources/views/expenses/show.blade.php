@extends('layouts.base')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Detail Beban</h1>
    @if(!$expense)
        <div class="alert alert-danger">Data beban tidak ditemukan.</div>
        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Kembali</a>
    @else
    <div class="card mb-3">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4"><b>Jenis Beban:</b> {{ $expense->type }}</div>
                <div class="col-md-4"><b>No. Dokumen:</b> {{ $expense->document_number }}</div>
                <div class="col-md-4"><b>Tanggal:</b> {{ $expense->date }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><b>Status:</b> {{ $expense->status == 'paid' ? 'Lunas' : 'Belum Lunas' }}</div>
                <div class="col-md-4"><b>Nominal:</b> {{ number_format($expense->amount,0,',','.') }}</div>
                <div class="col-md-4"><b>Deskripsi:</b> {{ $expense->description }}</div>
            </div>
        </div>
    </div>
    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Kembali</a>
    @endif
</div>
@endsection 