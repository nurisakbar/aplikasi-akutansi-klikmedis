@extends('layouts.base')

@section('page_title', 'Detail Supplier')

@section('page_content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Detail Supplier</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-md-3">Nama</dt>
                <dd class="col-md-9">{{ $supplier->name }}</dd>
                <dt class="col-md-3">Email</dt>
                <dd class="col-md-9">{{ $supplier->email }}</dd>
            </dl>
        </div>
        <div class="card-footer">
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection 