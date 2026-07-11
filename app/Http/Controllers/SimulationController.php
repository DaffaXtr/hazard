<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Simulation;

class SimulationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'dosen') {
            $simulations = Simulation::with('user')->latest()->get();
        } else {
            $simulations = $user->simulations()->latest()->get();
        }

        return response()->json([
            'simulations' => $simulations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gas_type' => 'required|string|in:amonia,klorin',
            'duration' => 'required|integer|min:0',
            'max_ppm' => 'required|numeric|min:0',
            'final_ppm' => 'required|numeric|min:0',
            'status' => 'required|string|in:survived,failed',
            'failure_reason' => 'nullable|string|max:255',
            'ppe_selected' => 'required|string|max:255',
            'mitigation_action' => 'required|string|in:water_spray,capping_kit',
        ]);

        $simulation = $request->user()->simulations()->create([
            'gas_type' => $validated['gas_type'],
            'duration' => $validated['duration'],
            'max_ppm' => $validated['max_ppm'],
            'final_ppm' => $validated['final_ppm'],
            'status' => $validated['status'],
            'failure_reason' => $validated['failure_reason'] ?? null,
            'ppe_selected' => $validated['ppe_selected'],
            'mitigation_action' => $validated['mitigation_action'],
        ]);

        return response()->json([
            'message' => 'Simulation log stored successfully',
            'simulation' => $simulation
        ], 201);
    }

    /**
     * Get aggregate statistics for landing page and dashboard.
     */
    public function stats()
    {
        $totalUsers = \App\Models\User::where('role', 'mahasiswa')->count();
        $totalSimulations = Simulation::count();
        $survivedSims = Simulation::where('status', 'survived')->count();
        $survivalRate = $totalSimulations > 0 ? round(($survivedSims / $totalSimulations) * 100, 0) : 0;

        return response()->json([
            'total_users' => $totalUsers,
            'total_simulations' => $totalSimulations,
            'survival_rate' => $survivalRate,
        ]);
    }
}
