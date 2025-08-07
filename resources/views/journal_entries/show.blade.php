@extends('layouts.base')

@section('page_title', 'Detail Jurnal Umum')

@section('page_content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detail Jurnal</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th width="20%">Tanggal</th>
                    <td>{{ $journalEntry->date }}</td>
                </tr>
                <tr>
                    <th>Referensi</th>
                    <td>{{ $journalEntry->reference }}</td>
                </tr>
                <tr>
                    <th>Deskripsi</th>
                    <td>{{ $journalEntry->description }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge badge-{{ $journalEntry->status_badge_class }}">
                            {{ $journalEntry->formatted_status }}
                        </span>
                        @if($journalEntry->isDraft())
                            <form action="{{ route('journal-entries.post', $journalEntry->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success ml-2" onclick="return confirm('Posting jurnal ini?')">
                                    <i class="fas fa-check"></i> Posting
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Lampiran</th>
                    <td>
                        @if($journalEntry->attachment)
                            <a href="{{ asset('storage/journal_attachments/' . $journalEntry->attachment) }}" target="_blank">Download Lampiran</a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            </table>
            @if($journalEntry->history && is_array($journalEntry->history))
                <hr>
                <h5>Riwayat Perubahan</h5>
                <ul class="list-group mb-3">
                    @foreach(array_reverse($journalEntry->history) as $log)
                        <li class="list-group-item">
                            <strong>{{ ucfirst($log['action']) }}</strong>
                            oleh <em>{{ $log['user'] }}</em>
                            pada <span class="text-muted">{{ $log['at'] }}</span>
                            @if(!empty($log['changes']))
                                <br><small>Perubahan: {{ json_encode($log['changes']) }}</small>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
            <hr>
            <h5>Baris Jurnal</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Akun</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($journalEntry->accountancyJournalEntryLines as $line)
                        <tr>
                            <td>{{ $line->accountancyChartOfAccount->code }} - {{ $line->accountancyChartOfAccount->name }}</td>
                            <td>{{ number_format($line->debit, 2) }}</td>
                            <td>{{ number_format($line->credit, 2) }}</td>
                            <td>{{ $line->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <th>Total</th>
                        <th>{{ number_format($journalEntry->accountancyJournalEntryLines->sum('debit'), 2) }}</th>
                        <th>{{ number_format($journalEntry->accountancyJournalEntryLines->sum('credit'), 2) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            <div class="mt-3">
                <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                @if($journalEntry->isDraft())
                    <a href="{{ route('journal-entries.edit', $journalEntry->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 