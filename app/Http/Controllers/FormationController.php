<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;

class FormationController extends Controller
{
    private function validationMessages(): array
    {
        return [
            'nom.required'            => 'Le nom de la formation est obligatoire.',
            'nom.max'                 => 'Le nom ne peut pas dépasser 255 caractères.',
            'date_debut.required'     => 'La date de début est obligatoire.',
            'date_debut.date'         => 'La date de début n\'est pas valide.',
            'date_fin.required'       => 'La date de fin est obligatoire.',
            'date_fin.date'           => 'La date de fin n\'est pas valide.',
            'date_fin.after_or_equal' => 'La date de fin doit être égale ou postérieure à la date de début.',
        ];
    }

    public function index()
    {
        $formations = Formation::withCount('inscriptions')->orderBy('date_debut', 'desc')->get();
        return view('formations.index', compact('formations'));
    }

    public function create()
    {
        return view('formations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'        => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after_or_equal:date_debut',
        ], $this->validationMessages());

        Formation::create($data);
        return redirect()->route('formations.index')->with('success', 'Formation créée avec succès.');
    }

    public function show(Formation $formation)
    {
        $formation->load(['inscriptions.user', 'inscriptions.assiduite']);
        return view('formations.show', compact('formation'));
    }

    public function edit(Formation $formation)
    {
        return view('formations.edit', compact('formation'));
    }

    public function update(Request $request, Formation $formation)
    {
        $data = $request->validate([
            'nom'        => 'required|string|max:255',
            'date_debut' => 'required|date',
            'date_fin'   => 'required|date|after_or_equal:date_debut',
        ], $this->validationMessages());

        $formation->update($data);
        return redirect()->route('formations.index')->with('success', 'Formation modifiée avec succès.');
    }

    public function destroy(Formation $formation)
    {
        $formation->delete();
        return redirect()->route('formations.index')->with('success', 'Formation supprimée.');
    }
}