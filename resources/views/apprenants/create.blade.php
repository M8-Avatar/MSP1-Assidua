@extends('layouts.app')

@section('title', 'Nouvel apprenant')
@section('page-title', 'Nouvel apprenant')
@section('page-subtitle', 'Créer un compte apprenant et l\'inscrire à une formation')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('apprenants.index') }}" class="btn btn-sm btn-outline-secondary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                </svg>
            </a>
            <h6 class="mb-0 fw-bold" style="color:#1B3A4B">Créer un apprenant</h6>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('apprenants.store') }}">
                    @csrf

                    {{-- Identité --}}
                    <p class="fw-semibold mb-3" style="font-size:.8125rem;color:#1E8296;text-transform:uppercase;letter-spacing:.06em">Identité</p>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color:#1B3A4B">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" value="{{ old('nom') }}"
                                   class="form-control @error('nom') is-invalid @enderror"
                                   placeholder="Dupont">
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color:#1B3A4B">Prénom <span class="text-danger">*</span></label>
                            <input type="text" name="prenom" value="{{ old('prenom') }}"
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
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="marie.dupont@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color:#1B3A4B">Mot de passe <span class="text-danger">*</span></label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min. 8 caractères">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold" style="color:#1B3A4B">Confirmer <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation"
                                   class="form-control"
                                   placeholder="Répéter le mot de passe">
                        </div>
                    </div>

                    {{-- Formation --}}
                    <p class="fw-semibold mb-3 mt-4" style="font-size:.8125rem;color:#1E8296;text-transform:uppercase;letter-spacing:.06em">Formation</p>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold" style="color:#1B3A4B">Inscrire à une formation</label>
                        <select name="formation_id" class="form-select @error('formation_id') is-invalid @enderror">
                            <option value="">— Aucune (inscription ultérieure) —</option>
                            @foreach($formations as $formation)
                                <option value="{{ $formation->id }}" {{ old('formation_id') == $formation->id ? 'selected' : '' }}>
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

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('apprenants.index') }}" class="btn btn-outline-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary" style="background:#1E8296;border-color:#1E8296">
                            Créer l'apprenant
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

@endsection