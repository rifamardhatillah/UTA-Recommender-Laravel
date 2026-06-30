@extends('layouts.app')

@section('title', 'Data Alternatif')

@section('content')
{{--
    Container asli: <div class="container mx-auto px-4 mt-8 mb-8">
    Container yang disesuaikan: Kita akan menggunakan max-width dan padding yang lebih spesifik.
    Anda bisa memilih max-width yang sesuai:
    - max-w-7xl (sekitar 1280px)
    - max-w-6xl (sekitar 1152px) - Pilihan baik untuk tabel
    - max-w-5xl (sekitar 1024px)
    - max-w-4xl (sekitar 896px)

    Untuk padding, px-4 sm:px-6 lg:px-8 adalah standar yang baik untuk responsif.
    Dan py-6 atau py-8 untuk padding vertikal.
    Kita akan coba kombinasi max-w-6xl dengan padding yang sudah ada (mt-8 mb-8 dan px-4) atau sedikit modifikasi.
--}}

{{-- Opsi 1: Menggunakan max-width dengan padding horizontal dari kode asli Anda dan margin vertikal dari kode asli Anda --}}
<div class="max-w-6xl mx-auto px-4 mt-8 mb-8">
    {{-- Anda juga bisa mencoba: max-w-5xl atau max-w-7xl --}}

    {{-- Opsi 2: Menggunakan max-w-6xl dengan padding standar Tailwind untuk konten --}}
    {{-- <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8"> --}}

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="text-lg font-semibold text-gray-700">Daftar Alternatif</h4>
        </div>

        <div class="p-6">
            {{-- Form untuk Pencarian Sisi Server (Tampilan Disesuaikan) --}}
            <form action="{{ route('alternatifs.index') }}" method="GET" class="mb-6">
                <div class="flex flex-wrap items-center justify-start mb-6 gap-4"> {{-- justify-start agar di kiri --}}
                    <div class="flex items-center">
                        <label for="search" class="text-sm text-gray-700 mr-2">Pencarian:</label>
                        <input type="search"
                            id="search"
                            name="search"
                            class="form-input block w-auto sm:w-64 px-3 py-1.5 text-sm text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded-md transition ease-in-out m-0 focus:outline-none focus:border-blue-500" placeholder="Cari Nama..."
                            value="{{ request('search') }}">
                        <button type="submit" class="ml-2 bg-custom-purple hover:bg-custom-purple-dark text-white font-semibold py-1.5 px-3 text-sm rounded-md shadow-sm">
                            <i class="fas fa-search fa-sm"></i> <span class="hidden sm:inline ml-1">Cari</span>
                        </button>
                        @if(request('search'))
                        <a href="{{ route('alternatifs.index') }}"
                            class="ml-2 text-sm text-gray-600 border border-gray-300 bg-gray-100 px-2 py-1.5 rounded hover:bg-gray-200 hover:border-gray-400 transition duration-200 ease-in-out flex items-center gap-1"
                            title="Hapus filter pencarian">
                            <i class="fas fa-times fa-sm"></i>
                            <span class="hidden sm:inline">Reset</span>
                        </a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                    <thead class="bg-gray-50">
                         <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[5%]">No.</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{-- MODIFIKASI DI SINI: Hapus span ikon dan class justify-between jika tidak ada item lain --}}
                                <div class="flex items-center">NAMA</div>
                            </th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[25%]">
                                <div class="flex items-center">TERDAFTAR</div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[100px]">
                                <a href="{{ route('alternatifs.create') }}" class="inline-flex items-center justify-center p-2 bg-custom-purple hover:bg-custom-purple-dark text-white rounded-md shadow-sm" title="Tambah Alternatif">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($alternatifs as $alternatif)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $loop->iteration + ($alternatifs->currentPage() - 1) * $alternatifs->perPage() }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $alternatif->nama }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($alternatif->terdaftar)->format('d/m/Y H:i:s') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 text-center">
                                <form onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" action="{{ route('alternatifs.destroy', $alternatif->id) }}" method="POST" class="inline-block">
                                    <a href="{{ route('alternatifs.edit', $alternatif->id) }}" class="bg-green-500 hover:bg-green-600 text-white p-0 w-7 h-7 inline-flex items-center justify-center rounded text-xs mr-1 shadow-sm" title="Edit"><i class="fas fa-edit fa-fw"></i></a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-0 w-7 h-7 inline-flex items-center justify-center rounded text-xs shadow-sm" title="Hapus"><i class="fas fa-trash-alt fa-fw"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-red-500 text-sm">
                                @if(request('search'))
                                Tidak ada data alternatif yang cocok dengan pencarian "{{ request('search') }}".
                                @else
                                Data Alternatif belum tersedia.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Bagian Pagination --}}
            <div class="mt-6">
                @if ($alternatifs->hasPages())
                {{ $alternatifs->appends(request()->query())->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- Pastikan SweetAlert2 library sudah dimuat. Bisa dari CDN atau NPM. --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Script untuk notifikasi SweetAlert2
        @if(session('success'))
            Swal.fire({
                icon: "success",
                title: "BERHASIL",
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000 // Durasi notifikasi dalam milidetik
            });
        @elseif(session('error'))
            Swal.fire({
                icon: "error",
                title: "GAGAL!",
                text: "{{ session('error') }}",
                showConfirmButton: true, // Mungkin lebih baik tampilkan tombol OK untuk error
                // timer: 3000 // Opsional, jika ingin auto close
            });
        @endif

        // Script untuk konfirmasi hapus dengan SweetAlert2
        function confirmDelete(event, alternativeName) {
            event.preventDefault(); // Mencegah form submit langsung
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Semua data nilai untuk alternatif '" + alternativeName + "' akan dihapus dan tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', // Merah (custom-red)
                cancelButtonColor: '#6b7280', // Abu-abu
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit(); // Lanjutkan submit form jika dikonfirmasi
                }
            });
        }
    </script>
@endpush