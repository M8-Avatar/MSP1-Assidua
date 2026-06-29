@extends('layouts.app')

@section('title', 'Nouvelle formation')
@section('page-title', 'Formations')
@section('page-subtitle', 'Créer une nouvelle formation')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm" style="border-radius:10px">
            <div class="card-body p-4">

                <h6 class="mb-4" style="font-weight:700;color:#1B3A4B;font-size:.9375rem">Nouvelle formation</h6>

                <form method="POST" action="{{ route('formations.store') }}">
                    @csrf
                <div class="mb-3">
                    <label for="nom" class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B">Nom de la formation</label>
                    <input type="text" id="nom" name="nom"
                           class="form-control @error('nom') is-invalid @enderror"
                           style="border-radius:6px;border-color:#DDE3EA;font-size:.875rem"
                           value="{{ old('nom', $formation->nom ?? '') }}"
                           placeholder="Ex : Formation développeur web" required>
                    @error('nom')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="date_debut" class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B">Date de début</label>
                        <input type="date" id="date_debut" name="date_debut"
                               class="form-control @error('date_debut') is-invalid @enderror"
                               style="border-radius:6px;border-color:#DDE3EA;font-size:.875rem"
                               value="{{ old('date_debut', isset($formation) ? $formation->date_debut : '') }}" required>
                        @error('date_debut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="date_fin" class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B">Date de fin</label>
                        <input type="date" id="date_fin" name="date_fin"
                               class="form-control @error('date_fin') is-invalid @enderror"
                               style="border-radius:6px;border-color:#DDE3EA;font-size:.875rem"
                               value="{{ old('date_fin', isset($formation) ? $formation->date_fin : '') }}" required>
                        @error('date_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <a href="{{ route('formations.index') }}"
                           class="btn btn-sm"
                           style="background:#F4F6F9;color:#64788A;border-radius:6px;font-size:.8125rem;padding:7px 16px;border:1px solid #EDF0F5">
                            Annuler
                        </a>
                        <button type="submit"
                                class="btn btn-sm"
                                style="background:#1E8296;color:#fff;border-radius:6px;font-size:.8125rem;padding:7px 20px">
                            Créer la formation
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection