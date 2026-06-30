{{-- resources/views/alternatifs/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Tambah Alternatif Baru')

@push('styles')
    {{--
        Catatan untuk Font:
        Jika layouts.app Anda sudah mengatur font-family secara global (misalnya 'Inter'),
        maka style di bawah ini mungkin tidak diperlukan.
        Jika belum, atau Anda ingin memastikan font ini khusus untuk konten ini, Anda bisa menyertakannya.
    --}}
    <style>
        /* Mengaplikasikan font ke wrapper konten jika diperlukan */
        .content-alternatif-form-wrapper {
            font-family: 'Inter', sans-serif;
        }
        /* Atau jika Anda yakin layouts.app tidak mengatur font,
           Anda bisa lebih agresif, tapi biasanya tidak disarankan:
           body { font-family: 'Inter', sans-serif !important; }
        */
    </style>
@endpush

@push('scripts')
    {{--
        Catatan Penting tentang Tailwind CSS:
        1. CARA IDEAL:
           Proyek Laravel Anda sudah terkonfigurasi dengan Tailwind CSS (melalui npm install, postcss, dan file tailwind.config.js).
           Jika demikian, `layouts.app` Anda seharusnya sudah memuat file CSS Tailwind yang telah di-compile.
           Dalam kasus itu, Anda TIDAK PERLU menyertakan CDN Tailwind atau konfigurasi inline di bawah ini.
           Semua warna kustom ('custom-purple', dll.) juga sebaiknya sudah didefinisikan di file `tailwind.config.js` utama proyek Anda.

        2. JIKA BELUM DISETUP SECARA GLOBAL (untuk contoh ini atau isolasi):
           Anda dapat menggunakan CDN dan konfigurasi inline seperti di bawah ini.
           Namun, berhati-hatilah karena ini dapat menyebabkan konflik atau pemuatan ganda jika `layouts.app`
           juga mencoba memuat atau mengkonfigurasi Tailwind secara terpisah.
    --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Pastikan konfigurasi ini tidak menimpa atau berkonflik
        // dengan konfigurasi Tailwind global jika ada.
        // Idealnya, semua warna kustom dan ekstensi tema ada di file tailwind.config.js proyek.
        window.tailwind = window.tailwind || {}; // Pastikan window.tailwind ada
        window.tailwind.config = {
            ...(window.tailwind.config || {}), // Gabungkan dengan config yang mungkin sudah ada
            theme: {
                ...(window.tailwind.config?.theme || {}),
                extend: {
                    ...(window.tailwind.config?.theme?.extend || {}),
                    colors: {
                        ...(window.tailwind.config?.theme?.extend?.colors || {}), // Pertahankan warna kustom lain jika ada
                        'custom-purple': '#435ebe',
                        'custom-purple-darker': '#3a50a0',
                        'custom-red': '#dc3545',
                        'custom-yellow': '#ffc107',
                        'custom-yellow-darker': '#e0a800',
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
        Jika Anda ingin area konten ini memiliki background spesifik (misalnya `bg-gray-100`),
        Anda bisa membungkus seluruh konten di dalam `@section('content')` ini
        dengan sebuah div, contoh:
        <div class="bg-gray-100 py-8">
            ... (konten form di bawah) ...
        </div>
        Untuk saat ini, kita asumsikan `layouts.app` menangani background utama.
    --}}

    {{-- Wrapper opsional ini bisa digunakan jika Anda ingin menerapkan style spesifik
         seperti font-family dari @push('styles') hanya ke area ini. --}}
    {{-- <div class="content-alternatif-form-wrapper"> --}}

        {{-- Struktur HTML asli dari form create Anda yang menggunakan Tailwind CSS --}}
        {{-- Kelas-kelas Tailwind seperti 'container', 'mx-auto', 'shadow-lg', dll., akan tetap berfungsi --}}
        {{-- berkat CDN Tailwind yang dimuat di @push('scripts'). --}}
        <div class="container mx-auto px-4 mt-8 mb-8">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white shadow-lg rounded-lg p-6 md:p-8">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-6">Tambah Alternatif Baru</h3>

                    <form action="{{ route('alternatifs.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Alternatif</label>
                            <input type="text"
                                   id="nama"
                                   class="block w-full px-3 py-2 border @error('nama') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-custom-purple focus:border-custom-purple sm:text-sm"
                                   name="nama"
                                   value="{{ old('nama') }}"
                                   placeholder="Masukkan Nama Alternatif">

                            @error('nama')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Contoh:
                        <div class="mb-6">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">DESKRIPSI (Opsional)</label>
                            <textarea id="deskripsi" name="deskripsi" rows="3"
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-custom-purple focus:border-custom-purple sm:text-sm">{{ old('deskripsi') }}</textarea>
                        </div>
                        --}}

                        <div class="flex items-center space-x-3">
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-custom-purple hover:bg-custom-purple-darker focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom-purple">
                                SIMPAN
                            </button>
                            <button type="reset"
                                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom-purple">
                                RESET
                            </button>
                            <a href="{{ route('alternatifs.index') }}"
                               class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-custom-purple">
                                KEMBALI
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    {{-- </div> --}} {{-- Penutup untuk .content-alternatif-form-wrapper jika digunakan --}}
@endsection

{{--
    Bootstrap JS tidak diperlukan untuk form ini karena sudah menggunakan Tailwind
    dan tidak ada komponen JS dari Bootstrap yang terlihat.
    @push('scripts') sudah digunakan untuk Tailwind CDN dan konfigurasinya.
--}}