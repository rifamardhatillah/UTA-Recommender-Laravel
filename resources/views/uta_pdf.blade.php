<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Laporan Hasil Perhitungan UTA</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .container { width: 100%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; font-size: 14px; }
        
        .section-title { font-size: 16px; font-weight: bold; color: #000; margin-top: 25px; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 2px solid #333; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
        table th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
        table td.text-center { text-align: center; }
        table td.font-bold { font-weight: bold; }
        
        .rank-1 { background-color: #e6ffed !important; }
        .rank-2 { background-color: #e0f2fe !important; }
        .rank-3 { background-color: #fffbeb !important; }
        
        .footer { text-align: center; font-size: 10px; color: #777; position: fixed; bottom: 0; width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Laporan Hasil Perhitungan</h1>
            <p>Metode UTA (UTilités Additives)</p>
            <p>Tanggal Cetak: {{ date('d F Y') }}</p>
        </div>

        @if(isset($calculationResults) && !empty($calculationResults))
            @php
                $hasilAlternatifs = collect($calculationResults['alternatifs_data']);
                $hasilKriterias = collect($calculationResults['kriterias_data']);
                $hasilOriginalMatrix = $calculationResults['originalDecisionMatrix'];
                $hasilNormalizedMatrix = $calculationResults['normalizedMatrix'];
                $hasilAnalysisTable = $calculationResults['analysisTable'];
                $hasilPartialUtilities = $calculationResults['partialUtilities'];
                $hasilFinalScores = $calculationResults['finalScores'];
            @endphp
            
            {{-- 1. Matriks Ternormalisasi --}}
            <h3 class="section-title">1. Matriks Ternormalisasi</h3>
            <table>
                <thead>
                    <tr>
                        <th>Alternatif</th>
                        @foreach ($hasilKriterias as $kriteria)
                            <th>{{ $kriteria['nama'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hasilAlternatifs as $alternatif)
                        <tr>
                            <td>{{ $alternatif['nama'] }}</td>
                            @foreach ($hasilKriterias as $kriteria)
                                <td class="text-center">
                                    {{ isset($hasilNormalizedMatrix[$alternatif['id']][$kriteria['id']]) ? number_format($hasilNormalizedMatrix[$alternatif['id']][$kriteria['id']], 3) : '-' }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- 2. Analisis Peringkat Kriteria --}}
            <h3 class="section-title">2. Analisis Peringkat Kriteria</h3>
            <table>
                <thead>
                    <tr>
                        <th>Kriteria</th>
                        <th>G+ (Max Norm.)</th>
                        <th>G- (Min Norm.)</th>
                        <th>Bobot Ternormalisasi</th>
                        <th>Perbedaan Interval (Pengali)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hasilKriterias as $kriteria)
                        @php $analysisData = $hasilAnalysisTable[$kriteria['id']] ?? null; @endphp
                        <tr>
                            <td>{{ $kriteria['nama'] }}</td>
                            <td class="text-center">{{ $analysisData ? number_format($analysisData['g_plus'], 3) : '-' }}</td>
                            <td class="text-center">{{ $analysisData ? number_format($analysisData['g_minus'], 3) : '-' }}</td>
                            <td class="text-center">{{ $analysisData ? number_format($analysisData['jumlah_interval_bobot'], 2) : '-' }}</td>
                            <td class="text-center">{{ $analysisData ? number_format($analysisData['perbedaan_interval'], 3) : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            {{-- Tambahkan tabel lain jika perlu (Partial Utilities, dll) --}}

            {{-- 4. Hasil Akhir Perangkingan --}}
            <h3 class="section-title">Hasil Akhir Perangkingan</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%;">Peringkat</th>
                        <th style="width: 55%;">Alternatif</th>
                        <th style="width: 30%;">Nilai Utilitas Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hasilFinalScores as $score)
                        <tr class="@if($score['rank'] == 1) rank-1 @elseif($score['rank'] == 2) rank-2 @elseif($score['rank'] == 3) rank-3 @endif">
                            <td class="text-center font-bold">{{ $score['rank'] }}</td>
                            <td>{{ $score['nama'] }}</td>
                            <td class="text-center font-bold">{{ number_format($score['total_utility'], 4) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data hasil.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: red;">Gagal memuat data perhitungan.</p>
        @endif
    </div>

    <div class="footer">
        Dicetak oleh Sistem Pendukung Keputusan
    </div>
</body>
</html>