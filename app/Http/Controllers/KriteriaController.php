<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $query = Kriteria::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_kriteria', 'like', "%{$search}%")
                  ->orWhere('tipe', 'like', "%{$search}%")
                  ->orWhere('bobot', 'like', "%{$search}%");
            });
        }

        // Menggunakan paginate dengan onEachSide(0)
        $kriterias = $query->orderBy('created_at', 'asc')
                           ->paginate(5)       // Jumlah item per halaman (sesuaikan jika perlu)
                           ->onEachSide(0)     // Meminta window seminimal mungkin
                           ->appends($request->except('page')); // Mempertahankan parameter query

        return view('kriterias.index', compact('kriterias', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('kriterias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'tipe'          => 'required|in:benefit,cost',
            'bobot'         => 'required|numeric|min:0|max:100'
        ], [
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'nama_kriteria.max'      => 'Nama kriteria terlalu panjang.',
            'tipe.required'          => 'Tipe kriteria wajib dipilih.',
            'bobot.required'         => 'Bobot harus diisi.',
            'bobot.numeric'          => 'Bobot harus berupa angka.',
            'bobot.min'              => 'Bobot minimal 0',
            'bobot.max'              => 'Bobot maksimal 100.'
        ]);

        $totalBobot = Kriteria::sum('bobot');
        if (($totalBobot + $request->bobot) > 100) {
            return back()->withErrors(['bobot' => 'Total bobot seluruh kriteria tidak boleh lebih dari 100.'])->withInput();
        }

        Kriteria::create($request->only(['nama_kriteria', 'tipe', 'bobot']));

        return redirect()->route('kriterias.index')->with('success', 'Data Kriteria berhasil disimpan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kriteria $kriteria): View // Menggunakan Route Model Binding
    {
        return view('kriterias.edit', compact('kriteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kriteria $kriteria): RedirectResponse // Menggunakan Route Model Binding
    {
        $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'tipe'          => 'required|in:benefit,cost',
            'bobot'         => 'required|numeric|min:0|max:100'
        ], [
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'nama_kriteria.max'      => 'Nama kriteria terlalu panjang.',
            'tipe.required'          => 'Tipe kriteria wajib dipilih.',
            'bobot.required'         => 'Bobot harus diisi.',
            'bobot.numeric'          => 'Bobot harus berupa angka.',
            'bobot.min'              => 'Bobot minimal 0.',
            'bobot.max'              => 'Bobot maksimal 100.'
        ]);

        $totalBobotLain = Kriteria::where('id', '!=', $kriteria->id)->sum('bobot');
        if (($totalBobotLain + $request->bobot) > 100) {
            return back()->withErrors(['bobot' => 'Total bobot seluruh kriteria tidak boleh lebih dari 100.'])->withInput();
        }

        $kriteria->update($request->only(['nama_kriteria', 'tipe', 'bobot']));

        return redirect()->route('kriterias.index')->with('success', 'Data Kriteria berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kriteria $kriteria): RedirectResponse // Menggunakan Route Model Binding
    {
        $kriteria->delete();
        return redirect()->route('kriterias.index')->with('success', 'Data Kriteria berhasil dihapus!');
    }
}