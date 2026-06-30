@extends('layouts.app')

@section('title', 'Perhitungan UTA') {{-- Judul bisa disesuaikan --}}

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .table th, .table td { padding: 0.5rem; vertical-align: middle; font-size: 0.875rem; }
        .table thead th { background-color: #f9fafb; font-weight: 600; color: #374151; text-transform: uppercase; letter-spacing: 0.05em; }
        .table tbody tr:hover { background-color: #f3f4f6; }
        .highlight-rank-1 { background-color: #d1fae5 !important; font-weight: bold; }
        .highlight-rank-2 { background-color: #e0f2fe !important; }
        .highlight-rank-3 { background-color: #ffedd5 !important; }
        .section-title { font-size: 1.125rem; font-weight: 600; color: #1f2937; margin-bottom: 0.75rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e5e7eb; }
        .sticky-header th { position: sticky; top: 0; z-index: 10; }
    </style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-12">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-xl font-semibold text-gray-800">Perhitungan Metode UTA</h3>
            <p class="text-sm text-gray-600 mt-1">Halaman ini menampilkan data awal, bobot kriteria, dan hasil perhitungan UTA.</p>
        </div>

        <div class="p-6">
            {{-- Notifikasi dari Controller --}}
            @if (session('error_message_uta'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-md shadow-sm" role="alert">
                    <div class="flex"><div class="py-1"><i class="fas fa-times-circle fa-lg text-red-500 mr-3"></i></div><div><p class="font-bold">Error!</p><p class="text-sm">{{ session('error_message_uta') }}</p></div></div>
                </div>
            @endif
            @if (session('success_message_uta') && !$errors->any() && !session('error_message_uta'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-md shadow-sm" role="alert">
                     <div class="flex"><div class="py-1"><i class="fas fa-check-circle fa-lg text-green-500 mr-3"></i></div><div><p class="font-bold">Sukses!</p><p class="text-sm">{{ session('success_message_uta') }}</p></div></div>
                </div>
            @endif

            {{-- Hanya tampilkan form input jika tidak ada error parah --}}
            @if ($kriterias->count() > 0 && $alternatifs->count() > 0)
                {{-- Bagian Data Nilai Keputusan Awal --}}
                <section class="mb-8">
                    <h4 class="section-title">Data Nilai Keputusan Awal</h4>
                    <div class="overflow-x-auto shadow-md sm:rounded-lg max-h-[400px]">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300 table">
                            <thead class="bg-gray-100 sticky-header">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left">Alternatif</th>
                                    @foreach ($kriterias as $kriteria)
                                        <th scope="col" class="px-4 py-3 text-center" title="Tipe: {{ ucfirst($kriteria->tipe) }}">
                                            {{ $kriteria->nama_kriteria }}
                                            <span class="block text-xs font-normal text-gray-500">({{ ucfirst($kriteria->tipe) }})</span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($alternatifs as $alternatif)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap font-medium">{{ $alternatif->nama }}</td>
                                        @foreach ($kriterias as $kriteria)
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                {{ $decisionMatrix[$alternatif->id][$kriteria->id] ?? '0' }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>

                {{-- Bagian Bobot Kriteria --}}
                <section class="mb-8">
                    <h4 class="section-title">Bobot Kriteria (dari Database)</h4>
                    <div class="overflow-x-auto shadow-md sm:rounded-lg">
                         <table class="min-w-full divide-y divide-gray-200 border border-gray-300 table">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left">Kriteria</th>
                                    <th scope="col" class="px-4 py-3 text-center">Tipe</th>
                                    <th scope="col" class="px-4 py-3 text-center">Bobot Asli (0-100)</th>
                                    <th scope="col" class="px-4 py-3 text-center">Bobot Ternormalisasi</th>
                                </tr>
                            </thead>
                             <tbody class="bg-white divide-y divide-gray-200">
                                @php $totalBobotAsli = 0; $totalBobotTernormalisasi = 0; @endphp
                                @foreach ($kriterias as $kriteria)
                                @php
                                    $bobotAsli = $kriteria->bobot ?? 0;
                                    $bobotTernormalisasi = $bobotAsli / 100;
                                    $totalBobotAsli += $bobotAsli;
                                    $totalBobotTernormalisasi += $bobotTernormalisasi;
                                @endphp
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap font-medium">{{ $kriteria->nama_kriteria }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">{{ ucfirst($kriteria->tipe) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">{{ $bobotAsli }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">{{ number_format($bobotTernormalisasi, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-100 font-semibold">
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-right">Total Bobot:</td>
                                    <td class="px-4 py-3 text-center">{{ $totalBobotAsli }}</td>
                                    <td class="px-4 py-3 text-center">{{ number_format($totalBobotTernormalisasi, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                         @if (abs($totalBobotTernormalisasi - 1.0) > 0.001 && $kriterias->count() > 0 && $totalBobotAsli != 100)
                            <p class="mt-3 text-sm text-yellow-700 bg-yellow-100 border border-yellow-300 p-3 rounded-md">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Perhatian: Total bobot ({{ $totalBobotAsli }}) tidak sama dengan 100.
                            </p>
                        @endif
                    </div>
                </section>

                {{-- Tombol Proses --}}
                <form action="{{ route('uta.calculate') }}" method="POST">
                    @csrf
                    <div class="flex justify-center mt-6 pt-6 border-t">
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-cogs -ml-1 mr-3"></i>Proses Perhitungan UTA
                        </button>
                    </div>
                </form>
            @elseif ($kriterias->isEmpty())
                <div class="text-center py-10"><i class="fas fa-folder-open fa-3x text-gray-400 mb-3"></i><p class="text-lg font-medium text-gray-700">Data Kriteria Tidak Ditemukan</p><p class="text-gray-500">Silakan <a href="{{ route('kriterias.index') }}" class="text-indigo-600 hover:underline">tambahkan kriteria</a> terlebih dahulu.</p></div>
            @elseif ($alternatifs->isEmpty())
                 <div class="text-center py-10"><i class="fas fa-folder-open fa-3x text-gray-400 mb-3"></i><p class="text-lg font-medium text-gray-700">Data Alternatif Tidak Ditemukan</p><p class="text-gray-500">Silakan <a href="{{ route('nilais.index') }}" class="text-indigo-600 hover:underline">input nilai untuk alternatif</a> terlebih dahulu.</p></div>
            @endif


            {{-- ========================================================================= --}}
            {{-- Bagian Hasil Perhitungan (Ditampilkan jika ada $calculationResults) --}}
            {{-- ========================================================================= --}}
            @if(isset($calculationResults) && !empty($calculationResults))
                <div class="mt-12 pt-8 border-t-2 border-indigo-500">
                    
                    <!-- [MODIFIKASI UTAMA DI SINI] -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800">Hasil Perhitungan Metode UTA</h3>
                        <a href="{{ route('uta.downloadPdf') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-file-pdf mr-2"></i>Download Laporan PDF
                        </a>
                    </div>
                    <!-- [AKHIR MODIFIKASI] -->

                    {{-- Variabel lokal untuk kemudahan akses --}}
                    @php
                        $hasilAlternatifs = collect($calculationResults['alternatifs_data']);
                        $hasilKriterias = collect($calculationResults['kriterias_data']);
                        $hasilNormalizedMatrix = $calculationResults['normalizedMatrix'];
                        $hasilAnalysisTable = $calculationResults['analysisTable'];
                        $hasilPartialUtilities = $calculationResults['partialUtilities'];
                        $hasilFinalScores = $calculationResults['finalScores'];
                    @endphp

                    {{-- 1. Matriks Ternormalisasi --}}
                    <section class="mb-8">
                        <h4 class="section-title">1. Matriks Ternormalisasi (Benefit/Cost)</h4>
                        <p class="text-xs text-gray-500 mb-2">Benefit: `nilai / max(kolom)`. Cost: `min(kolom) / nilai`.</p>
                        <div class="overflow-x-auto shadow-md sm:rounded-lg max-h-[400px]">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-300 table">
                                <thead class="bg-gray-100 sticky-header">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Alternatif</th>
                                        @foreach ($hasilKriterias as $kriteria)
                                            <th class="px-4 py-2 text-center">{{ $kriteria['nama'] }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($hasilAlternatifs as $alternatif)
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap font-medium">{{ $alternatif['nama'] }}</td>
                                            @foreach ($hasilKriterias as $kriteria)
                                                <td class="px-4 py-2 whitespace-nowrap text-center">
                                                    {{ isset($hasilNormalizedMatrix[$alternatif['id']][$kriteria['id']]) ? number_format($hasilNormalizedMatrix[$alternatif['id']][$kriteria['id']], 3) : '-' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>

                    {{-- 2. Tabel Analisis Peringkat --}}
                    <section class="mb-8">
                        <h4 class="section-title">2. Analisis Peringkat Kriteria</h4>
                        <p class="text-xs text-gray-500 mb-2">"Perbedaan Interval" = (G+ Ternormalisasi - G- Ternormalisasi) / Bobot Ternormalisasi.</p>
                        <div class="overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-300 table">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Kriteria</th>
                                        <th class="px-4 py-2 text-center">G+ (Max Norm.)</th>
                                        <th class="px-4 py-2 text-center">G- (Min Norm.)</th>
                                        <th class="px-4 py-2 text-center">Bobot Ternormalisasi</th>
                                        <th class="px-4 py-2 text-center">Perbedaan Interval</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($hasilKriterias as $kriteria)
                                        @php $analysisData = $hasilAnalysisTable[$kriteria['id']] ?? null; @endphp
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap font-medium">{{ $kriteria['nama'] }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-center">{{ $analysisData ? number_format($analysisData['g_plus'], 3) : '-' }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-center">{{ $analysisData ? number_format($analysisData['g_minus'], 3) : '-' }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-center">{{ $analysisData ? number_format($analysisData['jumlah_interval_bobot'], 2) : '-' }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-center">{{ $analysisData ? number_format($analysisData['perbedaan_interval'], 3) : '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>

                    {{-- 3. Nilai Utilitas Parsial --}}
                    <section class="mb-8">
                        <h4 class="section-title">3. Nilai Utilitas Parsial</h4>
                        <p class="text-xs text-gray-500 mb-2">Dihitung dari: Nilai Ternormalisasi * Perbedaan Interval.</p>
                         <div class="overflow-x-auto shadow-md sm:rounded-lg max-h-[400px]">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-300 table">
                                <thead class="bg-gray-100 sticky-header">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Alternatif</th>
                                        @foreach ($hasilKriterias as $kriteria)
                                            <th class="px-4 py-2 text-center">{{ $kriteria['nama'] }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($hasilAlternatifs as $alternatif)
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap font-medium">{{ $alternatif['nama'] }}</td>
                                            @foreach ($hasilKriterias as $kriteria)
                                                <td class="px-4 py-2 whitespace-nowrap text-center">
                                                    {{ isset($hasilPartialUtilities[$alternatif['id']][$kriteria['id']]) ? number_format($hasilPartialUtilities[$alternatif['id']][$kriteria['id']], 3) : '-' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>

                    {{-- 4. Hasil Akhir Perangkingan --}}
                    <section>
                        <h4 class="section-title">4. Hasil Akhir Perangkingan</h4>
                        <div class="overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-300 table">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-center">Peringkat</th>
                                        <th class="px-4 py-2 text-left">Alternatif</th>
                                        <th class="px-4 py-2 text-center">Nilai Utilitas Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($hasilFinalScores as $score)
                                        <tr class="
                                            @if($score['rank'] == 1) highlight-rank-1
                                            @elseif($score['rank'] == 2) highlight-rank-2
                                            @elseif($score['rank'] == 3) highlight-rank-3
                                            @endif
                                        ">
                                            <td class="px-4 py-2 whitespace-nowrap text-center font-bold">{{ $score['rank'] }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap font-medium">{{ $score['nama'] }}</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-center font-semibold">{{ number_format($score['total_utility'], 4) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-3 text-center text-red-500">Tidak ada data hasil perangkingan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            @endif
            {{-- Akhir Bagian Hasil Perhitungan --}}

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Optional: Scroll ke bagian hasil jika ada setelah reload
    document.addEventListener('DOMContentLoaded', function () {
        @if(isset($calculationResults) && !empty($calculationResults))
            const resultsSection = document.querySelector('.border-t-2.border-indigo-500');
            if (resultsSection) {
                // Cek apakah ada pesan sukses, jika ya, baru scroll
                @if(session('success_message_uta'))
                    resultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                @endif
            }
        @endif
    });
</script>
@endpush