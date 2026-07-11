<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\PreTest;

class PreTestTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_store_pre_test(): void
    {
        $response = $this->postJson('/api/pre-tests', [
            'score' => 80,
            'answers' => ['q1' => 'a']
        ]);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_store_pre_test(): void
    {
        $user = User::factory()->create();

        $payload = [
            'score' => 85,
            'answers' => [
                ['question_id' => 1, 'selected_option' => 'A'],
                ['question_id' => 2, 'selected_option' => 'C']
            ]
        ];

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/pre-tests', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'pre_test' => ['id', 'user_id', 'score', 'answers', 'created_at', 'updated_at']
            ]);

        $this->assertDatabaseHas('pre_tests', [
            'user_id' => $user->id,
            'score' => 85
        ]);
    }

    public function test_validation_errors_on_invalid_pre_test_data(): void
    {
        $user = User::factory()->create();

        // Test invalid score (too high)
        $payload1 = [
            'score' => 150,
            'answers' => ['q1' => 'A']
        ];
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/pre-tests', $payload1);
        $response->assertStatus(422)->assertJsonValidationErrors(['score']);

        // Test invalid answers (not an array)
        $payload2 = [
            'score' => 70,
            'answers' => 'not an array'
        ];
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/pre-tests', $payload2);
        $response->assertStatus(422)->assertJsonValidationErrors(['answers']);
    }

    public function test_mahasiswa_can_only_see_own_pre_tests(): void
    {
        $user1 = User::factory()->create(['role' => 'mahasiswa']);
        $user2 = User::factory()->create(['role' => 'mahasiswa']);

        $preTest1 = PreTest::create([
            'user_id' => $user1->id,
            'score' => 90,
            'answers' => ['q1' => 'A']
        ]);

        $preTest2 = PreTest::create([
            'user_id' => $user2->id,
            'score' => 75,
            'answers' => ['q1' => 'B']
        ]);

        $response = $this->actingAs($user1, 'sanctum')->getJson('/api/pre-tests');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'pre_tests')
            ->assertJsonPath('pre_tests.0.id', $preTest1->id);
    }

    public function test_dosen_can_see_all_pre_tests(): void
    {
        $mahasiswa1 = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa2 = User::factory()->create(['role' => 'mahasiswa']);
        $dosen = User::factory()->create(['role' => 'dosen']);

        PreTest::create([
            'user_id' => $mahasiswa1->id,
            'score' => 90,
            'answers' => ['q1' => 'A']
        ]);

        PreTest::create([
            'user_id' => $mahasiswa2->id,
            'score' => 75,
            'answers' => ['q1' => 'B']
        ]);

        $response = $this->actingAs($dosen, 'sanctum')->getJson('/api/pre-tests');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'pre_tests');
    }
}
