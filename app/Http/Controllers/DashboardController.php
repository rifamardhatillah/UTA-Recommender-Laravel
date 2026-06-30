<?php

namespace App\Http\Controllers; // <--- PASTIKAN NAMESPACE INI BENAR

use Illuminate\Http\Request;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Nilai;
// use App\Models\Tahun;

class DashboardController extends Controller // <--- PASTIKAN NAMA CLASS INI BENAR
{
    public function index()
    {
        $jumlahAlternatif = Alternatif::count();
        $jumlahKriteria = Kriteria::count();
        $jumlahAlternatifDinilai = Nilai::distinct('alternatif_id')->count('alternatif_id');

        $jumlahTahunSeleksi = 3; // Ganti dengan logika dinamis jika ada
        $tahunTerkini = 2026;    // Ganti dengan logika dinamis jika ada

        $deskripsiBobotKriteria = "Menggunakan {$jumlahKriteria} set bobot kriteria";

        return view('dashboard', compact(
            'jumlahAlternatif',
            'jumlahKriteria',
            'jumlahAlternatifDinilai',
            'jumlahTahunSeleksi',
            'tahunTerkini',
            'deskripsiBobotKriteria'
        ));
    }
}