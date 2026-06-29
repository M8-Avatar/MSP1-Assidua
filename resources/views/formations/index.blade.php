@extends('layouts.app')

@section('title', 'Formations')
@section('page-title', 'Formations')
@section('page-subtitle', 'Liste de toutes les formations')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div></div>
    <a href="{{ route('formations.create') }}"
       class="btn btn-sm d-flex align-items-center gap-2"
       style="background:#1E8296;color:#fff;border-radius:6px;font-size:.8125rem;padding:7px 16px">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Nouvelle formation
    </a>
</div>

<div class="card border-0 shadow-sm" style="border-radius:10px">
    <div class="card-body p-0">
        <table class="table mb-0" style="font-size:.875rem">
            <thead>
                <tr style="background:#F4F6F9;border-bottom:1px solid #EDF0F5">
                    <th class="px-4 py-3" style="font-weight:600;color:#64788A;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;border:none">Nom</th>
                    <th class="px-3 py-3" style="font-weight:600;color:#64788A;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;border:none">Date début</th>
                    <th class="px-3 py-3" style="font-weight:600;color:#64788A;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;border:none">Date fin</th>
                    <th class="px-3 py-3 text-center" style="font-weight:600;color:#64788A;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;border:none">Apprenants inscrits</th>
                    <th class="px-4 py-3 text-end" style="font-weight:600;color:#64788A;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;border:none">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($formations as $formation)
                <tr style="border-bottom:1px solid #EDF0F5">
                    <td class="px-4 py-3" style="font-weight:500;color:#1B3A4B;border:none">{{ $formation->nom }}</td>
                    <td class="px-3 py-3" style="color:#64788A;border:none">{{ \Carbon\Carbon::parse($formation->date_debut)->format('d/m/Y') }}</td>
                    <td class="px-3 py-3" style="color:#64788A;border:none">{{ \Carbon\Carbon::parse($formation->date_fin)->format('d/m/Y') }}</td>
                    <td class="px-3 py-3 text-center" style="border:none">
                        <span class="badge" style="background:#EAF6F8;color:#1E8296;font-size:.75rem;padding:4px 10px;border-radius:20px;font-weight:500">
                            {{ $formation->inscriptions_count }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-end" style="border:none">
                        <a href="{{ route('formations.edit', $formation) }}"
                           class="btn btn-sm me-1"
                           style="background:#F4F6F9;color:#1E8296;border-radius:5px;padding:4px 12px;font-size:.8rem;border:1px solid #EDF0F5">
                            Modifier
                        </a>
                        <form method="POST" action="{{ route('formations.destroy', $formation) }}" class="d-inline"
                              onsubmit="return confirm('Supprimer cette formation ? Les inscriptions associées seront également supprimées.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-sm"
                                    style="background:#FFF0F0;color:#E53935;border-radius:5px;padding:4px 12px;font-size:.8rem;border:1px solid #FDDEDE">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-5 text-center" style="color:#96A8B8;border:none">
                        Aucune formation enregistrée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection