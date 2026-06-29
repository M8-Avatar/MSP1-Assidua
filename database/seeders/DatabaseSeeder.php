<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    private array $profiles = [
        0 => 'excellent',
        1 => 'excellent',
        2 => 'bon',
        3 => 'excellent',
        4 => 'fragile',
        5 => 'bon',
        6 => 'fragile',
        7 => 'bon',
        8 => 'problematique',
        9 => 'excellent',
    ];

    public function run(): void
    {
        $this->command->info('Nettoyage des tables...');
        DB::statement('TRUNCATE TABLE sessions, alertes, assiduites, presences, animations, inscriptions, formations, users RESTART IDENTITY CASCADE');

        $now = Carbon::now();

        $this->command->info('Creation du compte admin...');
        $adminId = DB::table('users')->insertGetId([
            'nom'        => 'Laurent',
            'prenom'     => 'Marie',
            'name'       => 'Marie Laurent',
            'email'      => 'admin@assidua.fr',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->command->info('Creation des apprenants...');
        // [prenom, nom, email]
        $apprenants = [
            ['Thomas',  'Dupont',    'thomas.dupont@apprenant.fr'],
            ['Sophie',  'Martin',    'sophie.martin@apprenant.fr'],
            ['Lucas',   'Bernard',   'lucas.bernard@apprenant.fr'],
            ['Emma',    'Petit',     'emma.petit@apprenant.fr'],
            ['Nathan',  'Moreau',    'nathan.moreau@apprenant.fr'],
            ['Chloe',   'Simon',     'chloe.simon@apprenant.fr'],
            ['Antoine', 'Lefebvre',  'antoine.lefebvre@apprenant.fr'],
            ['Ines',    'Leroy',     'ines.leroy@apprenant.fr'],
            ['Maxime',  'Roux',      'maxime.roux@apprenant.fr'],
            ['Camille', 'Girard',    'camille.girard@apprenant.fr'],
        ];

        $ids = [];
        foreach ($apprenants as $a) {
            $ids[] = DB::table('users')->insertGetId([
                'prenom'     => $a[0],
                'nom'        => $a[1],
                'name'       => $a[0] . ' ' . $a[1],
                'email'      => $a[2],
                'password'   => Hash::make('password'),
                'role'       => 'apprenant',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info('Creation des formations...');
        $f1 = DB::table('formations')->insertGetId([
            'nom'        => 'Developpement Web Full Stack',
            'date_debut' => '2026-01-05',
            'date_fin'   => '2026-06-26',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $f2 = DB::table('formations')->insertGetId([
            'nom'        => 'Marketing Digital et Reseaux Sociaux',
            'date_debut' => '2026-02-02',
            'date_fin'   => '2026-06-19',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $f3 = DB::table('formations')->insertGetId([
            'nom'        => 'Gestion de Projet Agile (Scrum / Kanban)',
            'date_debut' => '2026-04-07',
            'date_fin'   => '2026-06-25',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        foreach ([$f1, $f2, $f3] as $fid) {
            DB::table('animations')->insert([
                'user_id'       => $adminId,
                'formations_id' => $fid,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }

        $this->command->info('Inscription des apprenants...');
        $inscF1 = [];
        foreach ([0,1,2,3,4,5,6,7] as $i) {
            $inscF1[$i] = DB::table('inscriptions')->insertGetId([
                'user_id'       => $ids[$i],
                'formations_id' => $f1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
        $inscF2 = [];
        foreach ([2,3,4,5,6,7,8] as $i) {
            $inscF2[$i] = DB::table('inscriptions')->insertGetId([
                'user_id'       => $ids[$i],
                'formations_id' => $f2,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
        $inscF3 = [];
        foreach ([0,1,2,5,6,7,8,9] as $i) {
            $inscF3[$i] = DB::table('inscriptions')->insertGetId([
                'user_id'       => $ids[$i],
                'formations_id' => $f3,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }

        $seances = [
            ['fid' => $f1, 'dates' => $this->weekdaysBetween('2026-01-06', '2026-06-23', Carbon::TUESDAY),   'insc' => $inscF1, 'idx' => [0,1,2,3,4,5,6,7]],
            ['fid' => $f2, 'dates' => $this->weekdaysBetween('2026-02-05', '2026-06-18', Carbon::THURSDAY),  'insc' => $inscF2, 'idx' => [2,3,4,5,6,7,8]],
            ['fid' => $f3, 'dates' => $this->weekdaysBetween('2026-04-08', '2026-06-24', Carbon::WEDNESDAY), 'insc' => $inscF3, 'idx' => [0,1,2,5,6,7,8,9]],
        ];

        foreach ($seances as $group) {
            $label = DB::table('formations')->where('id', $group['fid'])->value('nom');
            $n     = count($group['dates']);
            $this->command->info("Presences — {$label} ({$n} seances)...");

            foreach ($group['dates'] as $date) {
                foreach ($group['idx'] as $i) {
                    $statut = $this->pickStatut($this->profiles[$i]);
                    DB::table('presences')->insert([
                        'inscription_id' => $group['insc'][$i],
                        'date'           => $date,
                        'statut'         => $statut,
                        'observation'    => $this->pickObservation($statut),
                        'created_at'     => $now,
                        'updated_at'     => $now,
                    ]);
                }
            }
        }

        $this->command->info('');
        $this->command->table(
            ['Table', 'Lignes'],
            [
                ['users',        DB::table('users')->count()],
                ['formations',   DB::table('formations')->count()],
                ['inscriptions', DB::table('inscriptions')->count()],
                ['presences',    DB::table('presences')->count()],
                ['assiduites',   DB::table('assiduites')->count()],
                ['alertes',      DB::table('alertes')->count()],
            ]
        );
        $this->command->info('admin@assidua.fr / password');
    }

    private function weekdaysBetween(string $from, string $to, int $dayOfWeek): array
    {
        $dates = [];
        $cur   = Carbon::parse($from)->startOfDay();
        $end   = Carbon::parse($to)->startOfDay();
        while ($cur->dayOfWeek !== $dayOfWeek) {
            $cur->addDay();
        }
        while ($cur->lte($end)) {
            $dates[] = $cur->toDateString();
            $cur->addWeek();
        }
        return $dates;
    }

    private function pickStatut(string $profile): string
    {
        $weights = match ($profile) {
            'excellent'     => ['present' => 90, 'retard' => 5, 'absent' => 3, 'absent_justifie' => 2],
            'bon'           => ['present' => 74, 'retard' => 9, 'absent' => 12, 'absent_justifie' => 5],
            'fragile'       => ['present' => 57, 'retard' => 6, 'absent' => 30, 'absent_justifie' => 7],
            'problematique' => ['present' => 38, 'retard' => 5, 'absent' => 52, 'absent_justifie' => 5],
            default         => ['present' => 100, 'retard' => 0, 'absent' => 0, 'absent_justifie' => 0],
        };

        $roll = rand(1, 100);
        $cum  = 0;
        foreach ($weights as $statut => $w) {
            $cum += $w;
            if ($roll <= $cum) {
                return $statut;
            }
        }
        return 'present';
    }

    private function pickObservation(string $statut): ?string
    {
        return match ($statut) {
            'retard'          => $this->pick([null, null, 'Probleme de transport', 'Rendez-vous medical']),
            'absent'          => $this->pick([null, null, null, 'Maladie signalee', 'Sans nouvelle']),
            'absent_justifie' => $this->pick(['Certificat medical', 'Convocation officielle', 'Deuil familial', 'Hospitalisation']),
            default           => null,
        };
    }

    private function pick(array $items): mixed
    {
        return $items[array_rand($items)];
    }
}