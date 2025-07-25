<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Rfid;

class KelasController extends Controller
{
    public function show(Request $request, $nama)
    {
        $kelas = urldecode($nama);
        $tanggal = $request->query('tanggal', now()->toDateString());
        $cariNama = $request->query('nama');

        // Ambil semua siswa di kelas
        $query = Siswa::where('Kelas', $kelas);
        if ($cariNama) {
            $query->where('Nama', 'like', '%' . $cariNama . '%');
        }
        $siswa = $query->get();

        // Ambil data absensi pada tanggal itu, dikelompokkan berdasarkan tag
        $absensi = Rfid::whereDate('tanggal', $tanggal)
            ->get()
            ->groupBy('tag')
            ->map(function ($items) {
                $data = $items->first(); // Ambil absensi pertama untuk tag ini
                return [
                    'status' => $data->status,
                    'waktu' => $data->waktu,
                ];
            })
            ->toArray();

        return view('kelas.show', compact('kelas', 'siswa', 'absensi', 'tanggal', 'cariNama'));
    }
}
