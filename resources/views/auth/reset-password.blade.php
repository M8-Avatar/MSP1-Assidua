@extends('layouts.guest')

@section('title', 'Nouveau mot de passe')

@section('content')
    <div style="margin-bottom:32px">
        <h1 style="font-size:1.5rem;font-weight:700;color:#1B3A4B;margin-bottom:8px">Nouveau mot de passe</h1>
        <p style="font-size:.875rem;color:#7A92A3">Choisissez un nouveau mot de passe sécurisé.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B;margin-bottom:7px">Email</label>
            <input type="email" name="email" value="{{ old('email', $request->email) }}"
                   class="form-control @error('email') is-invalid @enderror" required>
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B;margin-bottom:7px">Nouveau mot de passe</label>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="new-password">
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="mb-4">
            <label class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B;margin-bottom:7px">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation"
                   class="form-control" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-primary w-100" style="font-size:.9375rem;padding:14px">
            Réinitialiser le mot de passe
        </button>
    </form>
@endsection