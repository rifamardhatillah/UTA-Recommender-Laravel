{{-- resources/views/alternatifs/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Alternatif - ' . $alternatif->nama)

@push('styles')
    {{--
        Catatan untuk Font:
        Sama seperti pada create.blade.php, jika layouts.app sudah mengatur font-family,
        style ini mungkin tidak diperlukan.
    --}}
    <style>
        .content-alternatif-edit-form-wrapper { /* Opsional wrapper class */
            font-family: 'Inter', sans-serif;
        }
    </style>
@endpush

@push('scripts')
    {{--
        Catatan Penting tentang Tailwind CSS (sama seperti pada create.blade.php):
        1. CARA IDEAL: Proyek Anda sudah terkonfigurasi dengan Tailwind CSS secara global.
           Jika demikian, Anda TIDAK PERLU menyertakan CDN atau konfigurasi inline di bawah.
           Warna kustom ('custom-purple', dll.) juga sebaiknya ada di file `tailwind.config.js` utama.

        2. JIKA BELUM DISETUP SECARA GLOBAL (untuk contoh ini atau isolasi):
           Gunakan CDN dan konfigurasi inline. Hati-hati dengan konflik jika `layouts.app`
           juga mencoba memuat atau mengkonfigurasi Tailwind.
    --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Pastikan konfigurasi ini tidak menimpa atau berkonflik
        // dengan konfigurasi Tailwind global jika ada.
        window.tailwind = window.tailwind || {}; // Pastikan window.tailwind ada
        window.tailwind.config = {
            ...(window.tailwind.config || {}), // Gabungkan dengan config yang mungkin sudah ada
            theme: {
                ...(window.tailwind.config?.theme || {}),
                extend: {
                    ...(window.tailwind.config?.theme?.extend || {}),
                    colors: {
                        ...(window.tailwind.config?.theme?.extend?.colors || {}), // Pertahankan warna kustom lain
                        'custom-purple': '#435ebe',
                        'custom-purple-darker': '#3a50a0',
                        'custom-red': '#dc3545',
                        // 'custom-yellow' dan 'custom-yellow-darker' tidak ada di file edit asli,
                        // jadi saya tidak menambahkannya di sini kecuali Anda memerlukannya.
                    }
                }
            }
        };
    </script>
@endpush

@section('content')
    {{--
        Background `body class="bg-gray-100"` dari file asli telah dihapus.
        Background halaman sekarang akan mengikuti apa yang diatur oleh `layouts.app`.
        Jika Anda ingin area konten ini memiliki background `bg-gray-100`,
        bungkus konten form di bawah ini dengan sebuah div, contohnya:
        <div class="bg-gray-100 py-8"> ... konten form ... </div>
    --}}

    {{-- Opsional wrapper untuk style khusus, seperti font --}}
    {{-- <div class="content-alternatif-edit-form-wrapper"> --}}

        {{-- Struktur HTML asli dari form edit Anda yang menggunakan Tailwind CSS --}}
        <div class="container mx-auto px-4 mt-8 mb-8">
            <div class="max-w-2xl mx-auto"> {{-- Batasi lebar form --}}
                <div class="bg-white shadow-lg rounded-lg p-6 md:p-8">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-6">Edit Data Alternatif</h3>

                    <form action="{{ route('alternatifs.update', $alternatif->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Alternatif</label>
                            <input type="text"
                                   name="nama"
                                   id="nama"
                                   class="block w-full px-3 py-2 border @error('nama') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-custom-purple focus:border-custom-purple sm:text-sm"
                                   value="{{ old('nama', $alternatif->nama) }}"
                                   required />
                            @error('nama')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tambahkan field lain di sini jika perlu diedit --}}

                        <div class="flex items-center space-x-3 mt-8">
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-custom-purple hover:bg-custom-purple-darker focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom-purple">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('alternatifs.index') }}"
                               class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom-purple">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    {{-- </div> --}} {{-- Penutup untuk .content-alternatif-edit-form-wrapper jika digunakan --}}
@endsection

{{--
    Bootstrap JS tidak diperlukan untuk form ini karena sudah menggunakan Tailwind
    dan tidak ada komponen JS dari Bootstrap yang terlihat.
    @push('scripts') sudah digunakan untuk Tailwind CDN dan konfigurasinya.
--}}