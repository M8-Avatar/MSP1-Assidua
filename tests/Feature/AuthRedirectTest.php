<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRedirectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_redirige_vers_dashboard_admin_apres_login(): void
    {
        $admin = User::factory()->admin()->create();

        $this->post('/login', [
            'email'    => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard.admin'));
    }

    /** @test */
    public function apprenant_redirige_vers_dashboard_apprenant_apres_login(): void
    {
        $apprenant = User::factory()->apprenant()->create();

        $this->post('/login', [
            'email'    => $apprenant->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard.apprenant'));
    }
}