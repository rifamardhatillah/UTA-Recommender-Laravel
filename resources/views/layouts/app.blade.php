<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'SPK Metode UTA')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-yellow': '#f59e0b',
                        'custom-purple': '#4f46e5', // Warna utama untuk branding/aktif
                        'custom-purple-dark': '#4338ca',
                        'custom-green': '#10b981',
                        'custom-green-dark': '#059669',
                        'custom-red': '#ef4444',
                        'custom-red-dark': '#dc2626',
                        'sidebar-bg': '#ffffff', // Background sidebar
                        'sidebar-text': '#374151', // Teks item sidebar normal
                        'sidebar-text-hover': '#4f46e5', // Teks item sidebar saat hover
                        'sidebar-active-bg': '#eef2ff', // Background item sidebar aktif
                        'sidebar-active-text': '#4f46e5', // Teks item sidebar aktif
                        'navbar-bg': '#ffffff', // Background navbar
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Pastikan font ini tersedia atau ganti */
        }

        .sort-icon-group {
            line-height: 0.8;
            display: inline-flex;
            flex-direction: column;
            vertical-align: middle;
        }

        .sort-icon-group .fa-sort-up {
            margin-bottom: -4px;
        }

        /* Styling scrollbar (opsional, untuk konsistensi visual) */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            /* Warna thumb scrollbar */
            border-radius: 10px;
        }

        ::-webkit-scrollbar-track {
            background-color: #f1f5f9;
            /* Warna track scrollbar */
        }
    </style>
    @stack('styles') <!-- Untuk style tambahan per halaman -->
</head>

<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    <!-- Navbar Atas -->
    @include('partials.navbar')

    <!-- Area Utama di Bawah Navbar (Sidebar Kiri, Konten Kanan) -->
    <div class="flex flex-1 overflow-hidden"> <!-- flex-1 mengisi sisa tinggi, overflow-hidden untuk child -->

        <!-- Sidebar Kiri (dikontrol oleh JavaScript) -->
        @include('partials.sidebar')

        <!-- Area Konten Utama (Kanan, Scrollable) -->
        <main class="flex-1 bg-gray-100 overflow-y-auto overflow-x-hidden">
            {{-- Container untuk padding dan max-width konten di dalam area scrollable --}}
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                @yield('content')
            </div>
        </main>

    </div>

    <!-- Footer Aplikasi (di paling bawah) -->
    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts') <!-- Untuk script tambahan per halaman -->

    {{-- SCRIPT UNTUK HAMBURGER MENU & SIDEBAR TOGGLE --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerButton = document.getElementById('hamburger-button'); // ID tombol hamburger di navbar
            const sidebar = document.getElementById('sidebar'); // ID elemen <aside> sidebar

            if (hamburgerButton && sidebar) {
                // Fungsi untuk toggle sidebar
                function toggleSidebar() {
                    // Toggle kelas lebar antara 0 dan lebar default sidebar (w-64)
                    sidebar.classList.toggle('w-0');
                    sidebar.classList.toggle('w-64'); // Pastikan kelas w-64 ada di sidebar saat visible

                    // Opsional: Simpan status sidebar di localStorage
                    if (sidebar.classList.contains('w-64')) {
                        localStorage.setItem('sidebarOpen', 'true');
                    } else {
                        localStorage.setItem('sidebarOpen', 'false');
                    }
                }

                // Opsional: Cek status sidebar dari localStorage saat halaman dimuat
                // Ini akan membuat sidebar tetap terbuka/tertutup sesuai pilihan terakhir user
                if (localStorage.getItem('sidebarOpen') === 'true') {
                    sidebar.classList.remove('w-0');
                    sidebar.classList.add('w-64');
                } else {
                    // Defaultnya tertutup jika tidak ada di localStorage atau false
                    sidebar.classList.add('w-0');
                    sidebar.classList.remove('w-64');
                }

                // Tambahkan event listener ke tombol hamburger
                hamburgerButton.addEventListener('click', function() {
                    toggleSidebar();
                });
            }
        });
    </script>
    {{-- AKHIR SCRIPT HAMBURGER MENU --}}

</body>

</html>