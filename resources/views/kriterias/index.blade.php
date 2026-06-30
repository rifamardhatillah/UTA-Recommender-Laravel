{{-- resources/views/kriterias/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Kriteria (Kriteria Penilaian)')

@push('styles')
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{--
    <style>
        body { font-family: 'Inter', sans-serif; } /* Assuming layouts.app or global CSS handles base font */
    </style>
    --}}
@endpush

@section('content')
    {{--
        Script for Custom Tailwind Colors.
        This assumes your layouts.app loads the main Tailwind script (e.g., from CDN) in the <head>.
        This script then extends or defines the tailwind.config.
        Ideally, these colors should be in your project's main tailwind.config.js file.
    --}}
    <script>
        // Ensure this runs to configure Tailwind, assuming the main Tailwind script is already loaded.
        // This will merge with any existing Tailwind config or create one.
        window.tailwind = window.tailwind || {};
        window.tailwind.config = {
            ...(window.tailwind.config || {}), // Preserve existing global config if any
            theme: {
                ...(window.tailwind.config?.theme || {}),
                extend: {
                    ...(window.tailwind.config?.theme?.extend || {}),
                    colors: {
                        ...(window.tailwind.config?.theme?.extend?.colors || {}), // Preserve other extended colors
                        'custom-purple': '#4f46e5',
                        'custom-purple-dark': '#4338ca',
                        'custom-green': '#10b981',
                        'custom-green-dark': '#059669',
                        'custom-red': '#ef4444',
                        'custom-red-dark': '#dc2626',
                    }
                }
            }
        };
    </script>

    {{-- Container styling matched with your 'alternatif' example --}}
    <div class="max-w-6xl mx-auto px-4 mt-8 mb-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-700">Data Kriteria <span class="text-sm text-gray-500">(Kriteria Penilaian)</span></h4>
            </div>

            <div class="p-6">
                {{-- FORM PENCARIAN --}}
                <form action="{{ route('kriterias.index') }}" method="GET" class="mb-6">
                    <div class="flex flex-wrap items-center justify-start mb-6 gap-4">
                        <div class="flex items-center">
                            <label for="search" class="text-sm text-gray-700 mr-2">Pencarian:</label>
                            <input type="search"
                                   id="search"
                                   name="search"
                                   class="form-input block w-auto sm:w-64 px-3 py-1.5 text-sm text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded-md transition ease-in-out m-0 focus:outline-none focus:border-blue-500" placeholder="Cari Nama, Tipe, Bobot..."
                                   value="{{ request('search') }}">
                            <button type="submit" class="ml-2 bg-custom-purple hover:bg-custom-purple-dark text-white font-semibold py-1.5 px-3 text-sm rounded-md shadow-sm">
                                <i class="fas fa-search fa-sm"></i> <span class="hidden sm:inline ml-1">Cari</span>
                            </button>
                             @if(request('search'))
                             <a href="{{ route('kriterias.index') }}"
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
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200" id="kriteriaTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[5%]">No.</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kriteria</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[100px]">
                                    <a href="{{ route('kriterias.create') }}" class="inline-flex items-center justify-center p-2 bg-custom-purple hover:bg-custom-purple-dark text-white rounded-md shadow-sm" title="Tambah Kriteria Baru">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($kriterias as $kriteria)
                                <tr class="hover:bg-gray-50">
                                    {{-- Using loop iteration similar to alternatif example for pagination numbering --}}
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $loop->iteration + ($kriterias->currentPage() - 1) * $kriterias->perPage() }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $kriteria->nama_kriteria }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $kriteria->tipe }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $kriteria->bobot }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 text-center">
                                        <form onsubmit="return confirm('Apakah Anda Yakin ingin menghapus data ini?');" action="{{ route('kriterias.destroy', $kriteria->id) }}" method="POST" class="inline-block">
                                            <a href="{{ route('kriterias.edit', $kriteria->id) }}" class="bg-custom-green hover:bg-custom-green-dark text-white p-0 w-7 h-7 inline-flex items-center justify-center rounded text-xs mr-1 shadow-sm" title="Edit">
                                                <i class="fas fa-edit fa-fw"></i>
                                            </a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-custom-red hover:bg-custom-red-dark text-white p-0 w-7 h-7 inline-flex items-center justify-center rounded text-xs shadow-sm" title="Hapus">
                                                <i class="fas fa-trash-alt fa-fw"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-center text-red-500 text-sm">
                                        @if(request('search'))
                                            Tidak ada data kriteria yang cocok dengan pencarian "{{ request('search') }}".
                                        @else
                                            Data Kriteria belum ada.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- BAGIAN PAGINATION --}}
                <div class="mt-6">
                    @if ($kriterias->hasPages())
                        {{-- Appending query strings to maintain search filters during pagination --}}
                        {{ $kriterias->appends(request()->query())->links() }}
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