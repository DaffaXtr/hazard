<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_mahasiswa_cannot_access_dashboard(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $response = $this->actingAs($mahasiswa)
            ->get('/dashboard');

        // Middleware should log them out and redirect to login
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->assertFalse(Auth::check());
    }

    public function test_dosen_can_access_dashboard_and_see_metrics(): void
    {
        $dosen = User::factory()->create(['role' => 'dosen']);

        $response = $this->actingAs($dosen)
            ->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');
        $response->assertViewHas('totalMahasiswa');
    }

    public function test_dosen_can_view_students_list(): void
    {
        $dosen = User::factory()->create(['role' => 'dosen']);

        $response = $this->actingAs($dosen)
            ->get('/dashboard/students');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.students');
        $response->assertViewHas('students');
    }

    public function test_dosen_can_view_pre_tests(): void
    {
        $dosen = User::factory()->create(['role' => 'dosen']);

        $response = $this->actingAs($dosen)
            ->get('/dashboard/pre-tests');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.pre_tests');
        $response->assertViewHas('preTests');
    }

    public function test_dosen_can_view_simulations_and_export_csv(): void
    {
        $dosen = User::factory()->create(['role' => 'dosen']);

        // Test normal view
        $response = $this->actingAs($dosen)
            ->get('/dashboard/simulations');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.simulations');
        $response->assertViewHas('simulations');

        // Test CSV export stream
        $responseCsv = $this->actingAs($dosen)
            ->get('/dashboard/simulations?export=csv');

        $responseCsv->assertStatus(200);
        $responseCsv->assertHeader('Content-Type', 'text/csv; charset=utf-8');
        // Asserting header starts with log_simulasi_webar_k3 is safer.
        $this->assertStringContainsString('log_simulasi_webar_k3_', $responseCsv->headers->get('Content-Disposition'));
    }
}
