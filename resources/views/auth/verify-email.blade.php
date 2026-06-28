@extends('layouts.guest')

@section('title', 'Vérification email')

@section('content')
    <div style="margin-bottom:24px">
        <h1 style="font-size:1.5rem;font-weight:700;color:#1B3A4B;margin-bottom:8px">Vérifiez votre email</h1>
        <p style="font-size:.875rem;color:#7A92A3;line-height:1.6">
            Un lien de vérification a été envoyé à votre adresse email. Consultez vos spams si vous ne le trouvez pas.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div style="background:#E8F5E9;border:1px solid #C8E6C9;color:#2E7D32;border-radius:8px;font-size:.8125rem;padding:10px 14px;margin-bottom:16px">
            Un nouveau lien de vérification vous a été envoyé.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary w-100 mb-3" style="padding:14px">
            Renvoyer le lien
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-link w-100" style="color:#64788A;font-size:.875rem">
            Se déconnecter
        </button>
    </form>
@endsection