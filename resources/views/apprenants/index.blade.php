@extends('layouts.app')

@section('title', 'Apprenants')
@section('page-title', 'Apprenants')
@section('page-subtitle', 'Gestion des apprenants inscrits')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Filtres --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('apprenants.index') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-semibold text-muted mb-1">Recherche</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#96A8B8" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control border-start-0 ps-0" placeholder="Nom, prénom, email…">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted mb-1">Formation</label>
                <select name="formation_id" class="form-select">
                    <option value="">Toutes les formations</option>
                    @foreach($formations as $formation)
                        <option value="{{ $formation->id }}" {{ request('formation_id') == $formation->id ? 'selected' : '' }}>
                            {{ $formation->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill" style="background:#1E8296;border-color:#1E8296">
                    Filtrer
                </button>
                @if(request('search') || request('formation_id'))
                    <a href="{{ route('apprenants.index') }}" class="btn btn-outline-secondary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- En-tête liste --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="text-muted small">
        <span class="fw-semibold text-dark">{{ $apprenants->total() }}</span> apprenant(s)
    </div>
    <a href="{{ route('apprenants.create') }}" class="btn btn-sm btn-primary" style="background:#1E8296;border-color:#1E8296">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Nouvel apprenant
    </a>
</div>

{{-- Tableau --}}
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:.875rem">
            <thead style="background:#F8FAFB;border-bottom:2px solid #EDF0F5">
                <tr>
                    <th class="px-4 py-3 fw-semibold text-muted" style="font-size:.75rem;letter-spacing:.05em;text-transform:uppercase">Nom</th>
                    <th class="py-3 fw-semibold text-muted" style="font-size:.75rem;letter-spacing:.05em;text-transform:uppercase">Prénom</th>
                    <th class="py-3 fw-semibold text-muted" style="font-size:.75rem;letter-spacing:.05em;text-transform:uppercase">Formation</th>
                    <th class="py-3 fw-semibold text-muted" style="font-size:.75rem;letter-spacing:.05em;text-transform:uppercase">Taux</th>
                    <th class="py-3 fw-semibold text-muted" style="font-size:.75rem;letter-spacing:.05em;text-transform:uppercase">Statut</th>
                    <th class="py-3 pe-4 fw-semibold text-muted text-end" style="font-size:.75rem;letter-spacing:.05em;text-transform:uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($apprenants as $apprenant)
                    @php
                        $inscription = $apprenant->inscriptions->first();
                        $taux        = $inscription?->assiduite?->taux ?? null;
                        $formation   = $inscription?->formation;
                        $actif       = $formation && \Carbon\Carbon::parse($formation->date_fin)->isFuture();
                    @endphp
                    <tr>
                        <td class="px-4 py-3 fw-semibold" style="color:#1B3A4B">
                            {{ $apprenant->nom ?? '—' }}
                        </td>
                        <td class="py-3" style="color:#1B3A4B">
                            {{ $apprenant->prenom ?? '—' }}
                        </td>
                        <td class="py-3 text-muted">
                            {{ $formation?->nom ?? '—' }}
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
                        <td class="py-3">
                            @if($actif)
                                <span class="badge rounded-pill" style="background:#E6F4F1;color:#1E8296;font-weight:600">Actif</span>
                            @else
                                <span class="badge rounded-pill bg-secondary bg-opacity-25 text-secondary">Inactif</span>
                            @endif
                        </td>
                        <td class="py-3 pe-4 text-end">
                            <a href="{{ route('apprenants.edit', $apprenant) }}"
                               class="btn btn-sm btn-outline-secondary me-1" title="Modifier">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('apprenants.destroy', $apprenant) }}" class="d-inline"
                                  onsubmit="return confirm('Supprimer {{ addslashes($apprenant->prenom . ' ' . $apprenant->nom) }} ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14H6L5 6"/>
                                        <path d="M10 11v6M14 11v6"/>
                                        <path d="M9 6V4h6v2"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#96A8B8" stroke-width="1.5" class="mb-2 d-block mx-auto">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            Aucun apprenant trouvé.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($apprenants->hasPages())
        <div class="card-footer bg-white border-top border-light px-4 py-3">
            {{ $apprenants->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection