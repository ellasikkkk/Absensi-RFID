<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rfid;
use App\Models\Siswa;

class AbsenController extends Controller
{
    public function absen(Request $request)
    {
        $tag = strtoupper($request->query('tag'));
        $tanggal = $request->query('tanggal', now()->toDateString());
        $waktu = $request->query('waktu', now()->format('H:i:s'));

        // 1. Cek apakah sudah absen hari ini
        $sudahAbsen = Rfid::where('tag', $tag)
            ->whereDate('tanggal', $tanggal)
            ->exists();

        if ($sudahAbsen) {
            return response("SUDAH ABSEN", 200);
        }

        // 2. Cek apakah tag terdaftar
        $siswaTerdaftar = Siswa::where('tag', $tag)->exists();
        if (!$siswaTerdaftar) {
            return response("BELUM TERDAFTAR", 200);
        }

        // 3. Tentukan status keterlambatan
        $jamMasuk = '06:30:00';
        $status = ($waktu > $jamMasuk) ? 'Terlambat' : 'Tepat Waktu';

        // 4. Simpan ke database
        Rfid::create([
            'tag' => $tag,
            'tanggal' => $tanggal,
            'waktu' => $waktu,
            'status' => $status,
        ]);

        return response("OK", 200);
    }
}
