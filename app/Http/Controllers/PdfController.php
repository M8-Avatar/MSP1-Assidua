<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PdfController extends Controller
{
    public function generateFeuillePresence(Request $request, int $formation_id, string $date)
    {
        $formation = DB::table('formations')->where('id', $formation_id)->first();

        abort_if(! $formation, 404);

        $presences = DB::table('presences')
            ->join('inscriptions', 'presences.inscription_id', '=', 'inscriptions.id')
            ->join('users', 'inscriptions.user_id', '=', 'users.id')
            ->where('inscriptions.formations_id', $formation_id)
            ->where('presences.date', $date)
            ->select('users.name', 'presences.statut', 'presences.observation')
            ->orderBy('users.name')
            ->get();

        $total     = $presences->count();
        $presents  = $presences->whereIn('statut', ['present', 'retard'])->count();
        $taux      = $total > 0 ? round($presents / $total * 100, 1) : 0;
        $formateur = $request->user()->name;

        $pdf = Pdf::loadView('pdf.feuille-presence', compact(
            'formation', 'presences', 'date', 'formateur', 'taux', 'total', 'presents'
        ))->setPaper('a4', 'portrait');

        $filename = 'presence_' . $formation_id . '_' . $date . '.pdf';

        return $pdf->download($filename);
    }
}
