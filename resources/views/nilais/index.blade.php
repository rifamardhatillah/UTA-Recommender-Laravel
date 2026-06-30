{{-- resources/views/nilais/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Data Nilai Alternatif')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush

@section('content')
    {{-- Script for Custom Tailwind Colors (jika belum terpusat) --}}
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

    <div class="max-w-7xl mx-auto px-4 mt-8 mb-8"> {{-- Max width diperbesar jika perlu --}}
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-700">Data Nilai Alternatif</h4>
            </div>

            <div class="p-6">
                {{-- FORM PENCARIAN --}}
                <form action="{{ route('nilais.index') }}" method="GET" class="mb-6">
                    <div class="flex flex-wrap items-center justify-start mb-6 gap-4">
                        <div class="flex items-center">
                            <label for="search" class="text-sm text-gray-700 mr-2">Cari Alternatif:</label>
                            <input type="search"
                                   id="search"
                                   name="search"
                                   class="form-input block w-auto sm:w-64 px-3 py-1.5 text-sm text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded-md transition ease-in-out m-0 focus:outline-none focus:border-blue-500"
                                   placeholder="Nama Alternatif..."
                                   value="{{ request('search') }}">
                            <button type="submit" class="ml-2 bg-custom-purple hover:bg-custom-purple-dark text-white font-semibold py-1.5 px-3 text-sm rounded-md shadow-sm">
                                <i class="fas fa-search fa-sm"></i> <span class="hidden sm:inline ml-1">Cari</span>
                            </button>
                             @if(request('search'))
                             <a href="{{ route('nilais.index') }}"
                                class="ml-2 text-sm text-gray-600 border border-gray-300 bg-gray-100 px-2 py-1.5 rounded hover:bg-gray-200 hover:border-gray-400 transition duration-200 ease-in-out flex items-center gap-1"
                                title="Hapus filter pencarian">
                                    <i class="fas fa-times fa-sm"></i>
                                    <span class="hidden sm:inline">Reset</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </form>

                {{-- Notifikasi standar dihapus, akan digantikan SweetAlert di @push('scripts') --}}
                {{--
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative" role="alert">
                        <strong class="font-bold">Berhasil!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded relative" role="alert">
                        <strong class="font-bold">Gagal!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                --}}

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200" id="nilaiTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[5%]">No.</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alternatif</th>
                                @if($kriterias->count() > 0)
                                    @foreach ($kriterias as $kriteria)
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $kriteria->nama_kriteria }}</th>
                                    @endforeach
                                @else
                                     <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                                @endif
                                {{-- Tombol Tambah di header kolom aksi telah dipindah ke atas, ini bisa jadi Aksi saja --}}
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[100px]">
                                    <a href="{{ route('nilais.create') }}" class="inline-flex items-center justify-center p-2 bg-custom-purple hover:bg-custom-purple-dark text-white rounded-md shadow-sm" title="Tambah Nilai Baru">
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
                                    @if($kriterias->count() > 0)
                                        @foreach ($kriterias as $kriteria)
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                                {{-- Ambil nilai dari array $alternatifNilais yang sudah disiapkan --}}
                                                {{ $alternatifNilais[$alternatif->id][$kriteria->id] ?? '-' }}
                                            </td>
                                        @endforeach
                                    @else
                                         <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">-</td>
                                    @endif
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 text-center">
                                        <a href="{{ route('nilais.edit', $alternatif->id) }}" class="bg-custom-green hover:bg-custom-green-dark text-white p-0 w-7 h-7 inline-flex items-center justify-center rounded text-xs mr-1 shadow-sm" title="Edit Nilai untuk {{ $alternatif->nama }}">
                                            <i class="fas fa-edit fa-fw"></i>
                                        </a>
                                        <form onsubmit="return confirmDelete(event, '{{ $alternatif->nama }}')" action="{{ route('nilais.destroy', $alternatif->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-custom-red hover:bg-custom-red-dark text-white p-0 w-7 h-7 inline-flex items-center justify-center rounded text-xs shadow-sm" title="Hapus Semua Nilai untuk {{ $alternatif->nama }}">
                                                <i class="fas fa-trash-alt fa-fw"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 2 + $kriterias->count() + 1 }}" class="px-4 py-3 text-center text-red-500 text-sm">
                                        @if(request('search'))
                                            Tidak ada data alternatif yang cocok dengan pencarian "{{ request('search') }}".
                                        @elseif($kriterias->count() == 0)
                                            Data Kriteria belum ada. Silakan tambahkan kriteria terlebih dahulu.
                                        @else
                                            Data Nilai belum ada atau tidak ada alternatif yang memiliki nilai. <a href="{{ route('nilais.create') }}" class="text-custom-purple hover:underline">Tambahkan sekarang?</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

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