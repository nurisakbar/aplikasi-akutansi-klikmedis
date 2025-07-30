@extends('layouts.base')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Detail Aset Tetap</h1>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4"><b>Kode:</b> {{ $asset->code }}</div>
                <div class="col-md-4"><b>Nama:</b> {{ $asset->name }}</div>
                <div class="col-md-4"><b>Kategori:</b> {{ $asset->category }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><b>Tgl Perolehan:</b> {{ $asset->acquisition_date }}</div>
                <div class="col-md-4"><b>Nilai Perolehan:</b> {{ number_format($asset->acquisition_value,0,',','.') }}</div>
                <div class="col-md-4"><b>Umur:</b> {{ $asset->useful_life }} tahun</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><b>Metode:</b> {{ $asset->depreciation_method == 'straight_line' ? 'Garis Lurus' : 'Saldo Menurun' }}</div>
                <div class="col-md-4"><b>Nilai Residu:</b> {{ number_format($asset->residual_value,0,',','.') }}</div>
                <div class="col-md-4"><b>Deskripsi:</b> {{ $asset->description }}</div>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header"><b>Schedule Penyusutan</b></div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Tahun</th>
                        <th>Beban Penyusutan</th>
                        <th>Akumulasi</th>
                        <th>Nilai Buku</th>
                    </tr>
                </thead>
                <tbody>
                    @if($depreciation && isset($depreciation['schedule']))
                        @foreach($depreciation['schedule'] as $row)
                        <tr>
                            <td>{{ $row['year'] }}</td>
                            <td class="text-end">{{ number_format($row['expense'],0,',','.') }}</td>
                            <td class="text-end">{{ number_format($row['accumulated'],0,',','.') }}</td>
                            <td class="text-end">{{ number_format($row['book_value'],0,',','.') }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="4" class="text-center">Tidak ada data penyusutan</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <a href="{{ route('fixed-assets.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection 