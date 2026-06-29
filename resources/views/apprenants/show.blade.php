@extends('layouts.app')

@section('title', $apprenant->prenom . ' ' . $apprenant->nom)
@section('page-title', 'Apprenants')
@section('page-subtitle', $apprenant->prenom . ' ' . $apprenant->nom . ' — Fiche détail')

@section('content')

@php
    $inscription = $apprenant->inscriptions->first();
    $formation   = $inscription?->formation;
    $taux        = (float) ($inscription?->assiduite?->taux ?? 0);
    $tauxPct     = min(100, max(0, $taux));
    $tauxBg      = $taux >= 75 ? 'bg-success' : ($taux >= 50 ? 'bg-warning' : 'bg-danger');
    $tauxColor   = $taux >= 75 ? '#2E7D32'   : ($taux >= 50 ? '#E57C00'   : '#E53935');
    $tauxLabel   = $taux >= 75 ? 'Bon niveau' : ($taux >= 50 ? 'Insuffisant' : 'Critique');
    $presences   = $inscription?->presences->sortByDesc('date') ?? collect();
@endphp

{{-- Bouton retour --}}
<div class="mb-4">
    <a href="{{ route('apprenants.index') }}"
       class="btn btn-sm d-inline-flex align-items-center gap-2"
       style="background:#F4F6F9;color:#64788A;border:1px solid #EDF0F5;border-radius:6px;font-size:.8125rem;padding:6px 14px">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        Retour à la liste
    </a>
</div>

<div class="row g-3 mb-3">

    {{-- Card identité --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius:10px">
            <div class="card-body p-4">
                <h6 class="mb-3" style="font-weight:700;color:#1B3A4B;font-size:.875rem;text-transform:uppercase;letter-spacing:.05em;color:#64788A">
                    Identité
                </h6>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="avatar-initials" style="width:48px;height:48px;font-size:1rem;flex-shrink:0">
                        {{ strtoupper(substr($apprenant->prenom ?? $apprenant->name, 0, 1)) }}{{ strtoupper(substr($apprenant->nom ?? '', 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size:1.0625rem;font-weight:700;color:#1B3A4B">
                            {{ $apprenant->prenom }} {{ $apprenant->nom }}
                        </div>
                        <div style="font-size:.8125rem;color:#96A8B8">{{ $apprenant->email }}</div>
                    </div>
                </div>
                <hr style="border-color:#EDF0F5">
                <div class="row g-2" style="font-size:.8125rem">
                    <div class="col-4" style="color:#96A8B8">Prénom</div>
                    <div class="col-8" style="color:#1B3A4B;font-weight:500">{{ $apprenant->prenom ?? '—' }}</div>
                    <div class="col-4" style="color:#96A8B8">Nom</div>
                    <div class="col-8" style="color:#1B3A4B;font-weight:500">{{ $apprenant->nom ?? '—' }}</div>
                    <div class="col-4" style="color:#96A8B8">Email</div>
                    <div class="col-8" style="color:#1B3A4B;font-weight:500">{{ $apprenant->email }}</div>
                    <div class="col-4" style="color:#96A8B8">Rôle</div>
                    <div class="col-8">
                        <span style="background:#EAF6F8;color:#1E8296;font-size:.7rem;padding:2px 10px;border-radius:20px;font-weight:600">
                            {{ ucfirst($apprenant->role) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card taux --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius:10px">
            <div class="card-body p-4 d-flex flex-column justify-content-center">
                <h6 class="mb-3" style="font-weight:700;font-size:.875rem;text-transform:uppercase;letter-spacing:.05em;color:#64788A">
                    Taux d'assiduité
                </h6>
                <div class="text-center mb-3">
                    <span style="font-size:3.5rem;font-weight:800;color:{{ $tauxColor }};line-height:1">
                        {{ number_format($taux, 1) }}
                    </span>
                    <span style="font-size:1.25rem;font-weight:600;color:{{ $tauxColor }}">%</span>
                    <div style="font-size:.75rem;color:#96A8B8;margin-top:4px">
                        <span style="background:{{ $tauxColor }}1A;color:{{ $tauxColor }};font-size:.7rem;padding:2px 10px;border-radius:20px;font-weight:600">
                            {{ $tauxLabel }}
                        </span>
                    </div>
                </div>
                <div class="progress mb-1" style="height:10px;border-radius:20px;background:#EDF0F5">
                    <div class="progress-bar {{ $tauxBg }}"
                         role="progressbar"
                         style="width:{{ $tauxPct }}%;border-radius:20px"
                         aria-valuenow="{{ $tauxPct }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <div class="d-flex justify-content-between" style="font-size:.7rem;color:#96A8B8;margin-top:4px">
                    <span>0%</span>
                    <span>Seuil requis : 75%</span>
                    <span>100%</span>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    <a href="{{ route('apprenants.edit', $apprenant) }}"
                       class="btn btn-sm"
                       style="background:#1E8296;color:#fff;border-radius:6px;font-size:.8rem;padding:5px 16px">
                        Modifier le profil
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Card formation --}}
@if($formation)
<div class="card border-0 shadow-sm mb-3" style="border-radius:10px">
    <div class="card-body p-4">
        <h6 class="mb-3" style="font-weight:700;font-size:.875rem;text-transform:uppercase;letter-spacing:.05em;color:#64788A">
            Formation
        </h6>
        <div class="row g-2" style="font-size:.875rem">
            <div class="col-md-6">
                <div style="font-size:1rem;font-weight:700;color:#1E8296">{{ $formation->nom }}</div>
            </div>
            <div class="col-md-3">
                <div style="font-size:.75rem;color:#96A8B8;margin-bottom:2px">Début</div>
                <div style="font-weight:500;color:#1B3A4B">{{ \Carbon\Carbon::parse($formation->date_debut)->format('d/m/Y') }}</div>
            </div>
            <div class="col-md-3">
                <div style="font-size:.75rem;color:#96A8B8;margin-bottom:2px">Fin</div>
                <div style="font-weight:500;color:#1B3A4B">{{ \Carbon\Carbon::parse($formation->date_fin)->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Historique présences --}}
<div class="card border-0 shadow-sm" style="border-radius:10px">
    <div class="card-header bg-white border-0 pt-3 pb-2 px-4">
        <h6 style="margin:0;font-size:.875rem;font-weight:700;color:#1B3A4B">
            Historique des présences
            <span class="ms-2" style="font-size:.75rem;font-weight:400;color:#96A8B8">
                ({{ $presences->count() }} séance(s))
            </span>
        </h6>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0" style="font-size:.875rem">
            <thead>
                <tr style="background:#F4F6F9;border-bottom:1px solid #EDF0F5">
                    <th class="px-4 py-3" style="font-weight:600;color:#64788A;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;border:none">Date</th>
                    <th class="px-3 py-3" style="font-weight:600;color:#64788A;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;border:none">Statut</th>
                    <th class="px-3 py-3" style="font-weight:600;color:#64788A;text-transform:uppercase;font-size:.7rem;letter-spacing:.05em;border:none">Observation</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presences as $p)
                @php
                    $statutMap = [
                        'present'         => ['label' => 'Présent',  'bg' => '#E8F5E9', 'color' => '#2E7D32'],
                        'absent'          => ['label' => 'Absent',   'bg' => '#FFEBEE', 'color' => '#E53935'],
                        'retard'          => ['label' => 'Retard',   'bg' => '#FFF3E0', 'color' => '#E57C00'],
                        'absent_justifie' => ['label' => 'Justifié', 'bg' => '#EDE7F6', 'color' => '#7C3AED'],
                    ];
                    $st = $statutMap[$p->statut] ?? ['label' => ucfirst($p->statut), 'bg' => '#EDF0F5', 'color' => '#64788A'];
                @endphp
                <tr style="border-bottom:1px solid #EDF0F5">
                    <td class="px-4 py-3" style="color:#1B3A4B;font-weight:500;border:none">
                        {{ \Carbon\Carbon::parse($p->date)->format('d/m/Y') }}
                    </td>
                    <td class="px-3 py-3" style="border:none">
                        <span style="background:{{ $st['bg'] }};color:{{ $st['color'] }};font-size:.75rem;padding:3px 10px;border-radius:20px;font-weight:500">
                            {{ $st['label'] }}
                        </span>
                    </td>
                    <td class="px-3 py-3" style="color:#64788A;border:none">
                        {{ $p->observation ?? '—' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-4 py-5 text-center" style="color:#96A8B8;border:none">
                        Aucune présence enregistrée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection