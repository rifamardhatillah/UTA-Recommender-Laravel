<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Alternatif;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $kriterias = Kriteria::orderBy('id')->get();

        $query = Alternatif::query();

        // Filter HANYA alternatif yang memiliki setidaknya satu nilai
        // Ini memastikan bahwa alternatif yang belum pernah diinput nilainya tidak akan tampil
        $query->whereHas('nilais');

        if ($request->has('search') && $request->search != '') {
            // Pencarian tetap pada nama alternatif, tapi sudah terfilter oleh whereHas('nilais')
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Eager load relasi 'nilais' dan 'kriteria' di dalam 'nilais'
        $alternatifs = $query->with(['nilais' => function ($q_nilai) {
            $q_nilai->with('kriteria');
        }])->orderBy('nama')->paginate(10); // Paginate Alternatif

        // Siapkan data nilai agar mudah diakses di view
        $alternatifNilais = [];
        foreach ($alternatifs as $alternatif) {
            $scores = [];
            foreach ($alternatif->nilais as $nilai) {
                if ($nilai->kriteria) {
                    $scores[$nilai->kriteria_id] = $nilai->nilai;
                }
            }
            $alternatifNilais[$alternatif->id] = $scores;
        }

        return view('nilais.index', compact('alternatifs', 'kriterias', 'alternatifNilais'));
    }

    public function create()
    {
        // Di form create, kita ingin menampilkan semua alternatif yang ada
        // atau hanya alternatif yang belum memiliki nilai sama sekali.
        // Untuk kesederhanaan dan agar bisa "melengkapi" jika ada yang terlewat,
        // lebih baik tampilkan semua alternatif.
        // Jika Anda benar-benar hanya ingin menampilkan yang BELUM punya nilai, Anda bisa gunakan:
        // $alternatifs = Alternatif::whereDoesntHave('nilais')->orderBy('nama')->pluck('nama', 'id');
        // Namun, dengan updateOrCreate di store, menampilkan semua lebih fleksibel.

        $alternatifs = Alternatif::orderBy('nama')->pluck('nama', 'id');
        $kriterias = Kriteria::orderBy('id')->get();
        return view('nilais.create', compact('alternatifs', 'kriterias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alternatif_id' => 'required|exists:alternatifs,id',
            'nilai.*' => 'required|numeric|min:0',
        ]);

        $alternatifId = $request->alternatif_id;

        foreach ($request->nilai as $kriteria_id => $value) {
            if ($value !== null) {
                Nilai::updateOrCreate(
                    ['alternatif_id' => $alternatifId, 'kriteria_id' => $kriteria_id],
                    ['nilai' => $value]
                );
            }
        }

        return redirect()->route('nilais.index')->with('success', 'Nilai berhasil ditambahkan/diperbarui.');
    }

    public function edit($alternatif_id)
    {
        // Alternatif yang diedit pasti sudah ada di index, jadi pasti punya nilai
        $alternatif = Alternatif::with(['nilais' => function ($q) {
            $q->with('kriteria');
        }])->findOrFail($alternatif_id);

        $kriterias = Kriteria::orderBy('id')->get();

        $currentNilais = [];
        foreach ($alternatif->nilais as $nilai) {
            if ($nilai->kriteria) {
                $currentNilais[$nilai->kriteria_id] = $nilai->nilai;
            }
        }

        return view('nilais.edit', compact('alternatif', 'kriterias', 'currentNilais'));
    }

    public function update(Request $request, $alternatif_id)
    {
        $request->validate([
            'nilai.*' => 'required|numeric|min:0',
        ]);

        $alternatif = Alternatif::findOrFail($alternatif_id);

        foreach ($request->nilai as $kriteria_id => $value) {
             if ($value !== null) {
                Nilai::updateOrCreate(
                    ['alternatif_id' => $alternatif->id, 'kriteria_id' => $kriteria_id],
                    ['nilai' => $value]
                );
            // Opsional: Jika ingin menghapus nilai jika field dikosongkan saat edit
            // } else {
            //     Nilai::where('alternatif_id', $alternatif->id)
            //          ->where('kriteria_id', $kriteria_id)
            //          ->delete();
            }
        }

        return redirect()->route('nilais.index')->with('success', 'Nilai untuk alternatif ' . $alternatif->nama . ' berhasil diperbarui.');
    }

    public function destroy($alternatif_id)
    {
        $alternatif = Alternatif::findOrFail($alternatif_id);
        // Menghapus semua nilai yang terkait dengan alternatif_id ini
        Nilai::where('alternatif_id', $alternatif->id)->delete();
        // Setelah dihapus, alternatif ini tidak akan lagi memenuhi whereHas('nilais')
        // dan otomatis tidak tampil di index.
        return redirect()->route('nilais.index')->with('success', 'Semua nilai untuk alternatif ' . $alternatif->nama . ' berhasil dihapus.');
    }
}