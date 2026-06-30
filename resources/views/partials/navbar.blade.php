<header class="bg-navbar-bg shadow-sm">
    <div class="container mx-auto px-4 sm:px-6 py-3"> {{-- Mengembalikan py-3 agar tidak terlalu tinggi --}}
        <div class="flex items-center">
            {{-- Kiri: Hamburger (mobile) dan Judul Utama Aplikasi --}}
            <div class="flex items-center">
                {{-- Tombol hamburger --}}
                <button id="hamburger-button" class="text-gray-700 hover:text-custom-purple focus:outline-none mr-3 p-1 rounded hover:bg-gray-200"> {{-- Beri ID & sedikit styling --}}
                    <i class="fas fa-bars text-xl"></i> {{-- Perbesar ikon --}}
                </button>

                {{-- Judul Utama Aplikasi --}}
                <a href="{{-- route('dashboard') --}}" class="flex items-center">
                    <span class="text-xl font-semibold text-custom-purple">SPK Metode UTA Kelompok 3</span> {{-- Menggunakan text-custom-purple seperti di gambar --}}
                </a>
            </div>
        </div>
    </div>
</header>