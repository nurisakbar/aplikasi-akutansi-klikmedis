@extends('layouts.base')

@section('page_title', 'Detail Customer')

@section('page_content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Detail Customer</h3>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-md-3">Nama</dt>
                <dd class="col-md-9">{{ $customer->name }}</dd>
                <dt class="col-md-3">Email</dt>
                <dd class="col-md-9">{{ $customer->email }}</dd>
            </dl>
        </div>
        <div class="card-footer">
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection 