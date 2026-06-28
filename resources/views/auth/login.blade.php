@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')
    <div style="margin-bottom:40px">
        <h1 style="font-size:1.6875rem;font-weight:700;color:#1B3A4B;margin-bottom:8px">Bon retour !</h1>
        <p style="font-size:.90625rem;color:#7A92A3">Connectez-vous à votre espace</p>
    </div>

    @if (session('status'))
        <div style="background:#E8F5E9;border:1px solid #C8E6C9;color:#2E7D32;border-radius:8px;font-size:.8125rem;padding:10px 14px;margin-bottom:16px">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B;margin-bottom:7px">
                Adresse email
            </label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="vous@exemple.fr" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="mb-2">
            <label class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B;margin-bottom:7px">
                Mot de passe
            </label>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="••••••••" required autocomplete="current-password">
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="text-end mb-4">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size:.8125rem;color:#1E8296;font-weight:500">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
            <label class="form-check-label" for="remember_me" style="font-size:.8125rem;color:#64788A">
                Se souvenir de moi
            </label>
        </div>

        <button type="submit" class="btn btn-primary w-100" style="font-size:.9375rem;padding:14px">
            Se connecter
        </button>
    </form>
@endsection