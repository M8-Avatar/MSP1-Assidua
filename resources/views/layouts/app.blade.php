<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Tableau de bord') ? Assidua</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>

@php
    $_u           = auth()->user();
    $_displayName = ($_u->nom && $_u->prenom)
                        ? $_u->prenom . ' ' . $_u->nom
                        : $_u->name;
    $_initial1    = $_u->prenom ? strtoupper(substr($_u->prenom, 0, 1))
                                : strtoupper(substr($_u->name, 0, 1));
    $_initial2    = $_u->nom    ? strtoupper(substr($_u->nom, 0, 1))
                                : strtoupper(substr($_u->name, strrpos($_u->name, ' ') + 1, 1));
    $_roleLabel   = ucfirst($_u->role ?? '');
@endphp

<div class="d-flex">

    {{-- Sidebar --}}
    <aside class="sidebar">

        {{-- Logo --}}
        <div style="padding:24px 20px 8px;border-bottom:1px solid #EDF0F5;margin-bottom:8px">
            <span style="font-size:1.1875rem;font-weight:700;color:#1E8296;letter-spacing:-.02em">Assidua</span>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav flex-grow-1 px-3 pt-2">
            <ul class="nav flex-column gap-1">

                {{-- Dashboard : lien adaptatif selon le role --}}
                <li class="nav-item">
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('dashboard.admin') }}"
                       class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                    @else
                    <a href="{{ route('dashboard.apprenant') }}"
                       class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                    @endif
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                            <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
                        </svg>
                        Dashboard
                    </a>
                </li>

                {{-- Liens reserves a l'admin --}}
                @if(auth()->user()->role === 'admin')

                <li class="nav-item">
                    <a href="{{ route('apprenants.index') }}"
                       class="nav-link {{ request()->routeIs('apprenants.*') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        Apprenants
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('formations.index') }}"
                       class="nav-link {{ request()->routeIs('formations.*') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                        Formations
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('presences.index') }}"
                       class="nav-link {{ request()->routeIs('presences.*') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 11l3 3L22 4"/>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                        </svg>
                        Presences
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('alertes.index') }}"
                       class="nav-link {{ request()->routeIs('alertes.*') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        Alertes
                        @php
                            try {
                                $alertCount = \App\Models\Alerte::where('vue_admin', false)->count();
                            } catch (\Exception $e) {
                                $alertCount = 0;
                            }
                        @endphp
                        @if($alertCount > 0)
                            <span class="badge bg-danger ms-auto" style="font-size:.65rem;padding:2px 6px;border-radius:10px">{{ $alertCount }}</span>
                        @endif
                    </a>
                </li>

                @endif

            </ul>
        </nav>

        {{-- Footer utilisateur --}}
        <div style="padding:16px 20px;border-top:1px solid #EDF0F5;display:flex;align-items:center;gap:12px">
            <div class="avatar-initials">{{ $_initial1 }}{{ $_initial2 }}</div>
            <div style="flex:1;min-width:0">
                <div style="font-size:.8125rem;font-weight:600;color:#1B3A4B;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                    {{ $_displayName }}
                </div>
                <div style="font-size:.6875rem;color:#96A8B8;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                    {{ $_roleLabel }}
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Deconnexion"
                        style="background:none;border:none;padding:4px;cursor:pointer;color:#96A8B8;line-height:0"
                        onmouseover="this.style.color='#E53935'" onmouseout="this.style.color='#96A8B8'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </button>
            </form>
        </div>

    </aside>

    {{-- Main --}}
    <div class="main-content flex-grow-1 d-flex flex-column">

        {{-- Topbar --}}
        <div class="topbar">
            <div>
                <h6 style="margin:0;font-weight:700;color:#1B3A4B;font-size:.9375rem">@yield('page-title', 'Tableau de bord')</h6>
                @hasSection('page-subtitle')
                    <div style="font-size:.75rem;color:#96A8B8;margin-top:1px">@yield('page-subtitle')</div>
                @endif
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-initials" style="background:#EDF0F5;color:#64788A;font-size:.75rem">
                    {{ $_initial1 }}{{ $_initial2 }}
                </div>
            </div>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-4 mt-3 mb-0" role="alert"
                 style="border-left:4px solid #1E8296;background:#EAF6F8;color:#1B3A4B;border-radius:6px;font-size:.875rem">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3 mb-0" role="alert"
                 style="border-left:4px solid #E53935;border-radius:6px;font-size:.875rem">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        @endif

        {{-- Content --}}
        <div class="flex-grow-1 p-4">
            @yield('content')
        </div>

    </div>

</div>

</body>
</html>