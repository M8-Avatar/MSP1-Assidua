@extends('layouts.app')

@section('title', 'Modifier apprenant')
@section('page-title', 'Modifier un apprenant')
@section('page-subtitle', 'Mettre à jour les informations et la formation')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('apprenants.index') }}" class="btn btn-sm btn-outline-secondary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                </svg>
            </a>
            <h6 class="mb-0 fw-bold" style="color:#1B3A4B">
                {{ $apprenant->prenom }} {{ $apprenant->nom }}
            </h6>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('apprenants.update', $apprenant) }}">
                    @csrf
                    @method('PUT')

                    {{-- Identité --}}
                    <p class="fw-semibold mb-3" style="font-size:.8125rem;color:#1E8296;text-transform:uppercase;letter-spacing:.06em">Identité</p>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color:#1B3A4B">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom"
                                   value="{{ old('nom', $apprenant->nom) }}"
                                   class="form-control @error('nom') is-invalid @enderror"
                                   placeholder="Dupont">
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color:#1B3A4B">Prénom <span class="text-danger">*</span></label>
                            <input type="text" name="prenom"
                                   value="{{ old('prenom', $apprenant->prenom) }}"
                                   class="form-control @error('prenom') is-invalid @enderror"
                                   placeholder="Marie">
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Connexion --}}
                    <p class="fw-semibold mb-3 mt-4" style="font-size:.8125rem;color:#1E8296;text-transform:uppercase;letter-spacing:.06em">Connexion</p>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold" style="color:#1B3A4B">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email"
                               value="{{ old('email', $apprenant->email) }}"
                               class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-1">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color:#1B3A4B">Nouveau mot de passe</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Laisser vide pour ne pas changer">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color:#1B3A4B">Confirmer</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control"
                                   placeholder="Répéter si changement">
                        </div>
                    </div>
                    <p class="text-muted small mb-3 mt-1">Laissez les champs vides pour conserver le mot de passe actuel.</p>

                    {{-- Formation --}}
                    <p class="fw-semibold mb-3 mt-2" style="font-size:.8125rem;color:#1E8296;text-transform:uppercase;letter-spacing:.06em">Formation</p>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold" style="color:#1B3A4B">Formation inscrite</label>
                        <select name="formation_id" class="form-select @error('formation_id') is-invalid @enderror">
                            <option value="">— Aucune —</option>
                            @foreach($formations as $formation)
                                <option value="{{ $formation->id }}"
                                    {{ old('formation_id', $inscription?->formations_id) == $formation->id ? 'selected' : '' }}>
                                    {{ $formation->nom }}
                                    ({{ \Carbon\Carbon::parse($formation->date_debut)->format('d/m/Y') }}
                                    – {{ \Carbon\Carbon::parse($formation->date_fin)->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('formation_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($inscription?->assiduite)
                        <div class="alert alert-light border d-flex align-items-center gap-2 mb-4" style="font-size:.8125rem">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1E8296" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            Taux d'assiduité actuel :
                            @php $taux = $inscription->assiduite->taux; @endphp
                            @if($taux >= 75)
                                <span class="badge bg-success ms-1">{{ number_format($taux, 1) }}%</span>
                            @elseif($taux >= 50)
                                <span class="badge bg-warning text-dark ms-1">{{ number_format($taux, 1) }}%</span>
                            @else
                                <span class="badge bg-danger ms-1">{{ number_format($taux, 1) }}%</span>
                            @endif
                        </div>
                    @endif

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('apprenants.index') }}" class="btn btn-outline-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary" style="background:#1E8296;border-color:#1E8296">
                            Enregistrer les modifications
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

@endsection