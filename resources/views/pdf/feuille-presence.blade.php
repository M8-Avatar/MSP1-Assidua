<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Feuille de présence — {{ $formation->nom }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px;
        color: #1B3A4B;
        background: #fff;
        padding: 32px 36px;
    }

    /* En-tête */
    .header { margin-bottom: 24px; border-bottom: 2px solid #1E8296; padding-bottom: 16px; }
    .header-top { display: flex; justify-content: space-between; align-items: flex-start; }
    .brand { font-size: 22px; font-weight: 700; color: #1E8296; letter-spacing: -0.5px; }
    .brand-sub { font-size: 8px; color: #96A8B8; text-transform: uppercase; letter-spacing: 1px; margin-top: 2px; }
    .header-meta { text-align: right; }
    .header-meta .doc-title { font-size: 13px; font-weight: 700; color: #1B3A4B; margin-bottom: 4px; }
    .header-meta .doc-info { font-size: 8.5px; color: #5A7A8A; line-height: 1.7; }

    .formation-block { margin-top: 14px; background: #F0F9FB; border-left: 3px solid #1E8296; padding: 10px 14px; }
    .formation-name { font-size: 13px; font-weight: 700; color: #1B3A4B; }
    .formation-details { font-size: 8.5px; color: #5A7A8A; margin-top: 4px; line-height: 1.8; }

    /* Tableau */
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    thead tr { background: #1E8296; }
    thead th {
        color: #fff;
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 8px 10px;
        text-align: left;
        border: none;
    }
    thead th.center { text-align: center; }
    tbody tr:nth-child(even) { background: #F7FBFC; }
    tbody tr:nth-child(odd)  { background: #fff; }
    tbody td {
        padding: 7px 10px;
        font-size: 9px;
        color: #1B3A4B;
        border-bottom: 1px solid #EDF0F5;
        vertical-align: middle;
    }
    tbody td.center { text-align: center; }

    /* Badges statut */
    .badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 10px;
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge-present  { background: #E8F5E9; color: #2E7D32; }
    .badge-absent   { background: #FFEBEE; color: #C62828; }
    .badge-retard   { background: #FFF3E0; color: #E65100; }
    .badge-justifie { background: #E3F2FD; color: #1565C0; }

    /* Pied de page */
    .footer {
        margin-top: 28px;
        border-top: 1px solid #EDF0F5;
        padding-top: 14px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }
    .taux-block { }
    .taux-label { font-size: 8px; color: #96A8B8; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 4px; }
    .taux-value { font-size: 24px; font-weight: 700; color: #1E8296; line-height: 1; }
    .taux-detail { font-size: 8px; color: #5A7A8A; margin-top: 3px; }
    .footer-right { text-align: right; font-size: 7.5px; color: #96A8B8; line-height: 1.8; }

    .no-data { text-align: center; color: #96A8B8; padding: 24px; font-style: italic; font-size: 9px; }
</style>
</head>
<body>

{{-- En-tête --}}
<div class="header">
    <div class="header-top">
        <div>
            @php
                $logoPath = public_path('images/logo-horizontal.png');
                $logoSrc  = file_exists($logoPath)
                    ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
                    : '';
            @endphp
            @if($logoSrc)
                <img src="{{ $logoSrc }}" style="height:48px;width:auto;display:block" alt="Assidua">
            @else
                <div class="brand">Assidua</div>
            @endif
            <div class="brand-sub">Gestion des présences</div>
        </div>
        <div class="header-meta">
            <div class="doc-title">Feuille de présence</div>
            <div class="doc-info">
                Séance du : <strong>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</strong><br>
                Formateur : <strong>{{ $formateur }}</strong>
            </div>
        </div>
    </div>
    <div class="formation-block">
        <div class="formation-name">{{ $formation->nom }}</div>
        <div class="formation-details">
            Période : {{ \Carbon\Carbon::parse($formation->date_debut)->format('d/m/Y') }}
            → {{ \Carbon\Carbon::parse($formation->date_fin)->format('d/m/Y') }}
        </div>
    </div>
</div>

{{-- Tableau des présences --}}
<table>
    <thead>
        <tr>
            <th style="width:40%">Nom / Prénom</th>
            <th class="center" style="width:22%">Statut</th>
            <th style="width:38%">Observation</th>
        </tr>
    </thead>
    <tbody>
        @forelse($presences as $p)
        @php
            $badgeClass = match($p->statut) {
                'present'         => 'badge-present',
                'absent'          => 'badge-absent',
                'retard'          => 'badge-retard',
                'absent_justifie' => 'badge-justifie',
                default           => 'badge-absent',
            };
            $label = match($p->statut) {
                'present'         => 'Présent',
                'absent'          => 'Absent',
                'retard'          => 'Retard',
                'absent_justifie' => 'Absent justifié',
                default           => $p->statut,
            };
        @endphp
        <tr>
            <td style="font-weight:600">{{ $p->name }}</td>
            <td class="center">
                <span class="badge {{ $badgeClass }}">{{ $label }}</span>
            </td>
            <td style="color:#5A7A8A">{{ $p->observation ?? '—' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="no-data">Aucune présence enregistrée pour cette séance.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Pied de page --}}
<div class="footer">
    <div class="taux-block">
        <div class="taux-label">Taux d'assiduité de la séance</div>
        <div class="taux-value">{{ $taux }}%</div>
        <div class="taux-detail">{{ $presents }} présent(s) sur {{ $total }} apprenant(s)</div>
    </div>
    <div class="footer-right">
        Document généré le {{ now()->format('d/m/Y à H:i') }}<br>
        Assidua — Système de gestion des présences
    </div>
</div>

</body>
</html>
