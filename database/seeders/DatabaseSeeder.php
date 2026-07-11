<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Dosen Account
        User::create([
            'name' => 'Dosen K3',
            'email' => 'dosen@example.com',
            'password' => bcrypt('password123'),
            'role' => 'dosen',
        ]);

        // 2. Create Mahasiswa Accounts
        $mahasiswas = [];
        $names = ['Aditya Pratama', 'Budi Santoso', 'Citra Lestari', 'Daffa Saputra', 'Elisa Putri'];
        foreach ($names as $index => $name) {
            $mahasiswas[] = User::create([
                'name' => $name,
                'email' => 'mahasiswa' . ($index + 1) . '@example.com',
                'password' => bcrypt('password123'),
                'role' => 'mahasiswa',
            ]);
        }

        // 3. Create Pre-Test Dummy Data
        foreach ($mahasiswas as $mahasiswa) {
            // Pre-test score ranges from 60 to 100
            $score = [60, 70, 80, 90, 100][rand(0, 4)];
            \App\Models\PreTest::create([
                'user_id' => $mahasiswa->id,
                'score' => $score,
                'answers' => [
                    [
                        'question' => 'Apa warna gas Amonia K3?',
                        'user_answer' => $score >= 80 ? 'Kuning kehijauan pudar' : 'Merah pekat',
                        'is_correct' => $score >= 80,
                    ],
                    [
                        'question' => 'Apa warna gas Klorin K3?',
                        'user_answer' => $score >= 70 ? 'Kuning kehijauan pekat' : 'Biru terang',
                        'is_correct' => $score >= 70,
                    ],
                    [
                        'question' => 'Alat pelindung diri apa yang wajib untuk Amonia?',
                        'user_answer' => 'Masker full-face dengan filter khusus',
                        'is_correct' => true,
                    ]
                ]
            ]);
        }

        // 4. Create Simulation Log Dummy Data
        $gases = ['amonia', 'klorin'];
        $statuses = ['survived', 'failed'];
        $reasons = [
            'amonia' => ['Over-exposure PPM', 'Vignette Opaque / Loss of Vision', 'Time limit exceeded'],
            'klorin' => ['Critical exposure level', 'Failed to place absorbent barricades', 'Time limit exceeded']
        ];

        foreach ($mahasiswas as $mahasiswa) {
            // Each student does 2-3 simulations
            $attempts = rand(2, 3);
            for ($i = 0; $i < $attempts; $i++) {
                $gas = $gases[rand(0, 1)];
                $status = $statuses[rand(0, 1)];
                $duration = rand(45, 180);
                
                if ($gas === 'amonia') {
                    $maxPpm = rand(150, 350);
                    $finalPpm = $status === 'survived' ? rand(10, 150) : $maxPpm;
                    $ppe = $status === 'survived' ? 'Respirator Full-Face (Filter K)' : (rand(0, 1) === 0 ? 'Masker Bedah' : 'Respirator Half-Mask');
                    $mitigation = 'water_spray';
                } else {
                    $maxPpm = rand(8, 20);
                    $finalPpm = $status === 'survived' ? rand(1, 8) : $maxPpm;
                    $ppe = $status === 'survived' ? 'SCBA + Hazmat Level A' : (rand(0, 1) === 0 ? 'Masker Bedah' : 'Respirator Full-Face (Filter K)');
                    $mitigation = 'capping_kit';
                }
                
                $failureReason = $status === 'failed' 
                    ? ($ppe === 'Masker Bedah' || $ppe === 'Respirator Half-Mask' || ($gas === 'klorin' && $ppe === 'Respirator Full-Face (Filter K)') ? 'Wrong PPE / Instant Penalty' : 'Over-exposure') 
                    : null;

                \App\Models\Simulation::create([
                    'user_id' => $mahasiswa->id,
                    'gas_type' => $gas,
                    'duration' => $duration,
                    'max_ppm' => $maxPpm,
                    'final_ppm' => $finalPpm,
                    'status' => $status,
                    'failure_reason' => $failureReason,
                    'ppe_selected' => $ppe,
                    'mitigation_action' => $mitigation,
                    'created_at' => now()->subDays(rand(0, 5))->subHours(rand(1, 12)),
                ]);
            }
        }
    }
}
