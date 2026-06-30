<aside id="sidebar" class="bg-sidebar-bg shadow-md flex-shrink-0 overflow-hidden transition-all">
    {{-- Div internal ini PENTING untuk menjaga lebar konten tetap w-64 saat sidebar beranimasi --}}
    <div class="w-64 h-full">
        <nav class="h-full overflow-y-auto pt-6 pb-4">
            <a href="{{ route('dashboard.index') }}" {{-- Diubah dari komentar menjadi route yang benar --}}
               class="flex items-center px-6 py-3 text-sidebar-text hover:bg-gray-100 hover:text-sidebar-text-hover {{ request()->routeIs('dashboard.index') || request()->is('dashboard') ? 'bg-sidebar-active-bg text-sidebar-active-text font-semibold border-l-4 border-custom-purple' : '' }}">
                <i class="fas fa-tachometer-alt w-6 mr-3"></i>
                Dashboard
            </a>

            <a href="{{ route('alternatifs.index') }}"
               class="flex items-center px-6 py-3 text-sidebar-text hover:bg-gray-100 hover:text-sidebar-text-hover {{ request()->is('alternatifs*') ? 'bg-sidebar-active-bg text-sidebar-active-text font-semibold border-l-4 border-custom-purple' : '' }}">
                {{-- Icon dari gambar Anda adalah user, tapi Font Awesome fas fa-users mungkin lebih cocok untuk 'data alternatif' secara umum --}}
                <i class="fas fa-users w-6 mr-3"></i> {{-- Icon sebelumnya: fas fa-user --}}
                Data Alternatif
            </a>

            {{-- Link untuk Data Kriteria --}}
            <a href="{{ route('kriterias.index') }}"
               class="flex items-center px-6 py-3 text-sidebar-text hover:bg-gray-100 hover:text-sidebar-text-hover {{ request()->is('kriterias*') ? 'bg-sidebar-active-bg text-sidebar-active-text font-semibold border-l-4 border-custom-purple' : '' }}">
                <i class="fas fa-table w-6 mr-3"></i> {{-- Icon dari gambar (mirip grid/table) --}}
                Data Kriteria
            </a>

            {{-- Link untuk Data Nilai --}}
            <a href="{{ route('nilais.index') }}"
                class="flex items-center px-6 py-3 text-sidebar-text hover:bg-gray-100 hover:text-sidebar-text-hover {{ request()->is('nilais*') ? 'bg-sidebar-active-bg text-sidebar-active-text font-semibold border-l-4 border-custom-purple' : '' }}">
                <i class="fas fa-check-square w-6 mr-3"></i> {{-- Icon dari gambar --}}
                Data Nilai
            </a>

            {{-- Link untuk Seleksi Metode UTA --}}
             <a href="{{ route('uta.index') }}"
                class="flex items-center px-6 py-3 text-sidebar-text hover:bg-gray-100 hover:text-sidebar-text-hover {{ request()->routeIs('uta.index') || request()->routeIs('uta.process') ? 'bg-sidebar-active-bg text-sidebar-active-text font-semibold border-l-4 border-custom-purple' : '' }}">
                <i class="fas fa-calendar-alt w-6 mr-3"></i>
                Seleksi Metode UTA
             </a>

            <a href="#" {{-- Ganti dengan route('data.admin.index') jika ada --}}
               class="flex items-center px-6 py-3 text-sidebar-text hover:bg-gray-100 hover:text-sidebar-text-hover {{ request()->is('data-admin*') ? 'bg-sidebar-active-bg text-sidebar-active-text font-semibold border-l-4 border-custom-purple' : '' }}">
                <i class="fas fa-user-cog w-6 mr-3"></i> {{-- Icon dari gambar (user dengan gear/cog) --}}
                Data Admin
            </a>
        </nav>
    </div>
</aside>