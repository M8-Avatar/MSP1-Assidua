<?php

namespace App\Http\Controllers;

use App\Models\Alerte;
use App\Models\Formation;
use App\Models\Inscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        try {
            $stats = [
                'apprenants'   => User::where('role', 'apprenant')->count(),
                'formations'   => Formation::count(),
                'seances_mois' => (int) DB::table('presences')
                                    ->whereMonth('date', now()->month)
                                    ->whereYear('date', now()->year)
                                    ->distinct('date')
                                    ->count(),
                'alertes'      => Alerte::where('vue_admin', false)->count(),
            ];

            $sessions_recentes = DB::table('presences')
                ->join('inscriptions', 'presences.inscription_id', '=', 'inscriptions.id')
                ->join('formations', 'inscriptions.formations_id', '=', 'formations.id')
                ->select(
                    'presences.date',
                    'formations.id as formation_id',
                    'formations.nom',
                    DB::raw('COUNT(*) as nb_presences')
                )
                ->groupBy('presences.date', 'formations.id', 'formations.nom')
                ->orderByDesc('presences.date')
                ->limit(8)
                ->get();

            $alertes_recentes = Alerte::with(['assiduite.inscription.user', 'assiduite.inscription.formation'])
                ->where('vue_admin', false)
                ->orderByDesc('date_alerte')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $stats = ['apprenants' => 0, 'formations' => 0, 'seances_mois' => 0, 'alertes' => 0];
            $sessions_recentes = collect();
            $alertes_recentes  = collect();
        }

        return view('dashboard.admin', compact('stats', 'sessions_recentes', 'alertes_recentes'));
    }

    public function apprenant()
    {
        try {
            $inscription = Inscription::where('user_id', auth()->id())
                ->with([
                    'formation',
                    'assiduite',
                    'presences' => fn($q) => $q->orderByDesc('date'),
                ])
                ->first();

            $taux = (float) ($inscription?->assiduite?->taux ?? 0);

            $presences_counts = ['present' => 0, 'absent' => 0, 'retard' => 0, 'absent_justifie' => 0];
            if ($inscription) {
                foreach ($inscription->presences as $p) {
                    if (array_key_exists($p->statut, $presences_counts)) {
                        $presences_counts[$p->statut]++;
                    }
                }
            }
        } catch (\Exception $e) {
            $inscription      = null;
            $taux             = 0.0;
            $presences_counts = ['present' => 0, 'absent' => 0, 'retard' => 0, 'absent_justifie' => 0];
        }

        return view('dashboard.apprenant', compact('inscription', 'taux', 'presences_counts'));
    }
}