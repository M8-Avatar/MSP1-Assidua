@extends('layouts.app')

@section('title', 'Saisie de presences')
@section('page-title', 'Saisie de presences')
@section('page-subtitle', 'Enregistrez les presences de la seance en cours')

@section('content')

{{-- Flash / Erreurs --}}
@if(session('success'))
<div class="alert border-0 mb-3 d-flex align-items-center gap-2"
     style="border-radius:.8125rem;background:#E8F5E9;color:#2E7D32;padding:.75rem 1.25rem">
    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <polyline points="20 6 9 17 4 12"/>
    </svg>
    {{ session('success') }}
</div>
@endif
@if($errors->any())
<div class="alert border-0 mb-3" style="border-radius:.8125rem;background:#FFEBEE;color:#C62828;padding:.75rem 1.25rem">
    <ul class="mb-0 ps-3" style="font-size:.875rem">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</div>
@endif

{{-- Bouton Enregistrer en haut a droite (seulement si tableau charge) --}}
@if($formation && $inscriptions->count() > 0)
<div class="d-flex justify-content-end mb-3">
    <button type="submit" form="presence-form"
            style="background:#1E8296;color:#fff;border:none;border-radius:.5rem;font-weight:600;font-size:.875rem;padding:.5rem 1.5rem;cursor:pointer;display:inline-flex;align-items:center;gap:6px">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        Enregistrer la seance
    </button>
</div>
@endif

{{-- Card configuration seance --}}
<div class="card border-0 mb-3" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
    <div class="card-body" style="padding:1.25rem 1.5rem">
        <form id="filter-form" method="GET" action="{{ route('presences.index') }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="formation_id"
                           style="font-size:.6875rem;font-weight:700;color:#96A8B8;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:.375rem">
                        Formation
                    </label>
                    <select id="formation_id" name="formation_id" class="form-select">
                        <option value="">? Choisir une formation ?</option>
                        @foreach($formations as $f)
                        <option value="{{ $f->id }}" {{ (string)$formation_id === (string)$f->id ? 'selected' : '' }}>
                            {{ $f->nom }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="date"
                           style="font-size:.6875rem;font-weight:700;color:#96A8B8;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:.375rem">
                        Date de seance
                    </label>
                    <input type="date" id="date" name="date"
                           class="form-control"
                           value="{{ $date }}">
                </div>
            </div>
        </form>
    </div>
</div>

@if($formation && $inscriptions->count() > 0)

{{-- Card tableau apprenants --}}
<div class="card border-0 mb-3" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center"
         style="padding:1.125rem 1.5rem .75rem;border-bottom:1px solid #EDF0F5;border-radius:.8125rem .8125rem 0 0">
        <h6 style="margin:0;font-size:.875rem;font-weight:700;color:#1B3A4B">Apprenants inscrits</h6>
        <div class="d-flex align-items-center gap-2">
            <span style="background:#EDF0F5;color:#64788A;font-size:.75rem;font-weight:600;padding:3px 10px;border-radius:20px">
                {{ $inscriptions->count() }} apprenant{{ $inscriptions->count() > 1 ? 's' : '' }}
            </span>
            @if($presences->count() > 0)
            <a href="{{ route('presences.pdf', ['formation_id' => $formation_id, 'date' => $date]) }}"
               target="_blank"
               style="display:inline-flex;align-items:center;gap:4px;font-size:.75rem;font-weight:600;color:#1E8296;text-decoration:none;padding:3px 10px;border:1px solid #1E8296;border-radius:6px">
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
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th style="min-width:310px">Statut de presence</th>
                        <th style="min-width:180px">Observation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inscriptions as $inscription)
                    @php
                        $i         = $loop->index;
                        $parts     = array_pad(explode(' ', $inscription->user->name ?? '', 2), 2, '');
                        $existing  = $presences->get($inscription->id);
                        $curStatut = old("presences.$i.statut", $existing?->statut ?? '');
                        $curObs    = old("presences.$i.observation", $existing?->observation ?? '');
                        $showObs   = in_array($curStatut, ['absent', 'retard', 'absent_justifie']);
                    @endphp
                    <tr>
                        <td style="font-weight:600;color:#1B3A4B">{{ $parts[0] }}</td>
                        <td style="color:#1B3A4B">{{ $parts[1] }}</td>
                        <td>
                            <input type="hidden"
                                   name="presences[{{ $i }}][inscription_id]"
                                   value="{{ $inscription->id }}">
                            <input type="hidden"
                                   name="presences[{{ $i }}][statut]"
                                   id="statut-{{ $i }}"
                                   value="{{ $curStatut }}">
                            <div class="d-flex gap-1 flex-wrap">
                                <button type="button" class="btn-status {{ $curStatut === 'present' ? 'active-present' : '' }}"
                                        data-index="{{ $i }}" data-value="present"
                                        onclick="setStatut({{ $i }}, 'present', this)">Present</button>
                                <button type="button" class="btn-status {{ $curStatut === 'absent' ? 'active-absent' : '' }}"
                                        data-index="{{ $i }}" data-value="absent"
                                        onclick="setStatut({{ $i }}, 'absent', this)">Absent</button>
                                <button type="button" class="btn-status {{ $curStatut === 'retard' ? 'active-late' : '' }}"
                                        data-index="{{ $i }}" data-value="retard"
                                        onclick="setStatut({{ $i }}, 'retard', this)">Retard</button>
                                <button type="button" class="btn-status {{ $curStatut === 'absent_justifie' ? 'active-excused' : '' }}"
                                        data-index="{{ $i }}" data-value="absent_justifie"
                                        onclick="setStatut({{ $i }}, 'absent_justifie', this)">Abs. justifie</button>
                            </div>
                        </td>
                        <td>
                            <div id="obs-wrap-{{ $i }}">
                                @if($curStatut === 'present' && !$showObs)
                                <span style="color:#96A8B8">?</span>
                                @else
                                <div id="obs-{{ $i }}" style="{{ $showObs ? '' : 'display:none' }}">
                                    <input type="text"
                                           name="presences[{{ $i }}][observation]"
                                           class="form-control form-control-sm"
                                           placeholder="Observation?"
                                           value="{{ $curObs }}"
                                           maxlength="255">
                                </div>
                                <span id="obs-dash-{{ $i }}" style="{{ $showObs ? 'display:none' : '' }};color:#96A8B8">?</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
</div>

{{-- Card resume temps reel --}}
<div class="card border-0" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
    <div class="card-body" style="padding:1rem 1.5rem;display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap">
        <span style="font-size:.8125rem;font-weight:700;color:#1B3A4B;white-space:nowrap">Resume en temps reel</span>
        <div class="d-flex align-items-center gap-3">
            <span style="display:inline-flex;align-items:center;gap:6px;font-size:.8125rem;color:#64788A">
                <span style="width:8px;height:8px;border-radius:50%;background:#2E7D32;flex-shrink:0;display:inline-block"></span>
                Presents <strong id="count-present" style="color:#1B3A4B;margin-left:2px">0</strong>
            </span>
            <span style="display:inline-flex;align-items:center;gap:6px;font-size:.8125rem;color:#64788A">
                <span style="width:8px;height:8px;border-radius:50%;background:#E53935;flex-shrink:0;display:inline-block"></span>
                Absents <strong id="count-absent" style="color:#1B3A4B;margin-left:2px">0</strong>
            </span>
            <span style="display:inline-flex;align-items:center;gap:6px;font-size:.8125rem;color:#64788A">
                <span style="width:8px;height:8px;border-radius:50%;background:#E57C00;flex-shrink:0;display:inline-block"></span>
                Retards <strong id="count-retard" style="color:#1B3A4B;margin-left:2px">0</strong>
            </span>
        </div>
        <div style="border-left:1px solid #EDF0F5;height:30px;align-self:center"></div>
        <div style="font-size:.8125rem;color:#64788A">
            Taux estime :
            <span id="taux-estime" style="font-size:1.25rem;font-weight:700;color:#1E8296;margin-left:4px">?</span>
        </div>
    </div>
</div>

@elseif($formation_id && $inscriptions->count() === 0)
<div class="card border-0" style="border-radius:.8125rem;box-shadow:0 1px 3px rgba(0,0,0,.05)">
    <div class="card-body text-center py-5" style="color:#96A8B8;font-size:.875rem">
        Aucun apprenant inscrit a cette formation.
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
            Selectionnez une formation et une date pour saisir les presences.
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
(function () {
    var activeClasses = {
        present:         'active-present',
        absent:          'active-absent',
        retard:          'active-late',
        absent_justifie: 'active-excused'
    };

    window.setStatut = function (index, value, btn) {
        // Clear all active classes on sibling buttons
        btn.closest('.d-flex').querySelectorAll('.btn-status').forEach(function (b) {
            b.classList.remove('active-present', 'active-absent', 'active-late', 'active-excused');
        });
        if (activeClasses[value]) btn.classList.add(activeClasses[value]);

        // Update hidden input
        var hidden = document.getElementById('statut-' + index);
        if (hidden) hidden.value = value;

        // Toggle observation
        var obsEl   = document.getElementById('obs-' + index);
        var dashEl  = document.getElementById('obs-dash-' + index);
        var showObs = ['absent', 'retard', 'absent_justifie'].indexOf(value) !== -1;
        if (obsEl)  { obsEl.style.display  = showObs ? '' : 'none'; }
        if (dashEl) { dashEl.style.display = showObs ? 'none' : ''; }
        if (!showObs && obsEl) {
            var inp = obsEl.querySelector('input[type="text"]');
            if (inp) inp.value = '';
        }

        updateSummary();
    };

    function updateSummary() {
        var counts = { present: 0, absent: 0, retard: 0 };
        document.querySelectorAll('input[id^="statut-"]').forEach(function (h) {
            if (h.value && Object.prototype.hasOwnProperty.call(counts, h.value)) {
                counts[h.value]++;
            }
        });

        var elP = document.getElementById('count-present');
        var elA = document.getElementById('count-absent');
        var elR = document.getElementById('count-retard');
        var elT = document.getElementById('taux-estime');

        if (elP) elP.textContent = counts.present;
        if (elA) elA.textContent = counts.absent;
        if (elR) elR.textContent = counts.retard;

        if (elT) {
            var eligible = counts.present + counts.absent + counts.retard;
            var taux = eligible > 0 ? Math.round((counts.present / eligible) * 100) : null;
            elT.textContent = taux !== null ? taux + '%' : '?';
            elT.style.color = taux === null ? '#1E8296'
                            : taux >= 75    ? '#2E7D32'
                            : taux >= 50    ? '#E57C00'
                            : '#E53935';
        }
    }

    // Auto-submit on formation/date change
    var formationSel = document.getElementById('formation_id');
    var dateInput    = document.getElementById('date');
    function autoSubmit() {
        if (formationSel && formationSel.value && dateInput && dateInput.value) {
            document.getElementById('filter-form').submit();
        }
    }
    if (formationSel) formationSel.addEventListener('change', autoSubmit);
    if (dateInput)    dateInput.addEventListener('change', autoSubmit);

    updateSummary();
}());
</script>
@endpush
