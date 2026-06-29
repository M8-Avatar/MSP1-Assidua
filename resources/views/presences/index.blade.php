@extends('layouts.app')

@section('title', 'Saisie des présences')
@section('page-title', 'Présences')
@section('page-subtitle', 'Pointage des séances')

@section('content')

{{-- Flash success --}}
@if(session('success'))
<div class="alert border-0 mb-3 d-flex align-items-center gap-2"
     style="border-radius:.8125rem;background:#E8F5E9;color:#2E7D32">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
    </svg>
    {{ session('success') }}
</div>
@endif

{{-- Validation errors --}}
@if($errors->any())
<div class="alert border-0 mb-3" style="border-radius:.8125rem;background:#FFEBEE;color:#C62828">
    <ul class="mb-0 ps-3">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- ── Filtre + bouton Enregistrer ── --}}
<div class="card border-0 mb-3" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
    <div class="card-body" style="padding:1.25rem 1.5rem">
        <form id="filter-form" method="GET" action="{{ route('presences.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-lg-5 col-md-6">
                    <label for="formation_id"
                           style="font-size:.6875rem;font-weight:600;color:#96A8B8;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:.375rem">
                        Formation
                    </label>
                    <select id="formation_id" name="formation_id" class="form-select form-select-sm">
                        <option value="">— Choisir une formation —</option>
                        @foreach($formations as $f)
                        <option value="{{ $f->id }}" {{ (string)$formation_id === (string)$f->id ? 'selected' : '' }}>
                            {{ $f->nom }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 col-md-4">
                    <label for="date"
                           style="font-size:.6875rem;font-weight:600;color:#96A8B8;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:.375rem">
                        Date de séance
                    </label>
                    <input type="date" id="date" name="date"
                           class="form-control form-control-sm"
                           value="{{ $date }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm"
                            style="background:#EDF0F5;color:#1B3A4B;border:none;font-weight:600">
                        Charger
                    </button>
                </div>
                @if($formation && $inscriptions->count() > 0)
                <div class="col-auto ms-auto">
                    <button type="submit" form="presence-form" class="btn btn-sm"
                            style="background:#1E8296;color:#fff;border:none;font-weight:600;padding:.4rem 1.25rem">
                        Enregistrer la séance
                    </button>
                </div>
                @endif
            </div>
        </form>
    </div>
</div>

@if($formation && $inscriptions->count() > 0)

{{-- ── Résumé temps réel ── --}}
<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <div class="card border-0 text-center" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
            <div class="card-body py-3 px-2">
                <div id="count-present"
                     style="font-size:1.5rem;font-weight:700;color:#2E7D32;line-height:1">0</div>
                <div style="font-size:.6875rem;color:#96A8B8;margin-top:4px;text-transform:uppercase;letter-spacing:.05em">Présents</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 text-center" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
            <div class="card-body py-3 px-2">
                <div id="count-absent"
                     style="font-size:1.5rem;font-weight:700;color:#E53935;line-height:1">0</div>
                <div style="font-size:.6875rem;color:#96A8B8;margin-top:4px;text-transform:uppercase;letter-spacing:.05em">Absents</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 text-center" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
            <div class="card-body py-3 px-2">
                <div id="count-retard"
                     style="font-size:1.5rem;font-weight:700;color:#E57C00;line-height:1">0</div>
                <div style="font-size:.6875rem;color:#96A8B8;margin-top:4px;text-transform:uppercase;letter-spacing:.05em">Retards</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 text-center" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
            <div class="card-body py-3 px-2">
                <div id="taux-estime"
                     style="font-size:1.5rem;font-weight:700;color:#1E8296;line-height:1">—</div>
                <div style="font-size:.6875rem;color:#96A8B8;margin-top:4px;text-transform:uppercase;letter-spacing:.05em">Taux estimé</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Tableau de pointage ── --}}
<div class="card border-0" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center"
         style="padding:1.125rem 1.5rem .75rem;border-bottom:1px solid #EDF0F5">
        <div>
            <h6 style="margin:0;font-size:.875rem;font-weight:700;color:#1B3A4B">
                {{ $formation->nom }}
            </h6>
            <div style="font-size:.75rem;color:#96A8B8;margin-top:2px">
                {{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span style="background:#EDF0F5;color:#64788A;font-size:.75rem;font-weight:600;padding:3px 10px;border-radius:20px">
                {{ $inscriptions->count() }} apprenant{{ $inscriptions->count() > 1 ? 's' : '' }}
            </span>
            @if($presences->count() > 0)
            <a href="{{ route('presences.pdf', ['formation_id' => $formation_id, 'date' => $date]) }}"
               target="_blank"
               style="display:inline-flex;align-items:center;gap:4px;font-size:.75rem;font-weight:600;color:#1E8296;text-decoration:none;padding:3px 10px;border:1px solid #1E8296;border-radius:6px;white-space:nowrap">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="12" y1="18" x2="12" y2="12"/>
                    <line x1="9" y1="15" x2="15" y2="15"/>
                </svg>
                Exporter PDF
            </a>
            @endif
        </div>
    </div>

    <form id="presence-form" method="POST" action="{{ route('presences.store') }}">
        @csrf
        <input type="hidden" name="formation_id" value="{{ $formation_id }}">
        <input type="hidden" name="date" value="{{ $date }}">

        <div class="table-responsive">
            <table class="table table-assidua mb-0">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th style="min-width:320px">Statut</th>
                        <th style="min-width:200px">Observation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inscriptions as $inscription)
                    @php
                        $i = $loop->index;
                        $nameParts   = array_pad(explode(' ', $inscription->user->name ?? '', 2), 2, '');
                        $existing    = $presences->get($inscription->id);
                        $curStatut   = old("presences.$i.statut", $existing?->statut ?? '');
                        $curObs      = old("presences.$i.observation", $existing?->observation ?? '');
                        $showObs     = in_array($curStatut, ['absent', 'retard', 'absent_justifie']);
                    @endphp
                    <tr>
                        <td style="color:#96A8B8;font-size:.8125rem;vertical-align:middle">
                            {{ $loop->iteration }}
                        </td>
                        <td style="font-weight:600;color:#1B3A4B;vertical-align:middle">
                            {{ $nameParts[0] }}
                        </td>
                        <td style="color:#1B3A4B;vertical-align:middle">
                            {{ $nameParts[1] }}
                        </td>
                        <td style="vertical-align:middle">
                            <input type="hidden"
                                   name="presences[{{ $i }}][inscription_id]"
                                   value="{{ $inscription->id }}">
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check presence-radio"
                                       name="presences[{{ $i }}][statut]"
                                       id="s{{ $i }}_present" value="present"
                                       data-index="{{ $i }}"
                                       {{ $curStatut === 'present' ? 'checked' : '' }}
                                       autocomplete="off">
                                <label class="btn btn-outline-success" for="s{{ $i }}_present">
                                    Présent
                                </label>

                                <input type="radio" class="btn-check presence-radio"
                                       name="presences[{{ $i }}][statut]"
                                       id="s{{ $i }}_absent" value="absent"
                                       data-index="{{ $i }}"
                                       {{ $curStatut === 'absent' ? 'checked' : '' }}
                                       autocomplete="off">
                                <label class="btn btn-outline-danger" for="s{{ $i }}_absent">
                                    Absent
                                </label>

                                <input type="radio" class="btn-check presence-radio"
                                       name="presences[{{ $i }}][statut]"
                                       id="s{{ $i }}_retard" value="retard"
                                       data-index="{{ $i }}"
                                       {{ $curStatut === 'retard' ? 'checked' : '' }}
                                       autocomplete="off">
                                <label class="btn btn-outline-warning" for="s{{ $i }}_retard">
                                    Retard
                                </label>

                                <input type="radio" class="btn-check presence-radio"
                                       name="presences[{{ $i }}][statut]"
                                       id="s{{ $i }}_justifie" value="absent_justifie"
                                       data-index="{{ $i }}"
                                       {{ $curStatut === 'absent_justifie' ? 'checked' : '' }}
                                       autocomplete="off">
                                <label class="btn btn-outline-secondary" for="s{{ $i }}_justifie">
                                    Justifié
                                </label>
                            </div>
                        </td>
                        <td style="vertical-align:middle">
                            <div id="obs-{{ $i }}" style="{{ $showObs ? '' : 'display:none' }}">
                                <input type="text"
                                       name="presences[{{ $i }}][observation]"
                                       class="form-control form-control-sm"
                                       placeholder="Observation…"
                                       value="{{ $curObs }}"
                                       maxlength="255">
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-0 d-flex justify-content-end"
             style="padding:.75rem 1.5rem 1.25rem;border-top:1px solid #EDF0F5">
            <button type="submit" class="btn"
                    style="background:#1E8296;color:#fff;border:none;font-weight:600;padding:.5rem 2rem">
                Enregistrer la séance
            </button>
        </div>
    </form>
</div>

@elseif($formation_id && $inscriptions->count() === 0)
<div class="card border-0" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
    <div class="card-body text-center py-5" style="color:#96A8B8">
        Aucun apprenant inscrit à cette formation.
    </div>
</div>
@else
<div class="card border-0" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
    <div class="card-body text-center py-5">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#EDF0F5" stroke-width="1.5"
             style="display:block;margin:0 auto 12px">
            <path d="M9 11l3 3L22 4"/>
            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
        </svg>
        <div style="color:#96A8B8;font-size:.875rem">
            Sélectionnez une formation et une date pour saisir les présences.
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
(function () {
    var formationSel = document.getElementById('formation_id');
    var dateInput    = document.getElementById('date');

    function autoSubmit() {
        if (formationSel && formationSel.value && dateInput && dateInput.value) {
            document.getElementById('filter-form').submit();
        }
    }

    if (formationSel) formationSel.addEventListener('change', autoSubmit);
    if (dateInput)    dateInput.addEventListener('change', autoSubmit);

    function toggleObs(index, statut) {
        var el = document.getElementById('obs-' + index);
        if (!el) return;
        var show = ['absent', 'retard', 'absent_justifie'].indexOf(statut) !== -1;
        el.style.display = show ? '' : 'none';
        if (!show) {
            var inp = el.querySelector('input[type="text"]');
            if (inp) inp.value = '';
        }
    }

    function updateSummary() {
        var counts = { present: 0, absent: 0, retard: 0, absent_justifie: 0 };
        document.querySelectorAll('.presence-radio:checked').forEach(function (r) {
            if (Object.prototype.hasOwnProperty.call(counts, r.value)) counts[r.value]++;
        });

        var elP = document.getElementById('count-present');
        var elA = document.getElementById('count-absent');
        var elR = document.getElementById('count-retard');
        var elT = document.getElementById('taux-estime');

        if (elP) elP.textContent = counts.present;
        if (elA) elA.textContent = counts.absent;
        if (elR) elR.textContent = counts.retard;

        var eligible = counts.present + counts.absent + counts.retard;
        if (elT) {
            elT.textContent = eligible > 0
                ? Math.round((counts.present / eligible) * 100) + '%'
                : '—';
        }
    }

    document.querySelectorAll('.presence-radio').forEach(function (radio) {
        radio.addEventListener('change', function () {
            toggleObs(this.dataset.index, this.value);
            updateSummary();
        });
    });

    updateSummary();
}());
</script>
@endpush
