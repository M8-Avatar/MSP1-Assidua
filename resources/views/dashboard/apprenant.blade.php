@extends('layouts.app')

@section('title', 'Mon tableau de bord')
@section('page-title', 'Mon tableau de bord')
@section('page-subtitle', "Suivi d'assiduité — " . now()->translatedFormat('l d F Y'))

@section('content')

@php
    $tauxPct   = min(100, max(0, $taux));
    $tauxBg    = $taux >= 75 ? 'bg-success' : ($taux >= 50 ? 'bg-warning' : 'bg-danger');
    $tauxColor = $taux >= 75 ? '#2E7D32'    : ($taux >= 50 ? '#E57C00'    : '#E53935');
    $tauxLabel = $taux >= 75 ? 'Bon niveau'  : ($taux >= 50 ? 'Insuffisant' : 'Critique');
@endphp

{{-- Bandeau avertissement seuil OPCO --}}
@if($taux < 75)
<div class="alert alert-warning d-flex align-items-center gap-2 mb-4" role="alert"
     style="border-left:4px solid #E57C00;border-radius:.8125rem;font-size:.875rem;border-color:#E57C00">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#E57C00" stroke-width="2" style="flex-shrink:0">
        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
    </svg>
    <span>
        Votre taux d'assiduité est de <strong>{{ number_format($taux, 1) }}%</strong>,
        en dessous du seuil OPCO requis de <strong>75%</strong>.
        @if($taux > 0)
            Il vous manque <strong>{{ number_format(75 - $taux, 1) }}%</strong>.
        @endif
    </span>
</div>
@endif

<div class="row g-3 mb-4">

    {{-- Card taux --}}
    <div class="col-md-5">
        <div class="card border-0 h-100" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
            <div class="card-body p-4 d-flex flex-column justify-content-center">
                <div class="text-center mb-3">
                    <div style="font-size:3.5rem;font-weight:800;color:{{ $tauxColor }};line-height:1">
                        {{ number_format($taux, 1) }}<span style="font-size:1.5rem">%</span>
                    </div>
                    <div style="font-size:.8125rem;color:#96A8B8;margin-top:6px">Taux d'assiduité</div>
                </div>
                <div class="progress mb-2" style="height:10px;border-radius:20px;background:#EDF0F5">
                    <div class="progress-bar {{ $tauxBg }}"
                         role="progressbar"
                         style="width:{{ $tauxPct }}%;border-radius:20px"
                         aria-valuenow="{{ $tauxPct }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-1" style="font-size:.75rem;color:#96A8B8">
                    <span>0%</span>
                    <span class="badge"
                          style="background:{{ $tauxColor }}1A;color:{{ $tauxColor }};font-size:.7rem;border-radius:20px;padding:3px 10px;font-weight:600">
                        {{ $tauxLabel }}
                    </span>
                    <span>100%</span>
                </div>
                <div style="font-size:.75rem;color:#96A8B8;text-align:center;margin-top:10px">
                    Seuil requis : 75%
                    @if($taux >= 75)
                        · <span style="color:#2E7D32;font-weight:600">Objectif atteint ✓</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Card formation --}}
    <div class="col-md-7">
        <div class="card border-0 h-100" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
            <div class="card-body p-4">
                <h6 style="margin:0 0 1rem;font-weight:700;color:#1B3A4B;font-size:.875rem">Ma formation</h6>
                @if($inscription?->formation)
                @php $f = $inscription->formation; @endphp
                <div style="font-size:1.0625rem;font-weight:700;color:#1E8296;margin-bottom:.875rem">
                    {{ $f->nom }}
                </div>
                <div class="d-flex flex-column gap-2 mb-3">
                    <div class="d-flex align-items-center gap-2" style="font-size:.8125rem;color:#64788A">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1E8296" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        Début : <strong>{{ \Carbon\Carbon::parse($f->date_debut)->translatedFormat('d F Y') }}</strong>
                    </div>
                    <div class="d-flex align-items-center gap-2" style="font-size:.8125rem;color:#64788A">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#96A8B8" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        Fin : <strong>{{ \Carbon\Carbon::parse($f->date_fin)->translatedFormat('d F Y') }}</strong>
                    </div>
                </div>
                <hr style="border-color:#EDF0F5;margin:.75rem 0">
                <div class="row g-2 text-center">
                    @foreach([
                        ['val' => $presences_counts['present'],         'label' => 'Présences',  'color' => '#2E7D32'],
                        ['val' => $presences_counts['absent'],          'label' => 'Absences',   'color' => '#E53935'],
                        ['val' => $presences_counts['retard'],          'label' => 'Retards',    'color' => '#E57C00'],
                        ['val' => $presences_counts['absent_justifie'], 'label' => 'Justifiées', 'color' => '#7C3AED'],
                    ] as $stat)
                    <div class="col-6 col-sm-3">
                        <div style="font-size:1.375rem;font-weight:700;color:{{ $stat['color'] }}">{{ $stat['val'] }}</div>
                        <div style="font-size:.6875rem;color:#96A8B8">{{ $stat['label'] }}</div>
                    </div>
                    @endforeach
                </div>
                @else
                <div style="color:#96A8B8;font-size:.875rem">Aucune inscription enregistrée.</div>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- Historique des présences --}}
<div class="card border-0" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
    <div class="card-header bg-white border-0 pb-0" style="padding:1.25rem 1.5rem .75rem">
        <h6 style="margin:0;font-size:.875rem;font-weight:700;color:#1B3A4B">Historique des présences</h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-assidua mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Observation</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inscription?->presences ?? collect() as $p)
                @php
                    $statutMap = [
                        'present'         => ['label' => 'Présent',         'class' => 'badge-taux-success'],
                        'absent'          => ['label' => 'Absent',          'class' => 'badge-taux-danger'],
                        'retard'          => ['label' => 'Retard',          'class' => 'badge-taux-warning'],
                        'absent_justifie' => ['label' => 'Absent justifié', 'class' => '',
                                              'style' => 'background:#EDE7F6;color:#7C3AED'],
                    ];
                    $st = $statutMap[$p->statut] ?? ['label' => ucfirst($p->statut), 'class' => '', 'style' => 'background:#EDF0F5;color:#64788A'];
                @endphp
                <tr>
                    <td style="font-weight:500;color:#1B3A4B">
                        {{ \Carbon\Carbon::parse($p->date)->format('d/m/Y') }}
                    </td>
                    <td>
                        <span class="badge {{ $st['class'] ?? '' }}"
                              style="font-size:.75rem;padding:4px 10px;border-radius:20px;font-weight:500;{{ $st['style'] ?? '' }}">
                            {{ $st['label'] }}
                        </span>
                    </td>
                    <td style="color:#64788A">{{ $p->observation ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align:center;color:#96A8B8;padding:3rem;font-size:.875rem">
                        Aucune présence enregistrée pour le moment.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection