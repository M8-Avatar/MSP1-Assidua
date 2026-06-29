<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function route_apprenants_redirige_vers_login_sans_authentification(): void
    {
        $this->get('/apprenants')->assertRedirect('/login');
    }

    /** @test */
    public function route_apprenants_retourne_403_avec_role_apprenant(): void
    {
        $apprenant = User::factory()->apprenant()->create();

        $this->actingAs($apprenant)
             ->get('/apprenants')
             ->assertStatus(403);
    }

    /** @test */
    public function route_apprenants_accessible_avec_role_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
             ->get('/apprenants')
             ->assertStatus(200);
    }

    /** @test */
    public function route_formations_redirige_vers_login_sans_authentification(): void
    {
        $this->get('/formations')->assertRedirect('/login');
    }

    /** @test */
    public function route_alertes_retourne_403_avec_role_apprenant(): void
    {
        $apprenant = User::factory()->apprenant()->create();

        $this->actingAs($apprenant)
             ->get('/alertes')
             ->assertStatus(403);
    }
}