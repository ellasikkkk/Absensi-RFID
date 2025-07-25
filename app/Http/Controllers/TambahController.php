<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tambah;
use App\Models\Siswa;

class TambahController extends Controller
{
    // Menampilkan daftar UID baru & form tambah siswa
    public function index()
    {
        $uidBaru = Tambah::all();

        // Daftar kelas (bisa disesuaikan)
        $kelasList = ['X TKJ', 'XI TKJ', 'XII TKJ', 'X PH', 'XI PH', 'XII PH', 'X BOGA', 'XI BOGA', 'XII BOGA'];

        return view('tambah.index', compact('uidBaru', 'kelasList'));
    }

    // Menyimpan data siswa baru
    public function store(Request $request)
    {
        $request->validate([
            'Nama' => 'required|string|max:100',
            'Kelas' => 'required|string|max:50',
            'tag' => 'required|string|unique:siswa,tag|max:50',
        ]);

        Siswa::create([
            'Nama' => $request->Nama,
            'Kelas' => $request->Kelas,
            'tag' => strtoupper($request->tag),
        ]);

        // Hapus dari tabel tambah setelah ditambahkan ke siswa
        Tambah::where('tag', $request->tag)->delete();

        return redirect()->route('tambah.index')->with('success', 'Siswa berhasil ditambahkan.');
    }
}
