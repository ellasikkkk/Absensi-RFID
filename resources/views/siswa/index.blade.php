@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-gradient text-primary mb-1">Daftar Siswa</h2>
            <p class="text-muted">Manajemen data siswa dan tag RFID</p>
        </div>
        <div>
            <a href="{{ route('tambah.index') }}" class="btn btn-success btn-lg">
                <i class="bi bi-plus-circle me-2"></i>Tambah Siswa
            </a>
        </div>
    </div>

    <!-- Alert Notification -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
     @endif

    <!-- Form Filter Nama -->
    <form method="GET" action="{{ route('siswa.index') }}" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="nama" value="{{ request('nama') }}" class="form-control form-control-lg" placeholder="Cari nama siswa...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-lg btn-primary">
                <i class="bi bi-search"></i> Cari
            </button>
            <a href="{{ route('siswa.index') }}" class="btn btn-lg btn-outline-secondary">Reset</a>
        </div>
    </form>

    <!-- Student Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Tag RFID</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($siswa as $item)
                            <tr>
                                <td class="ps-4 fw-medium">{{ $item->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-primary bg-opacity-10 rounded-circle me-3">
                                            <i class="bi bi-person-fill text-primary"></i>
                                        </div>
                                        <div>{{ $item->Nama }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2">
                                        {{ $item->Kelas }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                        <i class="bi bi-tag-fill me-2"></i>{{ $item->tag }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <!-- Edit Button -->
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                            <i class="bi bi-pencil-square me-1"></i>Edit
                                        </button>
                                        
                                        <!-- Delete Button -->
                                        <form action="{{ route('siswa.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Yakin ingin menghapus data siswa ini?')" 
                                                    class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash me-1"></i>Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('siswa.update', $item->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title fw-bold" id="editModalLabel{{ $item->id }}">Edit Data Siswa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Lengkap</label>
                                                    <input type="text" name="Nama" class="form-control form-control-lg" value="{{ $item->Nama }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Kelas</label>
                                                    <select name="Kelas" class="form-select form-select-lg" required>
                                                        <option value="">-- Pilih Kelas --</option>
                                                        @foreach ($kelasList as $kelas)
                                                            <option value="{{ $kelas }}" {{ $item->Kelas === $kelas ? 'selected' : '' }}>
                                                                {{ $kelas }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Tag RFID</label>
                                                    <input type="text" name="tag" class="form-control form-control-lg" value="{{ $item->tag }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-lg btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-lg btn-primary">
                                                    <i class="bi bi-save me-1"></i>Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
    
    .avatar {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom: 2px solid #e9ecef;
    }
    
    .table td {
        vertical-align: middle;
        padding: 1rem 0.5rem;
    }
    
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .modal-header {
        padding: 1.5rem;
    }
    
    .modal-body {
        padding: 0 1.5rem;
    }
    
    .modal-footer {
        padding: 1.5rem;
    }
    
    .form-control-lg, .form-select-lg {
        padding: 0.75rem 1rem;
        border-radius: 8px;
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
    }
    
    .alert {
        border-radius: 8px;
    }
</style>
@endsection