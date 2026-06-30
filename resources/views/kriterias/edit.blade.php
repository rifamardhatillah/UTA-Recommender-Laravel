{{-- resources/views/kriterias/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Data Kriteria - ' . $kriteria->nama_kriteria)

@push('styles')
    {{--
        Catatan untuk Font:
        Jika layouts.app Anda sudah mengatur font-family secara global (misalnya 'Inter'),
        maka style di bawah ini mungkin tidak diperlukan.
    --}}
    <style>
        .content-kriteria-edit-form-wrapper { /* Opsional wrapper class */
            font-family: 'Inter', sans-serif; /* Sesuaikan jika perlu */
        }
    </style>
@endpush

@push('scripts')
    {{--
        PENTING: Jika proyek Anda sudah setup Tailwind CSS secara global (via npm, tailwind.config.js),
        maka Anda TIDAK PERLU menyertakan CDN Tailwind dan konfigurasi inline di bawah ini.
        Warna kustom juga sebaiknya ada di file tailwind.config.js utama.
    --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.tailwind = window.tailwind || {};
        window.tailwind.config = {
            ...(window.tailwind.config || {}),
            theme: {
                ...(window.tailwind.config?.theme || {}),
                extend: {
                    ...(window.tailwind.config?.theme?.extend || {}),
                    colors: {
                        ...(window.tailwind.config?.theme?.extend?.colors || {}),
                        'custom-purple': '#435ebe', // Warna dari contoh alternatif/create
                        'custom-purple-darker': '#3a50a0', // Warna dari contoh alternatif/create
                        'custom-red': '#dc3545', // Warna dari contoh alternatif/create
                    }
                }
            }
        };
    </script>
@endpush

@section('content')
    {{--
        Background akan mengikuti layouts.app. Jika ingin background spesifik
        untuk area ini, tambahkan div pembungkus dengan kelas Tailwind (misal, bg-gray-100).
    --}}
    {{-- <div class="content-kriteria-edit-form-wrapper"> --}}

        <div class="container mx-auto px-4 mt-8 mb-8">
            <div class="max-w-2xl mx-auto"> {{-- Batasi lebar form, sesuaikan jika perlu --}}
                <div class="bg-white shadow-lg rounded-lg p-6 md:p-8">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-6">Edit Kriteria: {{ $kriteria->nama_kriteria }}</h3>

                    <form action="{{ route('kriterias.update', $kriteria->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- NAMA KRITERIA --}}
                        <div class="mb-6">
                            <label for="nama_kriteria" class="block text-sm font-medium text-gray-700 mb-1">Nama Kriteria</label>
                            <input type="text"
                                   id="nama_kriteria"
                                   name="nama_kriteria"
                                   class="block w-full px-3 py-2 border @error('nama_kriteria') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-custom-purple focus:border-custom-purple sm:text-sm"
                                   value="{{ old('nama_kriteria', $kriteria->nama_kriteria) }}"
                                   placeholder="Masukkan Nama Kriteria">
                            @error('nama_kriteria')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- TIPE --}}
                        <div class="mb-6">
                            <label for="tipe" class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                            <select id="tipe"
                                    name="tipe"
                                    class="block w-full px-3 py-2 border @error('tipe') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-custom-purple focus:border-custom-purple sm:text-sm">
                                <option value="">-- Pilih Tipe --</option>
                                <option value="benefit" {{ old('tipe', $kriteria->tipe) == 'benefit' ? 'selected' : '' }}>Benefit</option>
                                <option value="cost" {{ old('tipe', $kriteria->tipe) == 'cost' ? 'selected' : '' }}>Cost</option>
                            </select>
                            @error('tipe')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- BOBOT --}}
                        <div class="mb-6">
                            <label for="bobot" class="block text-sm font-medium text-gray-700 mb-1">Bobot</label>
                            <input type="number"
                                   id="bobot"
                                   name="bobot"
                                   step="any" {{-- Memungkinkan input desimal --}}
                                   class="block w-full px-3 py-2 border @error('bobot') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-custom-purple focus:border-custom-purple sm:text-sm"
                                   value="{{ old('bobot', $kriteria->bobot) }}"
                                   placeholder="Masukkan Nilai Bobot (misal: 0.25, 1, 5)">
                            @error('bobot')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- TOMBOL AKSI --}}
                        <div class="flex items-center space-x-3 mt-8">
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-custom-purple hover:bg-custom-purple-darker focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom-purple">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('kriterias.index') }}"
                               class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom-purple">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    {{-- </div> --}} {{-- Penutup untuk .content-kriteria-edit-form-wrapper jika digunakan --}}
@endsection

{{-- Bootstrap JS tidak lagi dibutuhkan karena form sudah full Tailwind --}}
{{-- @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush --}}