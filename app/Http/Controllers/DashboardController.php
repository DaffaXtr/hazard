<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PreTest;
use App\Models\Simulation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard Home - Metrics and Chart data.
     */
    public function index()
    {
        // 1. Overall Metrics
        $totalMahasiswa = User::where('role', 'mahasiswa')->count();
        $totalPreTest = PreTest::count();
        $totalSimulation = Simulation::count();
        
        $avgPreTestScore = round(PreTest::avg('score') ?? 0, 1);
        
        $survivedCount = Simulation::where('status', 'survived')->count();
        $survivalRate = $totalSimulation > 0 
            ? round(($survivedCount / $totalSimulation) * 100, 1) 
            : 0;

        $avgDuration = round(Simulation::avg('duration') ?? 0, 0); // in seconds
        $avgMaxPpm = round(Simulation::avg('max_ppm') ?? 0, 1);

        // 2. Chart 1: Survival Rate by Gas Type
        $amoniaTotal = Simulation::where('gas_type', 'amonia')->count();
        $amoniaSurvived = Simulation::where('gas_type', 'amonia')->where('status', 'survived')->count();
        $amoniaSurvivalRate = $amoniaTotal > 0 ? round(($amoniaSurvived / $amoniaTotal) * 100, 1) : 0;

        $klorinTotal = Simulation::where('gas_type', 'klorin')->count();
        $klorinSurvived = Simulation::where('gas_type', 'klorin')->where('status', 'survived')->count();
        $klorinSurvivalRate = $klorinTotal > 0 ? round(($klorinSurvived / $klorinTotal) * 100, 1) : 0;

        // 3. Chart 2: Average Duration and PPM Comparison
        $amoniaAvgDuration = round(Simulation::where('gas_type', 'amonia')->avg('duration') ?? 0, 0);
        $klorinAvgDuration = round(Simulation::where('gas_type', 'klorin')->avg('duration') ?? 0, 0);

        $amoniaAvgMaxPpm = round(Simulation::where('gas_type', 'amonia')->avg('max_ppm') ?? 0, 1);
        $klorinAvgMaxPpm = round(Simulation::where('gas_type', 'klorin')->avg('max_ppm') ?? 0, 1);

        $chartData = [
            'amonia_rate' => $amoniaSurvivalRate,
            'klorin_rate' => $klorinSurvivalRate,
            'amonia_avg_duration' => $amoniaAvgDuration,
            'klorin_avg_duration' => $klorinAvgDuration,
            'amonia_avg_ppm' => $amoniaAvgMaxPpm,
            'klorin_avg_ppm' => $klorinAvgMaxPpm,
        ];

        return view('dashboard.index', compact(
            'totalMahasiswa',
            'totalPreTest',
            'totalSimulation',
            'avgPreTestScore',
            'survivalRate',
            'avgDuration',
            'avgMaxPpm',
            'chartData'
        ));
    }

    /**
     * List students with pre-test and simulation summaries.
     */
    public function students()
    {
        $students = User::where('role', 'mahasiswa')
            ->with(['preTests', 'simulations'])
            ->latest()
            ->get()
            ->map(function ($student) {
                $totalSims = $student->simulations->count();
                $survivedSims = $student->simulations->where('status', 'survived')->count();
                $student->simulations_count = $totalSims;
                $student->survival_rate = $totalSims > 0 ? round(($survivedSims / $totalSims) * 100, 1) : 0;
                $student->pre_test_score = $student->preTests->first()->score ?? '-';
                return $student;
            });

        return view('dashboard.students', compact('students'));
    }

    /**
     * List all pre-tests.
     */
    public function preTests()
    {
        $preTests = PreTest::with('user')->latest()->get();
        return view('dashboard.pre_tests', compact('preTests'));
    }

    /**
     * List simulations with filtering and CSV export.
     */
    public function simulations(Request $request)
    {
        $query = Simulation::with('user');

        // Apply filters
        if ($request->filled('gas_type')) {
            $query->where('gas_type', $request->input('gas_type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // CSV Export
        if ($request->input('export') === 'csv') {
            $simulations = $query->latest()->get();
            return $this->exportCsv($simulations);
        }

        $simulations = $query->latest()->get();

        return view('dashboard.simulations', compact('simulations'));
    }

    /**
     * Export simulation logs to CSV format.
     */
    private function exportCsv(\Illuminate\Support\Collection $simulations)
    {
        $filename = 'log_simulasi_webar_k3_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $columns = [
            'ID', 
            'Nama Mahasiswa', 
            'Email Mahasiswa', 
            'Jenis Gas', 
            'Durasi (detik)', 
            'Max PPM', 
            'Final PPM', 
            'Status', 
            'Alasan Gagal', 
            'APD Dipilih', 
            'Aksi Mitigasi', 
            'Tanggal Percobaan'
        ];

        $callback = function() use($simulations, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, $columns, ';');

            foreach ($simulations as $sim) {
                fputcsv($file, [
                    $sim->id,
                    $sim->user->name ?? 'N/A',
                    $sim->user->email ?? 'N/A',
                    ucfirst($sim->gas_type),
                    $sim->duration,
                    $sim->max_ppm,
                    $sim->final_ppm,
                    ucfirst($sim->status),
                    $sim->failure_reason ?? '-',
                    $sim->ppe_selected,
                    $sim->mitigation_action === 'water_spray' ? 'Water Spray' : 'Capping Kit',
                    $sim->created_at->format('Y-m-d H:i:s'),
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
