@extends('layouts.base')

@section('page_title', 'Tambah Jurnal Umum')

@section('css')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
@endsection

@section('page_content')
<div class="container-fluid">
    <form action="{{ route('journal-entries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Input Jurnal Umum</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="date">Tanggal</label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                </div>
                <div class="form-group">
                    <label for="reference">Referensi</label>
                    <input type="text" name="reference" id="reference" class="form-control" value="{{ old('reference') }}">
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="attachment">Lampiran (Attachment)</label>
                    <div id="dropzone-attachment" class="dropzone"></div>
                    <input type="hidden" name="attachment" id="attachment-hidden">
                </div>
                <hr>
                <h5>Baris Jurnal</h5>
                <table class="table table-bordered" id="lines-table">
                    <thead>
                        <tr>
                            <th>Akun</th>
                            <th>Debit</th>
                            <th>Kredit</th>
                            <th>Deskripsi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(old('lines'))
                            @foreach(old('lines') as $i => $line)
                                <tr>
                                    <td>
                                        <select name="lines[{{ $i }}][chart_of_account_id]" class="form-control" required>
                                            <option value="">Pilih Akun</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}" {{ $line['chart_of_account_id'] == $account->id ? 'selected' : '' }}>{{ $account->code }} - {{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="lines[{{ $i }}][debit]" class="form-control" step="0.01" value="{{ $line['debit'] }}" min="0" required></td>
                                    <td><input type="number" name="lines[{{ $i }}][credit]" class="form-control" step="0.01" value="{{ $line['credit'] }}" min="0" required></td>
                                    <td><input type="text" name="lines[{{ $i }}][description]" class="form-control" value="{{ $line['description'] }}"></td>
                                    <td><button type="button" class="btn btn-danger btn-sm btn-remove-line">&times;</button></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>
                                    <select name="lines[0][chart_of_account_id]" class="form-control" required>
                                        <option value="">Pilih Akun</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="lines[0][debit]" class="form-control" step="0.01" min="0" required></td>
                                <td><input type="number" name="lines[0][credit]" class="form-control" step="0.01" min="0" required></td>
                                <td><input type="text" name="lines[0][description]" class="form-control"></td>
                                <td><button type="button" class="btn btn-danger btn-sm btn-remove-line">&times;</button></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <button type="button" class="btn btn-secondary btn-sm" id="add-line">Tambah Baris</button>
                <div class="mt-2 text-danger">
                    @error('lines') {{ $message }} @enderror
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection

@push('custom_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>
Dropzone.autoDiscover = false;
let uploadedFile = null;
let dz = new Dropzone('#dropzone-attachment', {
    url: '{{ route('journal-entries.upload-attachment') }}',
    paramName: 'file',
    maxFiles: 1,
    maxFilesize: 2, // MB
    acceptedFiles: '.pdf,.jpg,.jpeg,.png',
    addRemoveLinks: true,
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    init: function() {
        this.on('success', function(file, response) {
            uploadedFile = response.filename;
            $('#attachment-hidden').val(uploadedFile);
        });
        this.on('removedfile', function(file) {
            uploadedFile = null;
            $('#attachment-hidden').val('');
        });
    }
});

let lineIndex = {{ old('lines') ? count(old('lines')) : 1 }};
$('#add-line').on('click', function() {
    let row = `<tr>
        <td>
            <select name="lines[${lineIndex}][chart_of_account_id]" class="form-control" required>
                <option value="">Pilih Akun</option>
                @foreach($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                @endforeach
            </select>
        </td>
        <td><input type="number" name="lines[${lineIndex}][debit]" class="form-control" step="0.01" min="0" required></td>
        <td><input type="number" name="lines[${lineIndex}][credit]" class="form-control" step="0.01" min="0" required></td>
        <td><input type="text" name="lines[${lineIndex}][description]" class="form-control"></td>
        <td><button type="button" class="btn btn-danger btn-sm btn-remove-line">&times;</button></td>
    </tr>`;
    $('#lines-table tbody').append(row);
    lineIndex++;
});

$(document).on('click', '.btn-remove-line', function() {
    $(this).closest('tr').remove();
});
</script>
@endpush 