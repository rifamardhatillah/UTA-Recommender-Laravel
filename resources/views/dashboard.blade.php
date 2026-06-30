@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@push('styles')
<style>
    .main-content {
        min-height: calc(100vh - 100px); /* Sesuaikan dengan tinggi header+footer */
    }
    .gradient-bg-welcome {
        /* Anda bisa membuat kelas ini di tailwind.config.js atau gunakan kelas utilitas langsung */
        background: linear-gradient(135deg, #60a5fa 0%, #4f46e5 100%); /* sky-400 to indigo-600 (contoh) */
        /* Jika pakai kelas Tailwind: bg-gradient-to-br from-sky-400 to-indigo-600 */
    }
    .card-hover-effect {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    .card-hover-effect:hover {
        transform: translateY(-6px); /* Sedikit lebih terangkat */
        box-shadow: 0 12px 24px rgba(0,0,0,0.12); /* Bayangan lebih jelas */
    }
    .icon-bg-circle {
        width: 56px; /* Sedikit lebih kecil agar pas dengan padding kartu */
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(255, 255, 255, 0.1); /* Lebih subtle */
        box-shadow: 0 2px 4px rgba(0,0,0,0.1) inset; /* Inner shadow tipis */
    }
    .animated-text-gradient {
        background: linear-gradient(90deg, #a78bfa, #ec4899, #f59e0b, #a78bfa); /* violet, pink, orange */
        background-size: 250% 100%; /* Sesuaikan untuk kecepatan animasi */
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: gradient-text-flow 8s ease infinite;
    }
    @keyframes gradient-text-flow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
</style>
@endpush

@section('content')
{{-- Konten utama TANPA background abu-abu muda --}}
<div class="main-content">

    {{-- Bagian Header Sambutan --}}
    <div class="gradient-bg-welcome text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <h1 class="text-3xl sm:text-4xl font-bold tracking-tight mb-2">
                Selamat Datang Kembali, <span class="font-extrabold">Administrator</span>!
            </h1>
            <p class="text-base sm:text-lg text-indigo-100 opacity-90">
                Pantau statistik kunci dan kelola operasional sistem dari sini.
            </p>
        </div>
    </div>

    {{-- Kontainer Utama untuk Konten Dashboard --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex flex-col lg:flex-row lg:space-x-8 space-y-8 lg:space-y-0">

            {{-- Kolom Kiri: Ilustrasi Ayam Geprek (Card warna putih, TANPA BORDER) --}}
            <div class="lg:w-5/12 xl:w-2/5 w-full bg-white dark:bg-white p-6 rounded-xl shadow-lg flex justify-center items-center">
                <img src="{{ asset('images/rifa.jpeg') }}"
                     alt="Ayam Geprek Illustration"
                     class="max-w-full h-auto object-contain rounded-md"
                     style="max-height: 380px;">
            </div>

            {{-- Kolom Kanan: Kartu Statistik --}}
            <div class="lg:w-7/12 xl:w-3/5 w-full grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Kartu Jumlah Alternatif --}}
                <div class="card-hover-effect bg-gradient-to-tr from-blue-500 to-sky-400 dark:from-blue-600 dark:to-sky-500 text-white p-6 rounded-xl shadow-md flex flex-col justify-between min-h-[170px]">
                    <div class="flex justify-between items-start">
                        <h2 class="text-sm font-medium uppercase tracking-wide">Total Alternatif</h2>
                        <div class="icon-bg-circle">
                            <i class="fas fa-cubes text-xl text-white opacity-80"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-4xl font-bold mt-2">{{ $jumlahAlternatif ?? 0 }}</p>
                        <p class="text-xs text-sky-100 dark:text-sky-200 mt-1">Entitas yang tersedia</p>
                    </div>
                </div>

                {{-- Kartu Alternatif Dinilai --}}
                <div class="card-hover-effect bg-gradient-to-tr from-purple-500 to-violet-500 dark:from-purple-600 dark:to-violet-600 text-white p-6 rounded-xl shadow-md flex flex-col justify-between min-h-[170px]">
                    <div class="flex justify-between items-start">
                        <h2 class="text-sm font-medium uppercase tracking-wide">Alternatif Dinilai</h2>
                        <div class="icon-bg-circle">
                            <i class="fas fa-check-double text-xl text-white opacity-80"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-4xl font-bold mt-2">{{ $jumlahAlternatifDinilai ?? 0 }}</p>
                        <p class="text-xs text-violet-100 dark:text-violet-200 mt-1">Siap untuk dianalisis</p>
                    </div>
                </div>

                {{-- Kartu Kriteria --}}
                <div class="card-hover-effect bg-gradient-to-tr from-pink-500 to-rose-500 dark:from-pink-600 dark:to-rose-600 text-white p-6 rounded-xl shadow-md flex flex-col justify-between min-h-[170px]">
                    <div class="flex justify-between items-start">
                        <h2 class="text-sm font-medium uppercase tracking-wide">Kriteria Penilaian</h2>
                        <div class="icon-bg-circle">
                            <i class="fas fa-tasks text-xl text-white opacity-80"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-4xl font-bold mt-2">{{ $jumlahKriteria ?? 0 }}</p>
                        <p class="text-xs text-rose-100 dark:text-rose-200 mt-1">{{ $deskripsiBobotKriteria ?? 'Info bobot tidak tersedia' }}</p>
                    </div>
                </div>

                {{-- Kartu Proses UTA --}}
                <div class="card-hover-effect bg-gradient-to-tr from-emerald-500 to-green-500 dark:from-emerald-600 dark:to-green-600 text-white p-6 rounded-xl shadow-md flex flex-col justify-between min-h-[170px]">
                     <div class="flex justify-between items-start">
                        <h2 class="text-sm font-medium uppercase tracking-wide">Analisis Metode UTA</h2>
                        <div class="icon-bg-circle">
                            <i class="fas fa-calculator text-xl text-white opacity-80"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-2xl font-semibold mt-2 animated-text-gradient">Mulai Perhitungan</p>
                        <a href="{{ route('uta.index') }}" class="inline-block text-xs bg-white/20 dark:bg-white/10 hover:bg-white/30 dark:hover:bg-white/20 px-3 py-1 rounded-full mt-2 transition-colors">
                            Proses Sekarang <i class="fas fa-arrow-right text-xxs ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script>
        console.log('Dashboard with ayamgeprek.png loaded!');
    </script>
@endpush