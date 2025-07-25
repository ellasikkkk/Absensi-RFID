@extends('layouts.app')

@section('title', 'Rekap Kelas ' . $kelas)

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-gradient text-primary mb-1">Rekap Absensi Kelas</h2>
            <h3 class="text-dark">{{ $kelas }}</h3>
        </div>
        <div class="date-badge bg-white shadow-sm rounded-pill px-3 py-2">
            <i class="bi bi-calendar3 me-2 text-primary"></i>
            <span class="fw-medium">{{ now()->format('d F Y') }}</span>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body p-4">
            <h5 class="card-title mb-3 text-muted">Filter Data</h5>
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="tanggal" class="form-label small text-muted">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control form-control-lg" value="{{ $tanggal }}">
                </div>
                <div class="col-md-4">
                    <label for="nama" class="form-label small text-muted">Cari Siswa</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent"><i class="bi bi-search"></i></span>
                        <input type="text" name="nama" class="form-control form-control-lg" placeholder="Nama siswa..." value="{{ $cariNama }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-funnel me-2"></i>Filter
                    </button>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('kelas.show', urlencode($kelas)) }}" class="btn btn-outline-secondary btn-lg w-100">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card stat-card border-0 bg-success bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted small">Siswa Hadir</h6>
                            <h2 class="fw-bold text-success mb-0">
                                @php $hadirCount = 0; @endphp
                                @foreach ($siswa as $item)
                                    @if (isset($absensi[$item->tag])) @php $hadirCount++; @endphp @endif
                                @endforeach
                                {{ $hadirCount }}
                            </h2>
                            <span class="text-success small">
                                {{ $siswa->count() > 0 ? round(($hadirCount/$siswa->count())*100, 2) : 0 }}% dari total siswa
                            </span>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-25 rounded-circle">
                            <i class="bi bi-check-circle-fill text-success fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card border-0 bg-danger bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted small">Siswa Tidak Hadir</h6>
                            <h2 class="fw-bold text-danger mb-0">
                                @php $tidakHadirCount = 0; @endphp
                                @foreach ($siswa as $item)
                                    @if (!isset($absensi[$item->tag])) @php $tidakHadirCount++; @endphp @endif
                                @endforeach
                                {{ $tidakHadirCount }}
                            </h2>
                            <span class="text-danger small">
                                {{ $siswa->count() > 0 ? round(($tidakHadirCount/$siswa->count())*100, 2) : 0 }}% dari total siswa
                            </span>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-25 rounded-circle">
                            <i class="bi bi-x-circle-fill text-danger fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Present Students Table -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-success bg-opacity-10 border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-success">
                <i class="bi bi-check-circle-fill me-2"></i>Daftar Hadir
            </h5>
            <span class="badge bg-success rounded-pill">{{ $hadirCount }} siswa</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nama Siswa</th>
                            <th>Tag RFID</th>
                            <th>Status</th>
                            <th>Waktu Absensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $hadirCount = 0; @endphp
                        @foreach ($siswa as $item)
                            @php
                                $absen = $absensi[$item->tag] ?? null;
                            @endphp
                            @if ($absen)
                                @php $hadirCount++; @endphp
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $item->Nama }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $item->tag }}</span></td>
                                    <td>
                                        <span class="badge {{ $absen['status'] === 'Terlambat' ? 'bg-warning' : 'bg-success' }} rounded-pill">
                                            {{ $absen['status'] }}
                                        </span>
                                    </td>
                                    <td>{{ $absen['waktu'] }}</td>
                                </tr>
                            @endif
                        @endforeach

                        @if ($hadirCount === 0)
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="bi bi-info-circle me-2"></i>Tidak ada siswa yang hadir
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Absent Students Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-danger bg-opacity-10 border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-danger">
                <i class="bi bi-x-circle-fill me-2"></i>Tidak Hadir
            </h5>
            <span class="badge bg-danger rounded-pill">{{ $tidakHadirCount }} siswa</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nama Siswa</th>
                            <th>Tag RFID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $tidakHadirCount = 0; @endphp
                        @foreach ($siswa as $item)
                            @if (!isset($absensi[$item->tag]))
                                @php $tidakHadirCount++; @endphp
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $item->Nama }}</td>
                                    <td><span class="badge bg-light text-dark">{{ $item->tag }}</span></td>
                                </tr>
                            @endif
                        @endforeach

                        @if ($tidakHadirCount === 0)
                            <tr>
                                <td colspan="2" class="text-center py-4 text-muted">
                                    <i class="bi bi-check-circle me-2"></i>Semua siswa hadir
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Styles */
    .text-gradient {
        background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .stat-card {
        border-radius: 12px;
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .date-badge {
        transition: all 0.3s ease;
    }
    
    .date-badge:hover {
        box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
    }
    
    .card-header {
        padding: 1rem 1.5rem;
    }
    
    .form-control-lg {
        padding: 0.75rem 1rem;
    }
    
    .btn-lg {
        padding: 0.75rem 1rem;
    }
</style>
@endsection