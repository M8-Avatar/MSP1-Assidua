@extends('layouts.app')

@section('title', 'Mon Tableau de bord')
@section('page-title', 'Mon tableau de bord')
@section('page-subtitle', 'Votre suivi d''assiduite personnel')

@section('content')

@php
    $taux          = $assiduite?->taux ?? 0;
    $tauxClass     = $taux >= 80 ? 'success' : ($taux >= 60 ? 'warning' : 'danger');
    $progressColor = $taux >= 80 ? '#2E7D32' : ($taux >= 60 ? '#E57C00' : '#E53935');

    $nbPresents  = (int) ($presences_counts['present']         ?? 0);
    $nbAbsents   = (int) ($presences_counts['absent']          ?? 0);
    $nbRetards   = (int) ($presences_counts['retard']          ?? 0);
    $nbJustifies = (int) ($presences_counts['absent_justifie'] ?? 0);

    $circumference = round(2 * 3.14159 * 52, 1);
    $dashOffset    = round($circumference * $taux / 100, 1);
@endphp

<div class="row g-3 mb-4">

    {{-- Taux principal --}}
    <div class="col-md-4">
        <div class="stat-card text-center">
            <div style="position:relative;width:120px;height:120px;margin:0 auto 1.25rem">
                <svg viewBox="0 0 120 120" style="transform:rotate(-90deg);width:120px;height:120px">
                    <circle cx="60" cy="60" r="52" fill="none" stroke="#EDF0F5" stroke-width="10"/>
                    <circle cx="60" cy="60" r="52" fill="none" stroke="{{ $progressColor }}" stroke-width="10"
                            stroke-dasharray="{{ $dashOffset }} {{ $circumference }}"
                            stroke-linecap="round"/>
                </svg>
                <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center">
                    <span style="font-size:1.75rem;font-weight:700;color:{{ $progressColor }};line-height:1">{{ number_format($taux, 0) }}</span>
                    <span style="font-size:.6875rem;color:#96A8B8;font-weight:600">%</span>
                </div>
            </div>
            <div class="stat-label">Taux d'assiduité</div>
            <span class="badge badge-taux-{{ $tauxClass }} mt-1" style="font-size:.75rem;padding:4px 10px;border-radius:20px">
                {{ $taux >= 80 ? 'Excellent' : ($taux >= 60 ? 'Insuffisant' : 'Critique') }}
            </span>
        </div>
    </div>

    {{-- Détail présences --}}
    <div class="col-md-8">
        <div class="row g-3 h-100">
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(30,130,150,.12)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1E8296" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div class="stat-value" style="font-size:1.75rem">{{ $nbPresents }}</div>
                    <div class="stat-label">Présences</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(229,57,53,.12)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#E53935" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </div>
                    <div class="stat-value" style="font-size:1.75rem">{{ $nbAbsents }}</div>
                    <div class="stat-label">Absences</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(229,124,0,.12)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#E57C00" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div class="stat-value" style="font-size:1.75rem">{{ $nbRetards }}</div>
                    <div class="stat-label">Retards</div>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(124,58,237,.12)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7C3AED" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </div>
                    <div class="stat-value" style="font-size:1.75rem">{{ $nbJustifies }}</div>
                    <div class="stat-label">Justifiées</div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Barre de progression globale --}}
<div class="card border-0 mb-4" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
    <div class="card-body" style="padding:1.25rem 1.5rem">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span style="font-size:.8125rem;font-weight:600;color:#1B3A4B">
                Progression globale
                @if($inscription?->formation)
                    — {{ $inscription->formation->nom }}
                @endif
            </span>
            <span style="font-size:.875rem;font-weight:700;color:{{ $progressColor }}">{{ number_format($taux, 1) }}%</span>
        </div>
        <div style="height:10px;background:#EDF0F5;border-radius:20px;overflow:hidden">
            <div style="height:100%;width:{{ min($taux, 100) }}%;background:{{ $progressColor }};border-radius:20px;transition:width .6s ease"></div>
        </div>
        <div style="font-size:.75rem;color:#96A8B8;margin-top:6px">
            Seuil requis : 80%
            @if($taux >= 80)
                · Objectif atteint
            @else
                · Il vous manque {{ number_format(80 - $taux, 1) }}%
            @endif
        </div>
    </div>
</div>

{{-- Historique présences --}}
<div class="card border-0" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
    <div class="card-header bg-white border-0" style="padding:1.25rem 1.5rem .75rem">
        <h6 style="margin:0;font-size:.875rem;font-weight:700;color:#1B3A4B">Historique des présences</h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-assidua mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Formation</th>
                    <th>Observation</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presences_recentes as $p)
                @php
                    $statutMap = [
                        'present'         => ['label' => 'Présent',  'class' => 'badge-taux-success'],
                        'absent'          => ['label' => 'Absent',   'class' => 'badge-taux-danger'],
                        'retard'          => ['label' => 'Retard',   'class' => 'badge-taux-warning'],
                        'absent_justifie' => ['label' => 'Justifié', 'class' => ''],
                    ];
                    $st = $statutMap[$p->statut] ?? ['label' => ucfirst($p->statut), 'class' => ''];
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($p->date)->format('d/m/Y') }}</td>
                    <td style="font-weight:500">{{ $p->formation_nom }}</td>
                    <td style="color:#64788A">{{ $p->observation ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $st['class'] }}" style="font-size:.75rem;padding:3px 10px;border-radius:20px">
                            {{ $st['label'] }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;color:#96A8B8;padding:2rem">
                        Aucune présence enregistrée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection