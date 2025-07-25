<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Tambah;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query();

        // Filter berdasarkan nama
        if ($request->filled('nama')) {
            $query->where('Nama', 'like', '%' . $request->nama . '%');
        }

        $siswa = $query->get(); // Gunakan ->paginate(...) jika perlu pagination
        $kelasList = config('kelas.list');

        return view('siswa.index', compact('siswa', 'kelasList'));
    }

    public function create()
    {
        $tag = Tambah::orderBy('id', 'desc')->first()?->tag ?? '';
        $kelasList = config('kelas.list');

        return view('siswa.create', compact('tag', 'kelasList'));
    }

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

        Tambah::where('tag', $request->tag)->delete();

        return redirect()->route('siswa.index')->with('success', 'Siswa baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelasList = config('kelas.list');

        return view('siswa.edit', compact('siswa', 'kelasList'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'Nama' => 'required|string|max:100',
            'Kelas' => 'required|string|max:50',
            'tag' => 'required|string|max:50|unique:siswa,tag,' . $id,
        ]);

        $siswa->update([
            'Nama' => $request->Nama,
            'Kelas' => $request->Kelas,
            'tag' => strtoupper($request->tag),
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
