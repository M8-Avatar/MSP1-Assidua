<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    // /register est reserve aux admins (plus de self-registration publique)

    public function test_registration_page_inaccessible_to_guests(): void
    {
        $this->get('/register')->assertRedirect('/login');
    }

    public function test_registration_page_accessible_to_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->get('/register')->assertStatus(200);
    }

    public function test_admin_can_create_apprenant_account(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('apprenants.index'));
        $this->assertDatabaseHas('users', ['email' => 'test@example.com', 'role' => 'apprenant']);
    }
}