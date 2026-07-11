<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PreTest;

class PreTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'dosen') {
            $preTests = PreTest::with('user')->latest()->get();
        } else {
            $preTests = $user->preTests()->latest()->get();
        }

        return response()->json([
            'pre_tests' => $preTests
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'answers' => 'required|array',
        ]);

        $preTest = $request->user()->preTests()->create([
            'score' => $validated['score'],
            'answers' => $validated['answers'],
        ]);

        return response()->json([
            'message' => 'Pre-test log stored successfully',
            'pre_test' => $preTest
        ], 201);
    }
}
