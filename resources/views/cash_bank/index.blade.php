@extends('layouts.base')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Transaksi Kas & Bank</h1>
    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-3">
            <label>Akun</label>
            <select name="account_id" class="form-control">
                <option value="">- Semua Akun -</option>
                @foreach(\App\Models\ChartOfAccount::all() as $acc)
                    <option value="{{ $acc->id }}" {{ (request('account_id') == $acc->id) ? 'selected' : '' }}>{{ $acc->code }} - {{ $acc->name }}</option>
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
            <label>Tipe</label>
            <select name="type" class="form-control">
                <option value="">- Semua -</option>
                <option value="in" {{ request('type')=='in'?'selected':'' }}>Masuk</option>
                <option value="out" {{ request('type')=='out'?'selected':'' }}>Keluar</option>
                <option value="transfer" {{ request('type')=='transfer'?'selected':'' }}>Transfer</option>
            </select>
        </div>
        <div class="col-md-2">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="">- Semua -</option>
                <option value="draft" {{ request('status')=='draft'?'selected':'' }}>Draft</option>
                <option value="posted" {{ request('status')=='posted'?'selected':'' }}>Posted</option>
            </select>
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>
    <div class="mb-3">
        <a href="{{ route('cash-bank.export', request()->all()) }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>
    @if($saldo !== null)
    <div class="alert alert-info">Saldo Akhir: <b>{{ number_format($saldo,0,',','.') }}</b></div>
    @endif
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Akun</th>
                    <th>Tipe</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                <tr>
                    <td>{{ $row->date }}</td>
                    <td>{{ optional($row->account)->code }} - {{ optional($row->account)->name }}</td>
                    <td>{{ $row->type }}</td>
                    <td>{{ $row->description }}</td>
                    <td>{{ $row->status }}</td>
                    <td class="text-end">{{ number_format($row->amount,0,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 