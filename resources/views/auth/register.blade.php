@extends('layouts.guest')

@section('title', 'Créer un compte')

@section('content')
    <div style="margin-bottom:32px">
        <h1 style="font-size:1.5rem;font-weight:700;color:#1B3A4B;margin-bottom:8px">Créer un compte</h1>
        <p style="font-size:.875rem;color:#7A92A3">Renseignez vos informations pour accéder à Assidua.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B;margin-bottom:7px">
                Nom complet
            </label>
            <input type="text" id="name" name="name" value="{{ old('name') }}"
                   class="form-control @error('name') is-invalid @enderror"
                   placeholder="Marie Dupont" required autofocus autocomplete="name">
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div class="mb-3">
            <label for="email" class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B;margin-bottom:7px">
                Adresse email
            </label>
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="vous@exemple.fr" required autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="mb-3">
            <label for="password" class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B;margin-bottom:7px">
                Mot de passe
            </label>
            <input type="password" id="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
            <p class="mt-1" style="font-size:.75rem;color:#96A8B8">
                8 caractères minimum, une majuscule, un chiffre, un caractère spécial
            </p>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B;margin-bottom:7px">
                Confirmer le mot de passe
            </label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="form-control" required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <button type="submit" class="btn btn-primary w-100" style="font-size:.9375rem;padding:14px">
            Créer mon compte
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('login') }}" style="font-size:.8125rem;color:#1E8296;font-weight:500">
            Déjà inscrit ? Se connecter
        </a>
    </div>
@endsection