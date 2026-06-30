<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf; // <-- [DITAMBAHKAN] Import library PDF

class UtaController extends Controller
{
    /**
     * Menampilkan halaman utama UTA.
     */
    public function index()
    {
        $alternatifs = Alternatif::with('nilais.kriteria')->whereHas('nilais')->orderBy('nama')->get();
        $kriterias = Kriteria::orderBy('id')->get();

        $decisionMatrix = [];
        $calculationResults = null;

        if ($kriterias->isEmpty()) {
            Session::flash('error_message_uta', 'Tidak ada data kriteria. Silakan tambahkan kriteria terlebih dahulu.');
        } elseif ($alternatifs->isEmpty()) {
            Session::flash('error_message_uta', 'Tidak ada data nilai keputusan. Silakan input nilai untuk alternatif terlebih dahulu.');
        } else {
            foreach ($alternatifs as $alternatif) {
                $decisionMatrix[$alternatif->id] = [];
                foreach ($kriterias as $kriteria) {
                    $nilaiModel = $alternatif->nilais->where('kriteria_id', $kriteria->id)->first();
                    $decisionMatrix[$alternatif->id][$kriteria->id] = $nilaiModel ? (float)$nilaiModel->nilai : 0;
                }
            }
        }
        
        if (Session::has('uta_calculation_results')) {
            $calculationResults = Session::get('uta_calculation_results');
        }
        if (Session::has('error_message_uta_calc')) {
            Session::flash('error_message_uta', Session::get('error_message_uta_calc'));
        }
        if (Session::has('success_message_uta_calc')) {
            Session::flash('success_message_uta', Session::get('success_message_uta_calc'));
        }

        return view('uta.index', compact('alternatifs', 'kriterias', 'decisionMatrix', 'calculationResults'));
    }

    /**
     * [DIUBAH] Metode ini sekarang hanya memanggil fungsi perhitungan dan me-redirect.
     */
    public function calculate(Request $request)
    {
        $result = $this->performUtaCalculation();

        if ($result['type'] === 'error') {
            return redirect()->route('uta.index')->with('error_message_uta_calc', $result['message']);
        }

        return redirect()->route('uta.index')
            ->with('uta_calculation_results', $result['data'])
            ->with('success_message_uta_calc', $result['message']);
    }

    /**
     * [BARU] Metode untuk membuat dan mengunduh laporan PDF.
     */
    public function downloadPdf()
    {
        $result = $this->performUtaCalculation();

        if ($result['type'] === 'error') {
            return redirect()->route('uta.index')->with('error_message_uta', $result['message']);
        }

        $calculationResults = $result['data'];
        $fileName = 'Laporan-Hasil-UTA-' . date('Y-m-d_H-i-s') . '.pdf';

        $pdf = Pdf::loadView('uta_pdf', ['calculationResults' => $calculationResults]);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download($fileName);
    }

    /**
     * [BARU & UTAMA] Fungsi private yang berisi seluruh logika perhitungan UTA.
     * Semua logika dari fungsi calculate() lama Anda dipindahkan ke sini.
     */
    private function performUtaCalculation(): array
    {
        $kriterias = Kriteria::orderBy('id')->get();
        $alternatifs = Alternatif::with('nilais.kriteria')->whereHas('nilais')->orderBy('nama')->get();

        if ($kriterias->isEmpty() || $alternatifs->isEmpty()) {
            return ['type' => 'error', 'message' => 'Data kriteria atau alternatif tidak lengkap untuk perhitungan.'];
        }

        // --- LANGKAH PERSIAPAN DATA ---
        $decisionMatrix = [];
        $criteriaData = [];

        foreach ($kriterias as $kriteria) {
            if ($kriteria->bobot === null) {
                 return ['type' => 'error', 'message' => "Bobot untuk kriteria '{$kriteria->nama_kriteria}' belum diatur."];
            }
            if ($kriteria->bobot == 0) {
                 return ['type' => 'error', 'message' => "Bobot untuk kriteria '{$kriteria->nama_kriteria}' adalah nol. Metode UTA memerlukan bobot > 0."];
            }
            $criteriaData[$kriteria->id] = [
                'id' => $kriteria->id,
                'nama' => $kriteria->nama_kriteria,
                'bobot_original' => (float)$kriteria->bobot,
                'bobot_normalized' => (float)($kriteria->bobot / 100),
                'tipe' => strtolower($kriteria->tipe),
            ];
        }

        foreach ($alternatifs as $alternatif) {
            $decisionMatrix[$alternatif->id] = [];
            foreach ($kriterias as $kriteria) {
                $nilaiModel = $alternatif->nilais->where('kriteria_id', $kriteria->id)->first();
                $decisionMatrix[$alternatif->id][$kriteria->id] = $nilaiModel ? (float)$nilaiModel->nilai : 0;
            }
        }
        $originalDecisionMatrix = $decisionMatrix;

        // --- LANGKAH 1: NORMALISASI MATRIKS KEPUTUSAN (BENEFIT/COST) ---
        $normalizedMatrix = [];
        foreach ($kriterias as $kriteria) {
            $kriteria_id = $kriteria->id;
            $tipe_kriteria = $criteriaData[$kriteria_id]['tipe'];
            $columnValues = array_column($decisionMatrix, $kriteria_id);

            if (empty($columnValues)) continue;

            $maxValInColumn = max($columnValues);
            $minValInColumn = min($columnValues);

            foreach ($alternatifs as $alternatif) {
                $alt_id = $alternatif->id;
                $currentVal = $decisionMatrix[$alt_id][$kriteria_id] ?? 0;
                $normalizedMatrix[$alt_id] = $normalizedMatrix[$alt_id] ?? [];

                if ($tipe_kriteria == 'benefit') {
                    $normalizedMatrix[$alt_id][$kriteria_id] = ($maxValInColumn > 0) ? ($currentVal / $maxValInColumn) : 0;
                } elseif ($tipe_kriteria == 'cost') {
                    $normalizedMatrix[$alt_id][$kriteria_id] = ($currentVal > 0) ? ($minValInColumn / $currentVal) : 1;
                } else {
                    $normalizedMatrix[$alt_id][$kriteria_id] = 0;
                }
            }
        }

        // --- LANGKAH 2: TABEL ANALISIS PERINGKAT ---
        $analysisTable = [];
        foreach ($kriterias as $kriteria) {
            $kriteria_id = $kriteria->id;
            $normalizedColumn = array_column($normalizedMatrix, $kriteria_id);

            if (empty($normalizedColumn)) {
                $analysisTable[$kriteria_id] = ['g_plus' => 0, 'g_minus' => 0, 'jumlah_interval_bobot' => 0, 'perbedaan_interval' => 0];
                continue;
            }

            $g_plus = max($normalizedColumn);
            $g_minus = min($normalizedColumn);
            $bobot_ternormalisasi_kriteria = $criteriaData[$kriteria_id]['bobot_normalized'];
            
            $perbedaan_g = $g_plus - $g_minus;
            $perbedaan_interval = ($bobot_ternormalisasi_kriteria > 0) ? ($perbedaan_g / $bobot_ternormalisasi_kriteria) : 0;

            $analysisTable[$kriteria_id] = [
                'g_plus' => (float)$g_plus,
                'g_minus' => (float)$g_minus,
                'jumlah_interval_bobot' => (float)$bobot_ternormalisasi_kriteria,
                'perbedaan_interval' => (float)$perbedaan_interval,
            ];
        }

        // --- LANGKAH 3: MENGHITUNG NILAI UTILITAS PARSIAL ---
        $partialUtilities = [];
        foreach ($alternatifs as $alternatif) {
            $alt_id = $alternatif->id;
            foreach ($kriterias as $kriteria) {
                $kriteria_id = $kriteria->id;
                $normalizedValue = $normalizedMatrix[$alt_id][$kriteria_id] ?? 0;
                $multiplier = $analysisTable[$kriteria_id]['perbedaan_interval'] ?? 0;
                $partialUtilities[$alt_id][$kriteria_id] = (float)($normalizedValue * $multiplier);
            }
        }

        // --- LANGKAH 4: MENGHITUNG NILAI UTILITAS TOTAL DAN PERANGKINGAN ---
        $finalScores = [];
        foreach ($alternatifs as $alternatif) {
            $alt_id = $alternatif->id;
            $total_utility = isset($partialUtilities[$alt_id]) ? array_sum($partialUtilities[$alt_id]) : 0;
            $finalScores[] = [
                'nama' => $alternatif->nama,
                'id' => $alternatif->id,
                'total_utility' => (float)$total_utility,
            ];
        }

        usort($finalScores, fn($a, $b) => $b['total_utility'] <=> $a['total_utility']);
        
        $rank = 0;
        $last_score = -1;
        $rank_increment = 1;
        foreach ($finalScores as $key => &$score) {
            if (abs($score['total_utility'] - $last_score) > 0.00001) {
                $rank += $rank_increment;
                $rank_increment = 1;
            } else {
                $rank_increment++;
            }
            $score['rank'] = $rank;
            $last_score = $score['total_utility'];
        }
        unset($score);

        // --- MENGEMBALIKAN SEMUA HASIL PERHITUNGAN DALAM SEBUAH ARRAY ---
        $calculationResults = [
            'alternatifs_data' => $alternatifs->map(fn($alt) => ['id' => $alt->id, 'nama' => $alt->nama])->all(),
            'kriterias_data' => array_values($criteriaData),
            'originalDecisionMatrix' => $originalDecisionMatrix,
            'normalizedMatrix' => $normalizedMatrix,
            'analysisTable' => $analysisTable,
            'partialUtilities' => $partialUtilities,
            'finalScores' => $finalScores
        ];

        return ['type' => 'success', 'data' => $calculationResults, 'message' => 'Perhitungan UTA berhasil diselesaikan.'];
    }
}