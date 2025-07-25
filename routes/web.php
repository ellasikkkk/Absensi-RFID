<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Models\Tambah;
use App\Models\Siswa;
use App\Models\Rfid;

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TambahController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\AbsensiController;

// ✅ Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('home');

// ✅ Data Siswa
Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
Route::post('/siswa/store', [SiswaController::class, 'store'])->name('siswa.store');
Route::post('/siswa/update/{id}', [SiswaController::class, 'update'])->name('siswa.update');
Route::delete('/siswa/delete/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

// ✅ UID Baru (Kartu RFID yang belum terdaftar)
Route::get('/uid-baru', [TambahController::class, 'index'])->name('tambah.index');
Route::post('/uid-baru/tambah', [TambahController::class, 'store'])->name('tambah.store');

// ✅ Tampilan per kelas
Route::get('/kelas/{nama}', [KelasController::class, 'show'])->name('kelas.show');

// ✅ Endpoint ESP8266: Simpan UID baru jika belum terdaftar
Route::get('/tambah', function(Request $request) {
    $tag = strtoupper($request->query('tag'));
    if (!Siswa::where('tag', $tag)->exists()) {
        Tambah::updateOrCreate(['tag' => $tag]);
    }
    return response("OK", 200);
});

// ✅ Endpoint ESP8266: Absensi RFID
Route::get('/absen', [AbsenController::class, 'absen']);

// ✅ Halaman Web: Rekap data absensi
Route::get('/data-absen', [AbsensiController::class, 'index'])->name('absen.index');

// ✅ Resource jika dibutuhkan fitur tambahan absen
Route::resource('absen', AbsenController::class)->except(['index']);
