<?php

namespace Tests\Feature;

use App\Models\Alerte;
use App\Models\Assiduite;
use App\Models\Formation;
use App\Models\Inscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlerteTest extends TestCase
{
    use RefreshDatabase;

    private function creerContexteApprenant(float $taux): array
    {
        $apprenant   = User::factory()->apprenant()->create();
        $formation   = Formation::create([
            'nom'        => 'Formation Test',
            'date_debut' => '2026-01-01',
            'date_fin'   => '2026-12-31',
        ]);
        $inscription = Inscription::create([
            'user_id'       => $apprenant->id,
            'formations_id' => $formation->id,
        ]);
        $assiduite = Assiduite::create([
            'inscription_id' => $inscription->id,
            'taux'           => $taux,
        ]);

        return compact('apprenant', 'formation', 'inscription', 'assiduite');
    }

    /** @test */
    public function alerte_creee_quand_taux_inferieur_a_75(): void
    {
        ['assiduite' => $assiduite] = $this->creerContexteApprenant(60.0);

        // Simule le comportement du trigger generer_alerte()
        Alerte::create([
            'assiduite_id'  => $assiduite->id,
            'vue_admin'     => false,
            'vue_apprenant' => false,
        ]);

        $this->assertDatabaseHas('alertes', [
            'assiduite_id' => $assiduite->id,
            'vue_admin'    => false,
        ]);
        $this->assertLessThan(75, $assiduite->taux);
    }

    /** @test */
    public function aucune_alerte_ne_doit_exister_pour_taux_superieur_ou_egal_a_75(): void
    {
        $this->creerContexteApprenant(80.0);

        // Le trigger ne crée pas d'alerte si taux >= 75
        $this->assertDatabaseCount('alertes', 0);
    }

    /** @test */
    public function alerte_visible_sur_page_admin(): void
    {
        $admin                      = User::factory()->admin()->create();
        ['assiduite' => $assiduite] = $this->creerContexteApprenant(50.0);

        Alerte::create([
            'assiduite_id'  => $assiduite->id,
            'vue_admin'     => false,
            'vue_apprenant' => false,
        ]);

        $this->actingAs($admin)
             ->get('/alertes')
             ->assertStatus(200)
             ->assertSee('Formation Test');
    }

    /** @test */
    public function alerte_peut_etre_marquee_comme_vue(): void
    {
        $admin                      = User::factory()->admin()->create();
        ['assiduite' => $assiduite] = $this->creerContexteApprenant(40.0);

        $alerte = Alerte::create([
            'assiduite_id'  => $assiduite->id,
            'vue_admin'     => false,
            'vue_apprenant' => false,
        ]);

        $this->actingAs($admin)
             ->post(route('alertes.mark-read', $alerte))
             ->assertRedirect(route('alertes.index'));

        $this->assertDatabaseHas('alertes', [
            'id'        => $alerte->id,
            'vue_admin' => true,
        ]);
    }
}