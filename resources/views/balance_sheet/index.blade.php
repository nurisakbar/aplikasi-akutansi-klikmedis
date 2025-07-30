@extends('layouts.base')

@section('page_title', 'Balance Sheet (Neraca)')

@section('page_content')
<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">Filter Neraca</div>
        <div class="card-body">
            <form method="GET" action="{{ route('balance-sheet.index') }}" class="form-inline">
                <div class="form-group mr-2">
                    <label for="date_to" class="mr-2">Sampai Tanggal</label>
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
                    <a href="{{ route('balance-sheet.export', request()->all()) }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                @endif
            </form>
        </div>
    </div>
    @if($data)
        <div class="card">
            <div class="card-header">Neraca</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h5>Aset</h5>
                        <table class="table table-bordered table-sm">
                            <tbody>
                                @foreach($data['rows']->firstWhere('type', 'asset')->accounts ?? [] as $row)
                                    <tr>
                                        <td>{{ $row->account->code }}</td>
                                        <td>{{ $row->account->name }}</td>
                                        <td class="text-right">{{ number_format($row->saldo, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-weight-bold">
                                    <td colspan="2">Total Aset</td>
                                    <td class="text-right">{{ number_format($data['total_aset'], 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <h5>Liabilitas</h5>
                        <table class="table table-bordered table-sm">
                            <tbody>
                                @foreach($data['rows']->firstWhere('type', 'liability')->accounts ?? [] as $row)
                                    <tr>
                                        <td>{{ $row->account->code }}</td>
                                        <td>{{ $row->account->name }}</td>
                                        <td class="text-right">{{ number_format($row->saldo, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-weight-bold">
                                    <td colspan="2">Total Liabilitas</td>
                                    <td class="text-right">{{ number_format($data['total_liabilitas'], 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <h5>Ekuitas</h5>
                        <table class="table table-bordered table-sm">
                            <tbody>
                                @foreach($data['rows']->firstWhere('type', 'equity')->accounts ?? [] as $row)
                                    <tr>
                                        <td>{{ $row->account->code }}</td>
                                        <td>{{ $row->account->name }}</td>
                                        <td class="text-right">{{ number_format($row->saldo, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-weight-bold">
                                    <td colspan="2">Total Ekuitas</td>
                                    <td class="text-right">{{ number_format($data['total_ekuitas'], 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-3">
                    <strong>Total Aset:</strong> {{ number_format($data['total_aset'], 2) }}<br>
                    <strong>Total Liabilitas + Ekuitas:</strong> {{ number_format($data['total_liabilitas'] + $data['total_ekuitas'], 2) }}<br>
                    <strong>Selisih:</strong> <span class="text-{{ $data['selisih'] == 0 ? 'success' : 'danger' }}">{{ number_format($data['selisih'], 2) }}</span>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 