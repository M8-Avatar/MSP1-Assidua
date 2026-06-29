<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class TauxCalculTest extends TestCase
{
    /**
     * Formule miroir du trigger PostgreSQL recalculer_taux() :
     *   total    = COUNT(*) WHERE statut != 'absent_justifie'
     *   presents = COUNT(*) WHERE statut IN ('present', 'retard')
     *   taux     = total > 0 ? ROUND(presents/total * 100, 2) : 100
     */
    private function calculerTaux(array $statuts): float
    {
        $total    = count(array_filter($statuts, fn($s) => $s !== 'absent_justifie'));
        $presents = count(array_filter($statuts, fn($s) => in_array($s, ['present', 'retard'])));
        return $total > 0 ? round($presents / $total * 100, 2) : 100.0;
    }

    public function test_taux_est_100_si_toutes_presences_sont_present(): void
    {
        $this->assertEquals(100.0, $this->calculerTaux(['present', 'present', 'present']));
    }

    public function test_taux_est_100_si_aucune_presence_saisie(): void
    {
        $this->assertEquals(100.0, $this->calculerTaux([]));
    }

    public function test_absences_justifiees_exclues_du_denominateur(): void
    {
        // 2 present + 1 absent_justifie → denominateur = 2 → taux = 100 %
        $this->assertEquals(100.0, $this->calculerTaux(['present', 'present', 'absent_justifie']));
    }

    public function test_absent_simple_compte_dans_le_denominateur(): void
    {
        // 1 present + 1 absent + 1 absent_justifie → denominateur = 2 → taux = 50 %
        $this->assertEquals(50.0, $this->calculerTaux(['present', 'absent', 'absent_justifie']));
    }

    public function test_retard_compte_comme_present_dans_le_numerateur(): void
    {
        // 1 present + 1 retard + 1 absent → 2/3 → 66.67 %
        $this->assertEquals(66.67, $this->calculerTaux(['present', 'retard', 'absent']));
    }

    public function test_taux_de_25_pourcent_est_inferieur_au_seuil_alerte(): void
    {
        // 1 present / 4 total → 25 % → declenche une alerte
        $taux = $this->calculerTaux(['present', 'absent', 'absent', 'absent']);
        $this->assertEquals(25.0, $taux);
        $this->assertLessThan(75, $taux);
    }

    public function test_taux_de_75_pourcent_exact_ne_declenche_pas_alerte(): void
    {
        // 3 present / 4 total → 75 % → pas d alerte (seuil strict < 75)
        $taux = $this->calculerTaux(['present', 'present', 'present', 'absent']);
        $this->assertEquals(75.0, $taux);
        $this->assertGreaterThanOrEqual(75, $taux);
    }
}