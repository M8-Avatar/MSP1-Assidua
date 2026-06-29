<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Inscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ApprenantController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'apprenant')
            ->with(['inscriptions.formation', 'inscriptions.assiduite']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('formation_id')) {
            $query->whereHas('inscriptions', function ($q) use ($request) {
                $q->where('formations_id', $request->formation_id);
            });
        }

        $apprenants = $query->orderBy('nom')->paginate(15)->withQueryString();
        $formations = Formation::orderBy('nom')->get();

        return view('apprenants.index', compact('apprenants', 'formations'));
    }

    public function create()
    {
        $formations = Formation::orderBy('nom')->get();
        return view('apprenants.create', compact('formations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'          => 'required|string|max:100',
            'prenom'       => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email',
            'password'     => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()],
            'formation_id' => 'nullable|exists:formations,id',
        ], [
            'nom.required'       => 'Le nom est obligatoire.',
            'prenom.required'    => 'Le prénom est obligatoire.',
            'email.required'     => "L'adresse e-mail est obligatoire.",
            'email.email'        => "L'adresse e-mail n'est pas valide.",
            'email.unique'       => 'Cette adresse e-mail est déjà utilisée.',
            'password.required'  => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $user = User::create([
            'nom'      => $validated['nom'],
            'prenom'   => $validated['prenom'],
            'name'     => $validated['prenom'] . ' ' . $validated['nom'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'apprenant',
        ]);

        if (!empty($validated['formation_id'])) {
            Inscription::create([
                'user_id'       => $user->id,
                'formations_id' => $validated['formation_id'],
            ]);
        }

        return redirect()->route('apprenants.index')
            ->with('success', 'Apprenant créé avec succès.');
    }

    public function show(User $apprenant)
    {
        abort_if($apprenant->role !== 'apprenant', 404);

        $apprenant->load([
            'inscriptions.formation',
            'inscriptions.assiduite',
            'inscriptions.presences',
        ]);

        return view('apprenants.show', compact('apprenant'));
    }

    public function edit(User $apprenant)
    {
        abort_if($apprenant->role !== 'apprenant', 404);

        $formations  = Formation::orderBy('nom')->get();
        $inscription = $apprenant->inscriptions()->with('formation')->first();

        return view('apprenants.edit', compact('apprenant', 'formations', 'inscription'));
    }

    public function update(Request $request, User $apprenant)
    {
        abort_if($apprenant->role !== 'apprenant', 404);

        $validated = $request->validate([
            'nom'          => 'required|string|max:100',
            'prenom'       => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email,' . $apprenant->id,
            'password'     => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'formation_id' => 'nullable|exists:formations,id',
        ], [
            'nom.required'       => 'Le nom est obligatoire.',
            'prenom.required'    => 'Le prénom est obligatoire.',
            'email.required'     => "L'adresse e-mail est obligatoire.",
            'email.email'        => "L'adresse e-mail n'est pas valide.",
            'email.unique'       => 'Cette adresse e-mail est déjà utilisée.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $data = [
            'nom'    => $validated['nom'],
            'prenom' => $validated['prenom'],
            'name'   => $validated['prenom'] . ' ' . $validated['nom'],
            'email'  => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $apprenant->update($data);

        if (!empty($validated['formation_id'])) {
            $inscription = $apprenant->inscriptions()->first();
            if ($inscription) {
                $inscription->update(['formations_id' => $validated['formation_id']]);
            } else {
                Inscription::create([
                    'user_id'       => $apprenant->id,
                    'formations_id' => $validated['formation_id'],
                ]);
            }
        }

        return redirect()->route('apprenants.index')
            ->with('success', 'Apprenant mis à jour avec succès.');
    }

    public function destroy(User $apprenant)
    {
        abort_if($apprenant->role !== 'apprenant', 404);
        $apprenant->delete();

        return redirect()->route('apprenants.index')
            ->with('success', 'Apprenant supprimé avec succès.');
    }
}