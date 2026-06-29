<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Inscription;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresenceController extends Controller
{
    public function index(Request $request)
    {
        $formations   = Formation::orderBy('nom')->get();
        $formation_id = $request->get('formation_id');
        $date         = $request->get('date', now()->toDateString());

        $formation    = null;
        $inscriptions = collect();
        $presences    = collect();

        if ($formation_id) {
            $formation = Formation::find($formation_id);

            if ($formation) {
                $inscriptions = Inscription::with('user')
                    ->where('formations_id', $formation_id)
                    ->get();

                $presences = Presence::whereIn('inscription_id', $inscriptions->pluck('id'))
                    ->where('date', $date)
                    ->get()
                    ->keyBy('inscription_id');
            }
        }

        return view('presences.index', compact(
            'formations', 'formation', 'inscriptions', 'presences', 'formation_id', 'date'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'formation_id'               => 'required|exists:formations,id',
            'date'                       => 'required|date',
            'presences'                  => 'required|array',
            'presences.*.inscription_id' => 'required|exists:inscriptions,id',
            'presences.*.statut'         => 'required|in:present,absent,retard,absent_justifie',
            'presences.*.observation'    => 'nullable|string|max:255',
        ], [
            'formation_id.required'      => 'Veuillez sélectionner une formation.',
            'formation_id.exists'        => 'La formation sélectionnée est invalide.',
            'date.required'              => 'La date de séance est obligatoire.',
            'date.date'                  => 'La date de séance n\'est pas valide.',
            'presences.required'         => 'Aucune présence à enregistrer.',
            'presences.*.statut.required' => 'Le statut de chaque apprenant est obligatoire.',
            'presences.*.statut.in'      => 'Le statut sélectionné est invalide.',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->presences as $p) {
                Presence::updateOrCreate(
                    [
                        'inscription_id' => $p['inscription_id'],
                        'date'           => $request->date,
                    ],
                    [
                        'statut'      => $p['statut'],
                        'observation' => $p['observation'] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('presences.index', [
                'formation_id' => $request->formation_id,
                'date'         => $request->date,
            ])
            ->with('success', 'Séance enregistrée avec succès.');
    }
}