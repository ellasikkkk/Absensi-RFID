@extends('layouts.app')

@section('title', 'Tambah Siswa')

@push('styles')
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
    body {
        overflow-x: hidden;
    }

    main {
        padding-left: 270px;
    }

    @media (max-width: 768px) {
        main {
            padding-left: 0;
        }
    }
</style>
@endpush

@section('content')
<main class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-1">
            <h2 class="mb-4">Tambah Siswa dari Kartu Baru</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('siswa.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Tag RFID</label>
                    <input type="text" name="tag" class="form-control" value="{{ $tag }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="Nama" class="form-control" placeholder="Nama Lengkap" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kelas</label>
                    <select name="Kelas" class="form-select" required>
                        <option value="" disabled selected>Pilih Kelas</option>
                        @foreach (config('kelas') as $kelas)
                            <option value="{{ $kelas }}">{{ $kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</main>
@endsection

@section('sidebar')
<div class="d-none d-md-flex flex-column p-3 bg-dark text-white position-fixed" style="width: 250px; height: 100vh;">
    <a href="/" class="d-flex align-items-center mb-3 text-white text-decoration-none">
        <span class="fs-4">Absensi RFID</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li>
            <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door me-2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('siswa.index') }}" class="nav-link text-white {{ request()->is('siswa*') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill me-2"></i> Data Siswa
            </a>
        </li>
        <li>
            <a href="{{ route('absensi.index') }}" class="nav-link text-white {{ request()->is('absensi*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check me-2"></i> Rekap Absensi
            </a>
        </li>
        <!-- Submenu kelas -->
        <li class="nav-item mt-3">
            <a class="nav-link text-white d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#submenuKelas" role="button"
                aria-expanded="{{ request()->is('kelas*') ? 'true' : 'false' }}" aria-controls="submenuKelas">
                <span><i class="bi bi-book me-2"></i> Kelas</span>
                <i class="bi bi-chevron-down"></i>
            </a>
            <div class="collapse {{ request()->is('kelas*') ? 'show' : '' }}" id="submenuKelas">
                <ul class="nav flex-column ms-3">
                    @foreach (config('kelas') as $kelas)
                        <li class="nav-item">
                            <a href="{{ url('/kelas/' . urlencode($kelas)) }}" class="nav-link text-white {{ request()->is('kelas/' . urlencode($kelas)) ? 'active' : '' }}">
                                {{ $kelas }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </li>
    </ul>
    <hr>
    <div class="text-white small">© 2025 SMK Wisata Indonesia</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush
