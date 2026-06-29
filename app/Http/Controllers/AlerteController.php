<?php

namespace App\Http\Controllers;

use App\Models\Alerte;
use Illuminate\Http\Request;

class AlerteController extends Controller
{
    public function index(Request $request)
    {
        $filtre = $request->query('filtre', 'toutes');

        $query = Alerte::with(['assiduite.inscription.user', 'assiduite.inscription.formation'])
            ->orderByDesc('date_alerte');

        if ($filtre === 'non_vues') {
            $query->where('vue_admin', false);
        }

        $alertes     = $query->get();
        $nb_non_vues = Alerte::where('vue_admin', false)->count();
        $nb_total    = Alerte::count();

        return view('alertes.index', compact('alertes', 'filtre', 'nb_non_vues', 'nb_total'));
    }

    public function markAsRead(Alerte $alerte)
    {
        $alerte->update(['vue_admin' => true]);

        $filtre = request()->input('filtre', 'toutes');

        return redirect()
            ->route('alertes.index', ['filtre' => $filtre])
            ->with('success', 'Alerte marquée comme vue.');
    }
}
