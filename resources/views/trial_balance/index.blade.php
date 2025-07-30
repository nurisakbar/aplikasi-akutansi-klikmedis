@extends('layouts.base')

@section('page_title', 'Trial Balance (Neraca Saldo)')

@section('page_content')
<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">Filter Neraca Saldo</div>
        <div class="card-body">
            <form method="GET" action="{{ route('trial-balance.index') }}" class="form-inline">
                <div class="form-group mr-2">
                    <label for="date_from" class="mr-2">Dari</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="form-group mr-2">
                    <label for="date_to" class="mr-2">s/d</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="form-group mr-2">
                    <label for="status" class="mr-2">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="posted" {{ request('status', 'posted') == 'posted' ? 'selected' : '' }}>Posted</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mr-2">Tampilkan</button>
                @if($data)
                    <a href="{{ route('trial-balance.export', request()->all()) }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                @endif
            </form>
        </div>
    </div>
    @if($data)
        <div class="card">
            <div class="card-header">Neraca Saldo</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Saldo Awal</th>
                            <th>Mutasi Debit</th>
                            <th>Mutasi Kredit</th>
                            <th>Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['rows'] as $row)
                            <tr>
                                <td>{{ $row->account->code }}</td>
                                <td>{{ $row->account->name }}</td>
                                <td>{{ number_format($row->opening, 2) }}</td>
                                <td>{{ number_format($row->mutasi_debit, 2) }}</td>
                                <td>{{ number_format($row->mutasi_kredit, 2) }}</td>
                                <td>{{ number_format($row->closing, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Total</th>
                            <th>{{ number_format($data['total_debit'], 2) }}</th>
                            <th>{{ number_format($data['total_credit'], 2) }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection 