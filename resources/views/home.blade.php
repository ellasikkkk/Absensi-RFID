@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="dashboard-header mb-5">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fw-bold text-gradient text-primary mb-1">Absensi Dashboard</h1>
                <p class="text-muted">Overview of today's attendance statistics</p>
            </div>
            <div class="date-badge bg-white shadow-sm rounded-pill px-3 py-2">
                <i class="bi bi-calendar3 me-2 text-primary"></i>
                <span class="fw-medium">{{ now()->format('l, d F Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Students -->
        <div class="col-xl-4 col-md-6">
            <div class="card stat-card border-0 shadow-hover h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="stat-content">
                            <h6 class="stat-title text-uppercase text-muted">Total Students</h6>
                            <h2 class="stat-value fw-bold mt-2 mb-3">{{ $totalSiswa }}</h2>
                            <div class="stat-change positive">
                                <i class="bi bi-people-fill me-1"></i>
                                <span>All registered students</span>
                            </div>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 rounded-circle">
                            <i class="bi bi-people-fill text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Present Today -->
        <div class="col-xl-4 col-md-6">
            <div class="card stat-card border-0 shadow-hover h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="stat-content">
                            <h6 class="stat-title text-uppercase text-muted">Present Today</h6>
                            <h2 class="stat-value fw-bold mt-2 mb-3 text-success">{{ $hadirHariIni }}</h2>
                            <div class="stat-change positive">
                                <i class="bi bi-arrow-up-right me-1"></i>
                                <span>{{ $totalSiswa > 0 ? round(($hadirHariIni/$totalSiswa)*100, 2) : 0 }}% attendance rate</span>
                            </div>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 rounded-circle">
                            <i class="bi bi-check-circle-fill text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Absent Today -->
        <div class="col-xl-4 col-md-6">
            <div class="card stat-card border-0 shadow-hover h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="stat-content">
                            <h6 class="stat-title text-uppercase text-muted">Absent Today</h6>
                            <h2 class="stat-value fw-bold mt-2 mb-3 text-danger">{{ $tidakHadir }}</h2>
                            <div class="stat-change negative">
                                <i class="bi bi-arrow-down-right me-1"></i>
                                <span>{{ $totalSiswa > 0 ? round(($tidakHadir/$totalSiswa)*100, 2) : 0 }}% absence rate</span>
                            </div>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10 rounded-circle">
                            <i class="bi bi-x-circle-fill text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Section -->
    <div class="row g-4">
        <!-- Best Performing Class -->
        <div class="col-lg-6">
            <div class="card performance-card border-0 shadow-hover h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Best Performing Class</h5>
                        <span class="badge bg-success bg-opacity-10 text-success">
                            <i class="bi bi-award me-1"></i> Top
                        </span>
                    </div>
                    <div class="performance-content">
                        <h3 class="performance-value text-info fw-bold mb-2">{{ $kelasTertinggi ?? 'N/A' }}</h3>
                        <p class="text-muted mb-4">Highest attendance percentage today</p>
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1" style="height: 8px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="ms-3 fw-bold text-info">85%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Needs Attention -->
        <div class="col-lg-6">
            <div class="card performance-card border-0 shadow-hover h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Needs Attention</h5>
                        <span class="badge bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-exclamation-triangle me-1"></i> Low
                        </span>
                    </div>
                    <div class="performance-content">
                        <h3 class="performance-value text-warning fw-bold mb-2">{{ $kelasTerendah ?? 'N/A' }}</h3>
                        <p class="text-muted mb-4">Lowest attendance percentage today</p>
                        <div class="d-flex align-items-center">
                            <div class="progress flex-grow-1" style="height: 8px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="ms-3 fw-bold text-warning">45%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Styles */
    .dashboard-header {
        padding-top: 1rem;
    }
    
    .text-gradient {
        background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .stat-card {
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .stat-value {
        font-size: 2.2rem;
    }
    
    .stat-change {
        font-size: 0.9rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
    }
    
    .stat-change.positive {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    
    .stat-change.negative {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }
    
    .performance-card {
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .performance-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .performance-value {
        font-size: 2rem;
    }
    
    .shadow-hover {
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .date-badge {
        transition: all 0.3s ease;
    }
    
    .date-badge:hover {
        box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection