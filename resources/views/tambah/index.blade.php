@extends('layouts.app')

@section('title', 'UID Baru')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endpush

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4">
        <div class="card-header bg-primary bg-gradient text-white d-flex justify-content-between align-items-center rounded-top-4">
            <h4 class="mb-0"><i class="bi bi-credit-card me-2"></i> Daftar UID Baru</h4>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-0 py-1 px-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary text-uppercase small">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Tag UID</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($uidBaru as $index => $item)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $index + 1 }}</td>
                            <td>
                                <span class="badge text-bg-light text-primary fw-semibold">
                                    <i class="bi bi-tag-fill me-1"></i>{{ $item->tag }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-success d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalTambah{{ $item->id }}">
                                    <i class="bi bi-person-plus"></i> Tambah Siswa
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="text-center py-5 bg-light rounded-bottom">
                                    <i class="bi bi-credit-card fs-1 text-muted mb-2 d-block"></i>
                                    <h5 class="text-muted mb-0">Belum ada UID baru</h5>
                                    <p class="text-muted small">Scan kartu RFID baru untuk menampilkan UID disini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modals -->
@foreach ($uidBaru as $item)
<div class="modal fade" id="modalTambah{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <form action="{{ route('tambah.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary bg-gradient text-white">
                    <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Tambah Siswa Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pt-4 pb-0">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="Nama" class="form-control form-control-lg" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="Kelas" class="form-select form-select-lg" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas }}">{{ $kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Tag UID</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="bi bi-credit-card text-primary"></i></span>
                            <input type="text" name="tag" class="form-control" value="{{ $item->tag }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-1">
                        <i class="bi bi-save"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
