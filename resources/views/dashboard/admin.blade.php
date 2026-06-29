@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', "Vue d'ensemble — " . now()->translatedFormat('l d F Y'))

@section('content')

{{-- Stat cards --}}
<div class="row g-3 mb-4">

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(30,130,150,.12)">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1E8296" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div class="stat-value">{{ $stats['apprenants'] }}</div>
            <div class="stat-label">Apprenants inscrits</div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(46,125,50,.12)">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2E7D32" stroke-width="2">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
            </div>
            <div class="stat-value">{{ $stats['formations'] }}</div>
            <div class="stat-label">Formations actives</div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(229,124,0,.12)">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#E57C00" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <div class="stat-value">{{ $stats['seances_mois'] }}</div>
            <div class="stat-label">Seances ce mois</div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(229,57,53,.12)">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#E53935" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
            </div>
            <div class="stat-value">{{ $stats['alertes'] }}</div>
            <div class="stat-label">Alertes non vues</div>
        </div>
    </div>

</div>

<div class="row g-3 align-items-stretch">

    {{-- Seances recentes --}}
    <div class="col-lg-7">
        <div class="card h-100 border-0" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
            <div class="card-header bg-white border-0 pb-0" style="padding:1.25rem 1.5rem .75rem">
                <h6 style="margin:0;font-size:.875rem;font-weight:700;color:#1B3A4B">Seances recentes</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-assidua mb-0">
                    <thead>
                        <tr>
                            <th>Formation</th>
                            <th>Date</th>
                            <th>Présents</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions_recentes as $s)
                        <tr>
                            <td style="font-weight:500">{{ $s->nom }}</td>
                            <td>
                                <span style="font-size:.75rem;color:#96A8B8">
                                    {{ \Carbon\Carbon::parse($s->date)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-taux-success" style="font-size:.7rem;padding:3px 8px;border-radius:20px">
                                    {{ $s->nb_presences }}
                                </span>
                            </td>
                            <td style="text-align:right">
                                <a href="{{ route('presences.pdf', ['formation_id' => $s->formation_id, 'date' => $s->date]) }}"
                                   target="_blank"
                                   title="Exporter la feuille de présence en PDF"
                                   style="display:inline-flex;align-items:center;gap:4px;font-size:.72rem;font-weight:600;color:#1E8296;text-decoration:none;padding:3px 8px;border:1px solid #1E8296;border-radius:6px;white-space:nowrap">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                        <line x1="12" y1="18" x2="12" y2="12"/>
                                        <line x1="9" y1="15" x2="15" y2="15"/>
                                    </svg>
                                    PDF
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:#96A8B8;padding:2rem">
                                Aucune séance enregistrée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Alertes non vues --}}
    <div class="col-lg-5">
        <div class="card h-100 border-0" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
            <div class="card-header bg-white border-0 pb-0" style="padding:1.25rem 1.5rem .75rem;display:flex;align-items:center;justify-content:space-between">
                <h6 style="margin:0;font-size:.875rem;font-weight:700;color:#1B3A4B">Alertes non vues</h6>
                @if($stats['alertes'] > 0)
                    <span class="badge bg-danger" style="font-size:.65rem">{{ $stats['alertes'] }}</span>
                @endif
            </div>
            <div class="card-body p-0">
                @forelse($alertes_recentes as $a)
                @php
                    $taux = $a->assiduite?->taux ?? 0;
                    $nom  = $a->assiduite?->inscription?->user?->name ?? 'Apprenant';
                    $form = $a->assiduite?->inscription?->formation?->nom ?? 'Formation';
                @endphp
                <div style="padding:.875rem 1.5rem;border-bottom:1px solid #EDF0F5;display:flex;align-items:center;gap:12px">
                    <div style="width:8px;height:8px;border-radius:50%;background:#E53935;flex-shrink:0"></div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.8125rem;font-weight:600;color:#1B3A4B;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                            {{ $nom }}
                        </div>
                        <div style="font-size:.75rem;color:#96A8B8">
                            {{ $form }} — taux {{ number_format($taux, 1) }}%
                        </div>
                    </div>
                    <span class="badge badge-taux-danger" style="font-size:.7rem;padding:3px 8px;border-radius:20px">
                        {{ number_format($taux, 0) }}%
                    </span>
                </div>
                @empty
                <div style="padding:2rem;text-align:center;color:#96A8B8;font-size:.875rem">
                    Aucune alerte non vue.
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection
