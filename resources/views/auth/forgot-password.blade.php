@extends('layouts.guest')

@section('title', 'Mot de passe oublié')

@section('content')
    <div style="margin-bottom:32px">
        <h1 style="font-size:1.5rem;font-weight:700;color:#1B3A4B;margin-bottom:8px">Mot de passe oublié</h1>
        <p style="font-size:.875rem;color:#7A92A3;line-height:1.6">
            Saisissez votre adresse email et nous vous enverrons un lien de réinitialisation.
        </p>
    </div>

    @if (session('status'))
        <div style="background:#E8F5E9;border:1px solid #C8E6C9;color:#2E7D32;border-radius:8px;font-size:.8125rem;padding:10px 14px;margin-bottom:16px">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-4">
            <label class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B;margin-bottom:7px">
                Adresse email
            </label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="vous@exemple.fr" required autofocus>
            <x-input-error :messages="$errors->get('email')" />
        </div>
        <button type="submit" class="btn btn-primary w-100" style="font-size:.9375rem;padding:14px">
            Envoyer le lien de réinitialisation
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('login') }}" style="font-size:.8125rem;color:#1E8296;font-weight:500">
            ← Retour à la connexion
        </a>
    </div>
@endsection