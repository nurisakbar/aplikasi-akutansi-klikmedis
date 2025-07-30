@extends('layouts.base')

@section('page_title', 'Detail Akun')

@section('page_content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detail Akun</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Kode Akun</th>
                            <td><span class="badge badge-info">{{ $account->code }}</span></td>
                        </tr>
                        <tr>
                            <th>Nama Akun</th>
                            <td>{{ $account->name }}</td>
                        </tr>
                        <tr>
                            <th>Tipe</th>
                            <td>
                                <span class="badge badge-{{ $account->type == 'asset' ? 'success' : ($account->type == 'liability' ? 'danger' : ($account->type == 'equity' ? 'warning' : ($account->type == 'revenue' ? 'info' : 'secondary'))) }}">
                                    {{ ucfirst($account->type) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>{{ ucwords(str_replace('_', ' ', $account->category)) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Parent Akun</th>
                            <td>
                                @if($account->parent)
                                    <a href="{{ route('chart-of-accounts.show', $account->parent->id) }}">
                                        {{ $account->parent->code }} - {{ $account->parent->name }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Level</th>
                            <td>{{ $account->level }}</td>
                        </tr>
                        <tr>
                            <th>Path</th>
                            <td><code>{{ $account->path }}</code></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge badge-{{ $account->is_active ? 'success' : 'danger' }}">
                                    {{ $account->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($account->description)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-body bg-light">
                            <h5 class="card-title">Deskripsi</h5>
                            <p class="card-text">{{ $account->description }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($account->children->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Sub Akun</h5>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Tipe</th>
                                            <th>Kategori</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($account->children as $child)
                                            <tr>
                                                <td><span class="badge badge-info">{{ $child->code }}</span></td>
                                                <td>{{ $child->name }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $child->type == 'asset' ? 'success' : ($child->type == 'liability' ? 'danger' : ($child->type == 'equity' ? 'warning' : ($child->type == 'revenue' ? 'info' : 'secondary'))) }}">
                                                        {{ ucfirst($child->type) }}
                                                    </span>
                                                </td>
                                                <td>{{ ucwords(str_replace('_', ' ', $child->category)) }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $child->is_active ? 'success' : 'danger' }}">
                                                        {{ $child->is_active ? 'Aktif' : 'Nonaktif' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('chart-of-accounts.show', $child->id) }}" 
                                                       class="btn btn-sm btn-info" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('chart-of-accounts.edit', $account->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <a href="{{ route('chart-of-accounts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 