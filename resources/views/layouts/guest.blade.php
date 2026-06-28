<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Connexion') — Assidua</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body style="margin:0;padding:0;">

<div class="guest-wrapper">

    {{-- Panneau gauche teal --}}
    <div class="guest-brand">
        {{-- Cercles décoratifs --}}
        <div style="position:absolute;top:-140px;right:-140px;width:560px;height:560px;border-radius:50%;background:rgba(255,255,255,0.055);pointer-events:none"></div>
        <div style="position:absolute;bottom:-100px;left:-100px;width:420px;height:420px;border-radius:50%;background:rgba(255,255,255,0.045);pointer-events:none"></div>

        {{-- Logo sur carte blanche --}}
        <div style="background:#fff;border-radius:20px;padding:26px 48px;box-shadow:0 24px 64px rgba(0,0,0,.22);margin-bottom:48px;position:relative;z-index:1">
            <span style="font-size:1.5rem;font-weight:700;color:#1E8296;letter-spacing:-0.02em;">Assidua</span>
        </div>

        <h2 style="color:#fff;font-size:1.4375rem;font-weight:700;text-align:center;max-width:400px;line-height:1.45;margin-bottom:14px;position:relative;z-index:1">
            Gérez l'assiduité de vos formations en toute simplicité
        </h2>
        <p style="color:rgba(255,255,255,.62);font-size:.90625rem;text-align:center;max-width:360px;line-height:1.65;position:relative;z-index:1">
            Suivi en temps réel, alertes automatiques, rapports détaillés — tout ce qu'il faut pour les organismes de formation professionnelle.
        </p>

        {{-- Stats fictives --}}
        <div style="display:flex;align-items:center;gap:40px;margin-top:52px;position:relative;z-index:1">
            <div style="text-align:center">
                <div style="color:#fff;font-size:1.875rem;font-weight:700;line-height:1">2 400+</div>
                <div style="color:rgba(255,255,255,.58);font-size:.75rem;margin-top:5px;letter-spacing:.03em">Apprenants suivis</div>
            </div>
            <div style="width:1px;height:38px;background:rgba(255,255,255,.2)"></div>
            <div style="text-align:center">
                <div style="color:#fff;font-size:1.875rem;font-weight:700;line-height:1">98%</div>
                <div style="color:rgba(255,255,255,.58);font-size:.75rem;margin-top:5px;letter-spacing:.03em">Satisfaction</div>
            </div>
            <div style="width:1px;height:38px;background:rgba(255,255,255,.2)"></div>
            <div style="text-align:center">
                <div style="color:#fff;font-size:1.875rem;font-weight:700;line-height:1">350+</div>
                <div style="color:rgba(255,255,255,.58);font-size:.75rem;margin-top:5px;letter-spacing:.03em">Organismes</div>
            </div>
        </div>
    </div>

    {{-- Panneau droit formulaire --}}
    <div class="guest-form-panel">
        <div style="width:100%;max-width:380px">
            @yield('content')
            <p style="text-align:center;font-size:.75rem;color:#AAB8C2;margin-top:44px">
                © {{ date('Y') }} Assidua · Tous droits réservés
            </p>
        </div>
    </div>

</div>

</body>
</html>