@extends('layouts.base')

@section('page_title', 'General Ledger (Buku Besar)')

@section('page_content')
<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header">Filter Buku Besar</div>
        <div class="card-body">
            <form method="GET" action="{{ route('general-ledger.index') }}" class="form-inline">
                <div class="form-group mr-2">
                    <label for="account_id" class="mr-2">Akun</label>
                    <select name="account_id" id="account_id" class="form-control" required>
                        <option value="">Pilih Akun</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->code }} - {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
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
                    <a href="{{ route('general-ledger.export', request()->all()) }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                @endif
            </form>
        </div>
    </div>
    @if($data)
        <div class="card">
            <div class="card-header">Buku Besar: {{ $data['account']->code }} - {{ $data['account']->name }}</div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Saldo Awal:</strong> {{ number_format($data['opening'], 2) }}<br>
                    <strong>Mutasi Debit:</strong> {{ number_format($data['mutasi_debit'], 2) }}<br>
                    <strong>Mutasi Kredit:</strong> {{ number_format($data['mutasi_kredit'], 2) }}<br>
                    <strong>Saldo Akhir:</strong> {{ number_format($data['closing'], 2) }}
                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>No Jurnal</th>
                            <th>Referensi</th>
                            <th>Deskripsi</th>
                            <th>Debit</th>
                            <th>Kredit</th>
                            <th>Saldo Berjalan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $saldo = $data['opening']; @endphp
                        @foreach($data['lines'] as $line)
                            @php $saldo += $line->debit - $line->credit; @endphp
                            <tr>
                                <td>{{ $line->journalEntry->date }}</td>
                                <td>{{ $line->journalEntry->journal_number }}</td>
                                <td>{{ $line->journalEntry->reference }}</td>
                                <td>{{ $line->description }}</td>
                                <td>{{ number_format($line->debit, 2) }}</td>
                                <td>{{ number_format($line->credit, 2) }}</td>
                                <td>{{ number_format($saldo, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection 