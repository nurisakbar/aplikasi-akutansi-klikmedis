@extends('layouts.base')

@section('page_title', 'Beranda Akuntansi')

@section('page_content')
<div class="container py-5">
    <div class="row justify-content-center mb-4">
        <div class="col-md-10 text-center">
            <h1 class="display-5 fw-bold mb-3">Selamat Datang di Sistem Akuntansi</h1>
            <p class="lead text-muted">Pilih modul di bawah untuk mulai mengelola data akuntansi Anda.</p>
        </div>
    </div>
    <div class="row justify-content-center g-4">
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
            <a href="{{ route('customers.index') }}" class="w-100 text-decoration-none">
                <div class="card text-white bg-primary h-100 text-center shadow-lg rounded-4 p-3 d-flex flex-column justify-content-center align-items-center" style="min-height: 180px;">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <div class="fw-bold fs-5">Customer</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
            <a href="{{ route('suppliers.index') }}" class="w-100 text-decoration-none">
                <div class="card text-white bg-success h-100 text-center shadow-lg rounded-4 p-3 d-flex flex-column justify-content-center align-items-center" style="min-height: 180px;">
                    <i class="fas fa-truck fa-3x mb-3"></i>
                    <div class="fw-bold fs-5">Supplier</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
            <a href="{{ route('fixed-assets.index') }}" class="w-100 text-decoration-none">
                <div class="card text-white bg-warning h-100 text-center shadow-lg rounded-4 p-3 d-flex flex-column justify-content-center align-items-center" style="min-height: 180px;">
                    <i class="fas fa-building fa-3x mb-3"></i>
                    <div class="fw-bold fs-5">Aset Tetap</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
            <a href="{{ route('expenses.index') }}" class="w-100 text-decoration-none">
                <div class="card text-white bg-danger h-100 text-center shadow-lg rounded-4 p-3 d-flex flex-column justify-content-center align-items-center" style="min-height: 180px;">
                    <i class="fas fa-money-bill-wave fa-3x mb-3"></i>
                    <div class="fw-bold fs-5">Manajemen Beban</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
            <a href="{{ route('taxes.index') }}" class="w-100 text-decoration-none">
                <div class="card text-white bg-info h-100 text-center shadow-lg rounded-4 p-3 d-flex flex-column justify-content-center align-items-center" style="min-height: 180px;">
                    <i class="fas fa-file-invoice fa-3x mb-3"></i>
                    <div class="fw-bold fs-5">Manajemen Pajak</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
            <a href="{{ route('chart-of-accounts.index') }}" class="w-100 text-decoration-none">
                <div class="card text-white bg-secondary h-100 text-center shadow-lg rounded-4 p-3 d-flex flex-column justify-content-center align-items-center" style="min-height: 180px;">
                    <i class="fas fa-book fa-3x mb-3"></i>
                    <div class="fw-bold fs-5">Chart of Accounts</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
            <a href="{{ route('journal-entries.index') }}" class="w-100 text-decoration-none">
                <div class="card text-white bg-dark h-100 text-center shadow-lg rounded-4 p-3 d-flex flex-column justify-content-center align-items-center" style="min-height: 180px;">
                    <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i>
                    <div class="fw-bold fs-5">Jurnal Umum</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
            <a href="{{ route('cash-bank.index') }}" class="w-100 text-decoration-none">
                <div class="card text-white h-100 text-center shadow-lg rounded-4 p-3 d-flex flex-column justify-content-center align-items-center" style="min-height: 180px; background: linear-gradient(135deg, #007bff 60%, #6610f2 100%);">
                    <i class="fas fa-cash-register fa-3x mb-3"></i>
                    <div class="fw-bold fs-5">Kas &amp; Bank</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
            <a href="{{ route('accounts-receivable.index') }}" class="w-100 text-decoration-none">
                <div class="card text-white bg-teal h-100 text-center shadow-lg rounded-4 p-3 d-flex flex-column justify-content-center align-items-center" style="min-height: 180px; background: linear-gradient(135deg, #20c997 60%, #17a2b8 100%);">
                    <i class="fas fa-hand-holding-usd fa-3x mb-3"></i>
                    <div class="fw-bold fs-5">Piutang Usaha</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
            <a href="{{ route('accounts-payable.index') }}" class="w-100 text-decoration-none">
                <div class="card text-white bg-indigo h-100 text-center shadow-lg rounded-4 p-3 d-flex flex-column justify-content-center align-items-center" style="min-height: 180px; background: linear-gradient(135deg, #6610f2 60%, #6f42c1 100%);">
                    <i class="fas fa-file-invoice fa-3x mb-3"></i>
                    <div class="fw-bold fs-5">Hutang Usaha</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
            <a href="{{ route('general-ledger.index') }}" class="w-100 text-decoration-none">
                <div class="card text-white bg-secondary h-100 text-center shadow-lg rounded-4 p-3 d-flex flex-column justify-content-center align-items-center" style="min-height: 180px; background: linear-gradient(135deg, #343a40 60%, #adb5bd 100%);">
                    <i class="fas fa-book-open fa-3x mb-3"></i>
                    <div class="fw-bold fs-5">Buku Besar</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
            <a href="{{ route('trial-balance.index') }}" class="w-100 text-decoration-none">
                <div class="card text-white bg-info h-100 text-center shadow-lg rounded-4 p-3 d-flex flex-column justify-content-center align-items-center" style="min-height: 180px; background: linear-gradient(135deg, #17a2b8 60%, #20c997 100%);">
                    <i class="fas fa-balance-scale fa-3x mb-3"></i>
                    <div class="fw-bold fs-5">Neraca Saldo</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
            <a href="{{ route('balance-sheet.index') }}" class="w-100 text-decoration-none">
                <div class="card text-white bg-success h-100 text-center shadow-lg rounded-4 p-3 d-flex flex-column justify-content-center align-items-center" style="min-height: 180px; background: linear-gradient(135deg, #28a745 60%, #218838 100%);">
                    <i class="fas fa-chart-bar fa-3x mb-3"></i>
                    <div class="fw-bold fs-5">Neraca</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 d-flex">
            <a href="{{ route('profit-loss.index') }}" class="w-100 text-decoration-none">
                <div class="card text-white bg-danger h-100 text-center shadow-lg rounded-4 p-3 d-flex flex-column justify-content-center align-items-center" style="min-height: 180px; background: linear-gradient(135deg, #dc3545 60%, #c82333 100%);">
                    <i class="fas fa-chart-line fa-3x mb-3"></i>
                    <div class="fw-bold fs-5">Laba Rugi</div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
