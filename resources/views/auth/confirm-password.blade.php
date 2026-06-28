@extends('layouts.guest')

@section('title', 'Confirmation')

@section('content')
    <div style="margin-bottom:32px">
        <h1 style="font-size:1.5rem;font-weight:700;color:#1B3A4B;margin-bottom:8px">Confirmez votre identité</h1>
        <p style="font-size:.875rem;color:#7A92A3">Zone sécurisée — saisissez votre mot de passe pour continuer.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="mb-4">
            <label class="form-label" style="font-size:.8125rem;font-weight:600;color:#1B3A4B;margin-bottom:7px">Mot de passe</label>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="current-password">
            <x-input-error :messages="$errors->get('password')" />
        </div>
        <button type="submit" class="btn btn-primary w-100" style="padding:14px">Confirmer</button>
    </form>
@endsection