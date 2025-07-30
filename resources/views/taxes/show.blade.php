@extends('layouts.base')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Detail Pajak</h1>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4"><b>Jenis Pajak:</b> {{ $tax->type }}</div>
                <div class="col-md-4"><b>No. Dokumen:</b> {{ $tax->document_number }}</div>
                <div class="col-md-4"><b>Tanggal:</b> {{ $tax->date }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><b>Status:</b> {{ $tax->status == 'paid' ? 'Lunas' : 'Belum Lunas' }}</div>
                <div class="col-md-4"><b>Nominal:</b> {{ number_format($tax->amount,0,',','.') }}</div>
                <div class="col-md-4"><b>Deskripsi:</b> {{ $tax->description }}</div>
            </div>
        </div>
    </div>
    <a href="{{ route('taxes.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection 