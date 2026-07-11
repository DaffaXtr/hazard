<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Simulation;

class SimulationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_store_simulation(): void
    {
        $response = $this->postJson('/api/simulations', [
            'gas_type' => 'amonia',
            'duration' => 120,
            'max_ppm' => 300.5,
            'final_ppm' => 15.2,
            'status' => 'survived'
        ]);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_store_simulation(): void
    {
        $user = User::factory()->create();

        $payload = [
            'gas_type' => 'klorin',
            'duration' => 90,
            'max_ppm' => 450.0,
            'final_ppm' => 450.0,
            'status' => 'failed',
            'failure_reason' => 'Tunnel Vision / Over-exposure',
            'ppe_selected' => 'SCBA + Hazmat Level A',
            'mitigation_action' => 'capping_kit'
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/simulations', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'simulation' => [
                    'id', 'user_id', 'gas_type', 'duration', 'max_ppm', 'final_ppm',
                    'status', 'failure_reason', 'created_at', 'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('simulations', [
            'user_id' => $user->id,
            'gas_type' => 'klorin',
            'status' => 'failed'
        ]);
    }

    public function test_validation_errors_on_invalid_simulation_data(): void
    {
        $user = User::factory()->create();

        // Test invalid gas type
        $payload1 = [
            'gas_type' => 'helium',
            'duration' => 120,
            'max_ppm' => 300,
            'final_ppm' => 15,
            'status' => 'survived'
        ];
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/simulations', $payload1);
        $response->assertStatus(422)->assertJsonValidationErrors(['gas_type']);

        // Test invalid status
        $payload2 = [
            'gas_type' => 'amonia',
            'duration' => -10,
            'max_ppm' => 300,
            'final_ppm' => 15,
            'status' => 'dead'
        ];
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/simulations', $payload2);
        $response->assertStatus(422)->assertJsonValidationErrors(['status', 'duration']);
    }

    public function test_mahasiswa_can_only_see_own_simulations(): void
    {
        $user1 = User::factory()->create(['role' => 'mahasiswa']);
        $user2 = User::factory()->create(['role' => 'mahasiswa']);

        $simulation1 = Simulation::create([
            'user_id' => $user1->id,
            'gas_type' => 'amonia',
            'duration' => 120,
            'max_ppm' => 200,
            'final_ppm' => 10,
            'status' => 'survived',
            'ppe_selected' => 'Respirator Full-Face (Filter K)',
            'mitigation_action' => 'water_spray'
        ]);

        $simulation2 = Simulation::create([
            'user_id' => $user2->id,
            'gas_type' => 'klorin',
            'duration' => 80,
            'max_ppm' => 500,
            'final_ppm' => 500,
            'status' => 'failed',
            'ppe_selected' => 'SCBA + Hazmat Level A',
            'mitigation_action' => 'capping_kit'
        ]);

        $response = $this->actingAs($user1, 'sanctum')->getJson('/api/simulations');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'simulations')
            ->assertJsonPath('simulations.0.id', $simulation1->id);
    }

    public function test_dosen_can_see_all_simulations(): void
    {
        $mahasiswa1 = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa2 = User::factory()->create(['role' => 'mahasiswa']);
        $dosen = User::factory()->create(['role' => 'dosen']);

        Simulation::create([
            'user_id' => $mahasiswa1->id,
            'gas_type' => 'amonia',
            'duration' => 120,
            'max_ppm' => 200,
            'final_ppm' => 10,
            'status' => 'survived',
            'ppe_selected' => 'Respirator Full-Face (Filter K)',
            'mitigation_action' => 'water_spray'
        ]);

        Simulation::create([
            'user_id' => $mahasiswa2->id,
            'gas_type' => 'klorin',
            'duration' => 80,
            'max_ppm' => 500,
            'final_ppm' => 500,
            'status' => 'failed',
            'ppe_selected' => 'SCBA + Hazmat Level A',
            'mitigation_action' => 'capping_kit'
        ]);

        $response = $this->actingAs($dosen, 'sanctum')->getJson('/api/simulations');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'simulations');
    }
}
