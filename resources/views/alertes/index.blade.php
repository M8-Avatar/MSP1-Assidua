@extends('layouts.app')

@section('title', 'Alertes')
@section('page-title', 'Alertes')
@section('page-subtitle', $nb_non_vues . ' alerte' . ($nb_non_vues > 1 ? 's' : '') . ' non vue' . ($nb_non_vues > 1 ? 's' : ''))

@section('content')

{{-- Flash --}}
@if(session('success'))
<div class="alert alert-dismissible fade show mb-3" role="alert"
     style="border-radius:.8125rem;border:none;background:#E8F5E9;color:#2E7D32;font-size:.875rem;padding:.875rem 1.25rem">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
        <polyline points="20 6 9 17 4 12"/>
    </svg>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer" style="filter:invert(35%) sepia(80%) saturate(400%) hue-rotate(90deg)"></button>
</div>
@endif

{{-- Filtres --}}
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('alertes.index') }}"
       class="btn btn-sm {{ $filtre !== 'non_vues' ? 'btn-primary' : 'btn-outline-secondary' }}"
       style="border-radius:20px;padding:5px 16px;font-size:.8125rem;font-weight:500">
        Toutes
        <span class="badge ms-1"
              style="font-size:.65rem;background:{{ $filtre !== 'non_vues' ? 'rgba(255,255,255,.25)' : '#E4EAF0' }};color:{{ $filtre !== 'non_vues' ? '#fff' : '#64788A' }};border-radius:10px;padding:2px 6px">
            {{ $nb_total }}
        </span>
    </a>
    <a href="{{ route('alertes.index', ['filtre' => 'non_vues']) }}"
       class="btn btn-sm {{ $filtre === 'non_vues' ? 'btn-danger' : 'btn-outline-danger' }}"
       style="border-radius:20px;padding:5px 16px;font-size:.8125rem;font-weight:500">
        Non vues
        @if($nb_non_vues > 0)
        <span class="badge ms-1"
              style="font-size:.65rem;background:{{ $filtre === 'non_vues' ? 'rgba(255,255,255,.25)' : '#FFEBEE' }};color:{{ $filtre === 'non_vues' ? '#fff' : '#E53935' }};border-radius:10px;padding:2px 6px">
            {{ $nb_non_vues }}
        </span>
        @endif
    </a>
</div>

{{-- Tableau --}}
<div class="card border-0" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
    <div class="card-body p-0">
        <table class="table table-assidua mb-0">
            <thead>
                <tr>
                    <th>Apprenant</th>
                    <th>Formation</th>
                    <th>Taux</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($alertes as $alerte)
                @php
                    $taux      = $alerte->assiduite?->taux ?? 0;
                    $nom       = $alerte->assiduite?->inscription?->user?->name ?? '—';
                    $formation = $alerte->assiduite?->inscription?->formation?->nom ?? '—';
                    $initiale1 = strtoupper(substr($nom, 0, 1));
                    $pos       = strrpos($nom, ' ');
                    $initiale2 = $pos !== false ? strtoupper(substr($nom, $pos + 1, 1)) : '';
                    if ($taux >= 75) {
                        $badgeClass = 'badge-taux-success';
                    } elseif ($taux >= 50) {
                        $badgeClass = 'badge-taux-warning';
                    } else {
                        $badgeClass = 'badge-taux-danger';
                    }
                @endphp
                <tr>
                    {{-- Apprenant --}}
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-initials" style="width:32px;height:32px;font-size:.75rem">
                                {{ $initiale1 }}{{ $initiale2 }}
                            </div>
                            <span style="font-weight:500;color:#1B3A4B">{{ $nom }}</span>
                        </div>
                    </td>

                    {{-- Formation --}}
                    <td style="color:#64788A">{{ $formation }}</td>

                    {{-- Taux --}}
                    <td>
                        <span class="badge {{ $badgeClass }}" style="font-size:.75rem;padding:4px 10px;border-radius:20px;font-weight:600">
                            {{ number_format($taux, 1) }}%
                        </span>
                    </td>

                    {{-- Date --}}
                    <td style="color:#96A8B8;font-size:.75rem">
                        {{ $alerte->date_alerte->format('d/m/Y') }}
                    </td>

                    {{-- Statut --}}
                    <td>
                        @if($alerte->vue_admin)
                            <span style="font-size:.75rem;color:#2E7D32;display:flex;align-items:center;gap:4px">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                Vue
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:.75rem;font-weight:600;color:#E53935">
                                <span style="width:7px;height:7px;border-radius:50%;background:#E53935;display:inline-block"></span>
                                Non vue
                            </span>
                        @endif
                    </td>

                    {{-- Action --}}
                    <td class="text-end">
                        @unless($alerte->vue_admin)
                        <form method="POST" action="{{ route('alertes.mark-read', $alerte) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="filtre" value="{{ $filtre }}">
                            <button type="submit"
                                    class="btn btn-sm btn-outline-secondary"
                                    style="border-radius:20px;font-size:.75rem;padding:4px 12px;border-color:#E4EAF0;color:#64788A"
                                    onmouseover="this.style.borderColor='#1E8296';this.style.color='#1E8296'"
                                    onmouseout="this.style.borderColor='#E4EAF0';this.style.color='#64788A'">
                                Marquer comme vue
                            </button>
                        </form>
                        @endunless
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:#96A8B8;padding:3rem;font-size:.875rem">
                        @if($filtre === 'non_vues')
                            Aucune alerte non vue. Toutes les alertes ont été traitées.
                        @else
                            Aucune alerte enregistrée.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection