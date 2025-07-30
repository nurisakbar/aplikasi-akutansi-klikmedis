@extends('layouts.base')

@section('page_title', 'Profit and Loss Statement (Laporan Laba Rugi)')

@section('page_content')
<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">Filter Laporan Laba Rugi</div>
        <div class="card-body">
            <form method="GET" action="{{ route('profit-loss.index') }}" class="form-inline">
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
                    <a href="{{ route('profit-loss.export', request()->all()) }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                @endif
            </form>
        </div>
    </div>
    @if($data)
        <div class="card">
            <div class="card-header">Laporan Laba Rugi</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Pendapatan</h5>
                        <table class="table table-bordered table-sm">
                            <tbody>
                                @foreach($data['rows']->firstWhere('type', 'revenue')->accounts ?? [] as $row)
                                    <tr>
                                        <td>{{ $row->account->code }}</td>
                                        <td>{{ $row->account->name }}</td>
                                        <td class="text-right">{{ number_format($row->saldo, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-weight-bold">
                                    <td colspan="2">Total Pendapatan</td>
                                    <td class="text-right">{{ number_format($data['total_revenue'], 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Beban</h5>
                        <table class="table table-bordered table-sm">
                            <tbody>
                                @foreach($data['rows']->firstWhere('type', 'expense')->accounts ?? [] as $row)
                                    <tr>
                                        <td>{{ $row->account->code }}</td>
                                        <td>{{ $row->account->name }}</td>
                                        <td class="text-right">{{ number_format($row->saldo, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-weight-bold">
                                    <td colspan="2">Total Beban</td>
                                    <td class="text-right">{{ number_format($data['total_expense'], 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-3">
                    <strong>Laba/Rugi Bersih:</strong> <span class="text-{{ $data['profit'] >= 0 ? 'success' : 'danger' }}">{{ number_format($data['profit'], 2) }}</span>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 