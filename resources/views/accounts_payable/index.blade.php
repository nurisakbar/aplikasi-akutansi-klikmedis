@extends('layouts.base')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Hutang Usaha</h1>
    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-3">
            <label>Supplier</label>
            <select name="supplier_id" class="form-control">
                <option value="">- Semua Supplier -</option>
                @foreach(\App\Models\Supplier::all() as $s)
                    <option value="{{ $s->id }}" {{ (request('supplier_id') == $s->id) ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label>Dari Tanggal</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
        </div>
        <div class="col-md-2">
            <label>Sampai Tanggal</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
        </div>
        <div class="col-md-2">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="">- Semua -</option>
                <option value="unpaid" {{ request('status')=='unpaid'?'selected':'' }}>Belum Lunas</option>
                <option value="paid" {{ request('status')=='paid'?'selected':'' }}>Lunas</option>
            </select>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <a href="{{ route('accounts-payable.create') }}" class="btn btn-success w-100"><i class="fas fa-plus"></i> Tambah Hutang</a>
        </div>
    </form>
    <div class="mb-3">
        <a href="{{ route('accounts-payable.export', request()->all()) }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="alert alert-info">Total Hutang: <b>{{ number_format($saldo,0,',','.') }}</b></div>
        </div>
        <div class="col-md-8">
            <div class="alert alert-secondary">
                <b>Aging:</b>
                Current: {{ number_format($aging['current'] ?? 0,0,',','.') }} |
                1-30: {{ number_format($aging['overdue_1_30'] ?? 0,0,',','.') }} |
                31-60: {{ number_format($aging['overdue_31_60'] ?? 0,0,',','.') }} |
                61-90: {{ number_format($aging['overdue_61_90'] ?? 0,0,',','.') }} |
                91+: {{ number_format($aging['overdue_91_plus'] ?? 0,0,',','.') }}
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jatuh Tempo</th>
                    <th>Supplier</th>
                    <th>Status</th>
                    <th>Nominal</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                <tr>
                    <td>{{ $row->date }}</td>
                    <td>{{ $row->due_date }}</td>
                    <td>{{ optional($row->supplier)->name }}</td>
                    <td>{{ $row->status == 'paid' ? 'Lunas' : 'Belum Lunas' }}</td>
                    <td class="text-end">{{ number_format($row->amount,0,',','.') }}</td>
                    <td>{{ $row->description }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 