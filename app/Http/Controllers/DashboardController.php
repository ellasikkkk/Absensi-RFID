<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Rfid;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $hadirHariIni = Rfid::whereDate('tanggal', now())->distinct('tag')->count('tag');
        $tidakHadir = $totalSiswa - $hadirHariIni;

        $kelasTertinggi = Siswa::select('Kelas', DB::raw('COUNT(rfid.id) as jumlah'))
            ->leftJoin('rfid', function($join) {
                $join->on('siswa.tag', '=', 'rfid.tag')
                    ->whereDate('rfid.tanggal', now());
            })
            ->groupBy('Kelas')
            ->orderByDesc('jumlah')
            ->value('Kelas');

        $kelasTerendah = Siswa::select('Kelas', DB::raw('COUNT(rfid.id) as jumlah'))
            ->leftJoin('rfid', function($join) {
                $join->on('siswa.tag', '=', 'rfid.tag')
                    ->whereDate('rfid.tanggal', now());
            })
            ->groupBy('Kelas')
            ->orderBy('jumlah')
            ->value('Kelas');

        return view('home', compact(
            'totalSiswa',
            'hadirHariIni',
            'tidakHadir',
            'kelasTertinggi',
            'kelasTerendah'
        ));
    }
}
