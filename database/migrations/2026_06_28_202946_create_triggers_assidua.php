<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        DB::unprepared("
            CREATE OR REPLACE FUNCTION recalculer_taux()
            RETURNS TRIGGER AS \$\$
            DECLARE
                ref_inscription INT;
                total     INT;
                presents  INT;
                nouveau_taux NUMERIC(5,2);
            BEGIN
                IF TG_OP = 'DELETE' THEN
                    ref_inscription := OLD.inscription_id;
                ELSE
                    ref_inscription := NEW.inscription_id;
                END IF;

                SELECT COUNT(*) INTO total
                FROM presences
                WHERE inscription_id = ref_inscription
                  AND statut != 'absent_justifie';

                SELECT COUNT(*) INTO presents
                FROM presences
                WHERE inscription_id = ref_inscription
                  AND statut IN ('present', 'retard');

                IF total = 0 THEN
                    nouveau_taux := 100;
                ELSE
                    nouveau_taux := ROUND((presents::NUMERIC / total) * 100, 2);
                END IF;

                INSERT INTO assiduites (inscription_id, taux, updated_at)
                VALUES (ref_inscription, nouveau_taux, NOW())
                ON CONFLICT (inscription_id)
                DO UPDATE SET taux = nouveau_taux, updated_at = NOW();

                IF TG_OP = 'DELETE' THEN
                    RETURN OLD;
                ELSE
                    RETURN NEW;
                END IF;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("
            CREATE TRIGGER trigger_recalcul_taux
            AFTER INSERT OR UPDATE OR DELETE ON presences
            FOR EACH ROW EXECUTE FUNCTION recalculer_taux();
        ");

        DB::unprepared("
            CREATE OR REPLACE FUNCTION generer_alerte()
            RETURNS TRIGGER AS \$\$
            BEGIN
                IF NEW.taux < 75 THEN
                    INSERT INTO alertes (assiduite_id, date_alerte, vue_admin, vue_apprenant)
                    VALUES (NEW.id, NOW(), FALSE, FALSE)
                    ON CONFLICT DO NOTHING;
                END IF;
                RETURN NEW;
            END;
            \$\$ LANGUAGE plpgsql;
        ");

        DB::unprepared("ALTER TABLE alertes ADD CONSTRAINT alertes_assiduite_unique UNIQUE (assiduite_id)");

        DB::unprepared("
            CREATE TRIGGER trigger_alerte_assiduité
            AFTER INSERT OR UPDATE OF taux ON assiduites
            FOR EACH ROW EXECUTE FUNCTION generer_alerte();
        ");
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        DB::unprepared("ALTER TABLE alertes DROP CONSTRAINT IF EXISTS alertes_assiduite_unique");
        DB::unprepared("DROP TRIGGER IF EXISTS trigger_alerte_assiduité ON assiduites");
        DB::unprepared("DROP TRIGGER IF EXISTS trigger_recalcul_taux ON presences");
        DB::unprepared("DROP FUNCTION IF EXISTS generer_alerte()");
        DB::unprepared("DROP FUNCTION IF EXISTS recalculer_taux()");
    }
};