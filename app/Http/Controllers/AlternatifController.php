<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AlternatifController extends Controller
{
    /**
     * Menampilkan daftar alternatif dengan pagination dan pencarian.
     */
    public function index(Request $request): View
    {
        $query = Alternatif::query(); // Mulai query builder

        // Ambil nilai pencarian dari request
        $searchTerm = $request->input('search');

        // Jika ada input pencarian dan tidak kosong
        if ($searchTerm) {
            // Sesuaikan 'nama' dengan nama kolom yang ingin Anda cari.
            // Anda bisa menambahkan pencarian di kolom lain dengan ->orWhere()
            $query->where('nama', 'LIKE', "%{$searchTerm}%");
            // Contoh pencarian di kolom lain (misalnya 'deskripsi', jika ada):
            // $query->orWhere('deskripsi', 'LIKE', "%{$searchTerm}%");
        }

        // Ambil nilai 'per_page' dari request, default ke 5 jika tidak ada
        // Ini untuk jika Anda ingin menambahkan dropdown "Tampilkan X baris"
        $perPage = $request->input('per_page', 5); // Default 5 item per halaman

        // Urutkan (misalnya, data terbaru dulu) dan lakukan paginasi
        // Penting: appends($request->except('page')) akan menambahkan semua parameter query string saat ini
        // (seperti 'search' dan 'per_page', kecuali 'page' itu sendiri) ke link pagination
        // agar filter dan jumlah item per halaman tetap aktif saat berpindah halaman.
        $alternatifs = $query->orderBy('created_at', 'asc')->paginate($perPage)->appends($request->except('page'));

        return view('alternatifs.index', compact('alternatifs')); // Kirim data ke view
    }

    /**
     * Form untuk menambah alternatif baru.
     */
    public function create(): View
    {
        return view('alternatifs.create');
    }

    /**
     * Menyimpan alternatif ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|min:3|string|max:255',
        ]);

        Alternatif::create([
            'nama' => $request->nama,
            'terdaftar' => now(),
        ]);

        return redirect()->route('alternatifs.index')
            ->with('success', 'Data alternatif berhasil disimpan!');
    }

    /**
     * Menampilkan detail alternatif.
     */
    public function show($id): View
    {
        $alternatif = Alternatif::findOrFail($id);
        return view('alternatifs.show', compact('alternatif'));
    }

    /**
     * Form edit alternatif.
     */
    public function edit($id): View
    {
        $alternatif = Alternatif::findOrFail($id);
        return view('alternatifs.edit', compact('alternatif'));
    }

    /**
     * Update data alternatif.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|min:3|string|max:255',
        ]);

        $alternatif = Alternatif::findOrFail($id);
        $alternatif->update([
            'nama' => $request->nama,
            // 'terdaftar' biasanya tidak diupdate di sini, biarkan Eloquent menangani 'updated_at'
        ]);

        return redirect()->route('alternatifs.index')
            ->with('success', 'Data alternatif berhasil diperbarui!');
    }

    /**
     * Hapus alternatif.
     */
    public function destroy($id): RedirectResponse
    {
        $alternatif = Alternatif::findOrFail($id);
        $alternatif->delete();

        return redirect()->route('alternatifs.index')
            ->with('success', 'Data alternatif berhasil dihapus!');
    }
}