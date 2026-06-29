@extends('layouts.app')

@section('title', $formation->nom)
@section('page-title', $formation->nom)
@section('page-subtitle', 'Détail de la formation')

@section('content')

{{-- Actions --}}
<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('formations.index') }}" class="btn btn-sm btn-outline-secondary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
        </svg>
        Retour
    </a>
    <a href="{{ route('presences.index', ['formation_id' => $formation->id]) }}"
       class="btn btn-sm btn-primary ms-auto" style="background:#1E8296;border-color:#1E8296">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
            <path d="M9 11l3 3L22 4"/>
            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
        </svg>
        Saisir les présences
    </a>
    <a href="{{ route('formations.edit', $formation) }}" class="btn btn-sm btn-outline-secondary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>
        Modifier
    </a>
</div>

{{-- Card infos --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div style="font-size:1.75rem;font-weight:700;color:#1E8296">
                {{ $formation->inscriptions->count() }}
            </div>
            <div class="text-muted small">Apprenants inscrits</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div style="font-size:1rem;font-weight:600;color:#1B3A4B">
                {{ \Carbon\Carbon::parse($formation->date_debut)->format('d/m/Y') }}
            </div>
            <div class="text-muted small">Date de début</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div style="font-size:1rem;font-weight:600;color:#1B3A4B">
                {{ \Carbon\Carbon::parse($formation->date_fin)->format('d/m/Y') }}
            </div>
            <div class="text-muted small">Date de fin</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            @php $actif = \Carbon\Carbon::parse($formation->date_fin)->isFuture(); @endphp
            @if($actif)
                <div style="font-size:1rem;font-weight:600">
                    <span class="badge rounded-pill" style="background:#E6F4F1;color:#1E8296;font-size:.875rem;font-weight:600">En cours</span>
                </div>
            @else
                <div style="font-size:1rem;font-weight:600">
                    <span class="badge rounded-pill bg-secondary bg-opacity-25 text-secondary font-size:.875rem">Terminée</span>
                </div>
            @endif
            <div class="text-muted small mt-1">Statut</div>
        </div>
    </div>
</div>

{{-- Tableau apprenants --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom border-light px-4 py-3 d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-semibold" style="color:#1B3A4B;font-size:.875rem">Apprenants inscrits</h6>
        <span class="badge bg-light text-muted" style="font-size:.75rem">{{ $formation->inscriptions->count() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:.875rem">
            <thead style="background:#F8FAFB;border-bottom:2px solid #EDF0F5">
                <tr>
                    <th class="px-4 py-3 fw-semibold text-muted" style="font-size:.75rem;letter-spacing:.05em;text-transform:uppercase">Nom</th>
                    <th class="py-3 fw-semibold text-muted" style="font-size:.75rem;letter-spacing:.05em;text-transform:uppercase">Prénom</th>
                    <th class="py-3 fw-semibold text-muted" style="font-size:.75rem;letter-spacing:.05em;text-transform:uppercase">Email</th>
                    <th class="py-3 fw-semibold text-muted" style="font-size:.75rem;letter-spacing:.05em;text-transform:uppercase">Taux</th>
                    <th class="py-3 pe-4 fw-semibold text-muted" style="font-size:.75rem;letter-spacing:.05em;text-transform:uppercase">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($formation->inscriptions as $inscription)
                    @php
                        $user  = $inscription->user;
                        $taux  = $inscription->assiduite?->taux ?? null;
                    @endphp
                    <tr>
                        <td class="px-4 py-3 fw-semibold" style="color:#1B3A4B">
                            {{ $user->nom ?? '—' }}
                        </td>
                        <td class="py-3" style="color:#1B3A4B">
                            {{ $user->prenom ?? '—' }}
                        </td>
                        <td class="py-3 text-muted" style="font-size:.8125rem">
                            {{ $user->email }}
                        </td>
                        <td class="py-3">
                            @if($taux !== null)
                                @if($taux >= 75)
                                    <span class="badge rounded-pill bg-success">{{ number_format($taux, 1) }}%</span>
                                @elseif($taux >= 50)
                                    <span class="badge rounded-pill bg-warning text-dark">{{ number_format($taux, 1) }}%</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">{{ number_format($taux, 1) }}%</span>
                                @endif
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="py-3 pe-4">
                            @if($actif)
                                <span class="badge rounded-pill" style="background:#E6F4F1;color:#1E8296;font-weight:600">Actif</span>
                            @else
                                <span class="badge rounded-pill bg-secondary bg-opacity-25 text-secondary">Inactif</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#96A8B8" stroke-width="1.5" class="mb-2 d-block mx-auto">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                            </svg>
                            Aucun apprenant inscrit.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection