# Spec : Authentification Breeze + Design Assidua

**Date :** 2026-06-29  
**Projet :** Assidua — Gestion assiduité pour organismes de formation  
**Stack :** Laravel 13.8, Blade pur, Bootstrap 5, PostgreSQL 17, Vite

---

## Périmètre

Installation et configuration de Laravel Breeze (Blade stack) avec remplacement de Tailwind CSS v4 par Bootstrap 5. Application de la charte graphique Assidua sur toutes les vues d'authentification et les layouts principaux. Ajout de la gestion des rôles (`admin` / `apprenant`) avec redirections post-login conditionnelles.

---

## Décisions architecturales

| Point | Décision | Raison |
|---|---|---|
| Breeze stack | Blade pur | Dossier CDA spécifie Blade + Bootstrap 5, jury attend du Blade lisible |
| CSS Framework | Bootstrap 5 (remplace Tailwind v4) | Bootstrap 5 a son propre JS, pas de redondance |
| Font | Plus Jakarta Sans (Google Fonts CDN) | Charte graphique Assidua |
| Rôles | `admin` / `apprenant` via colonne `role` users | CHECK PostgreSQL `role IN (''admin'', ''apprenant'')` |
| Redirect post-login | Conditionnelle selon `role` dans `AuthenticatedSessionController` | UX adaptée par profil |

---

## Fichiers créés / modifiés

### Nouveaux fichiers

```
resources/css/app.scss              ← remplace app.css, importe Bootstrap
resources/css/_variables.scss       ← overrides Bootstrap ($primary, $body-bg, $font-family)
resources/views/layouts/app.blade.php       ← layout auth (sidebar + contenu)
resources/views/layouts/guest.blade.php     ← layout public (split panel)
resources/views/auth/login.blade.php
resources/views/auth/register.blade.php     ← admin uniquement
resources/views/auth/forgot-password.blade.php
resources/views/auth/reset-password.blade.php
resources/views/dashboard/admin.blade.php
resources/views/dashboard/apprenant.blade.php
resources/views/components/input-error.blade.php
app/Http/Middleware/EnsureRole.php
database/migrations/xxxx_add_role_to_users_table.php
```

### Fichiers modifiés

```
composer.json                       ← ajout laravel/breeze
package.json                        ← remplacement tailwindcss par bootstrap + sass
vite.config.js                      ← suppression tailwindcss plugin, ajout sass
app/Http/Controllers/Auth/AuthenticatedSessionController.php  ← redirect conditionnelle
bootstrap/app.php                   ← enregistrement EnsureRole middleware
routes/web.php                      ← routes protégées par rôle
```

---

## Design des vues

### `guest.blade.php` — Split layout 52/48

- **Panneau gauche (52%) :** fond `#1E8296`, carte blanche avec logo "Assidua", 3 stats fictives (icône + chiffre + libellé)
- **Panneau droit (48%) :** fond blanc, formulaire centré verticalement, bouton `.btn-primary` teal, lien "Mot de passe oublié"
- Hauteur 100vh, pas de scroll

### `app.blade.php` — Sidebar fixe + main

- **Sidebar :** 242px, fixe, fond `#FFF`, `border-right: 1px solid #EDF0F5`
  - Logo en haut (texte "Assidua" couleur `#1E8296`)
  - Nav items : Dashboard / Apprenants / Formations / Présences / Alertes
  - Badge rouge sur Alertes (compteur depuis DB)
  - Active state : `border-left: 3px solid #1E8296`, `background: rgba(30,130,150,0.08)`, texte `#1E8296`
  - Footer : cercle initiales (bg `#1E8296`), nom, bouton déconnexion
- **Main :** `margin-left: 242px`, fond `#F4F6F9`, padding 24px

### `dashboard/admin.blade.php`

- 4 stat cards Bootstrap : Total apprenants / Formations actives / Séances ce mois / Alertes actives
- Tableau des dernières séances (animation, date, nb présents, taux)
- Panneau alertes : liste avec badge sévérité (danger/warning)

### `dashboard/apprenant.blade.php`

- Card : taux d'assiduité personnel (chiffre large + `progress` Bootstrap colorée selon seuil)
- Card : historique des présences (tableau avec statut présent/absent/retard)

---

## Bootstrap — Variables SCSS

```scss
// resources/css/_variables.scss
$primary:                 #1E8296;
$body-bg:                 #F4F6F9;
$body-color:              #343A40;
$font-family-sans-serif:  'Plus Jakarta Sans', system-ui, sans-serif;
$font-size-base:          0.9375rem;     // 15px
$border-radius:           0.5rem;
$card-border-color:       #EDF0F5;
$card-box-shadow:         0 2px 8px rgba(0,0,0,.06);
$input-border-color:      #CED4DA;
$input-focus-border-color: #1E8296;
$input-focus-box-shadow:  0 0 0 0.2rem rgba(30,130,150,.2);
```

---

## Gestion des rôles

### Migration

```php
// Ajoute role avec valeur par défaut 'apprenant'
$table->string('role', 20)->default('apprenant');
// CHECK ajouté via DB::statement() dans up()
```

### Middleware `EnsureRole`

```php
// Vérifie auth()->user()->role === $role
// Redirige avec 403 ou vers dashboard si rôle incorrect
```

### Redirect post-login (dans `AuthenticatedSessionController@store`)

```php
return $request->user()->role === 'admin'
    ? redirect()->route('dashboard.admin')
    : redirect()->route('dashboard.apprenant');
```

### Routes

```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', ...)->name('dashboard.admin');
    Route::get('/apprenants', ...);
    Route::get('/formations', ...);
    // ...
});

Route::middleware(['auth', 'role:apprenant'])->group(function () {
    Route::get('/dashboard/apprenant', ...)->name('dashboard.apprenant');
});
```

---

## Plus Jakarta Sans

Chargée via Google Fonts (embed dans `guest.blade.php` et `app.blade.php`) :

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
```

---

## Contraintes et points d'attention

1. **Vite + SCSS** : remplacer `@tailwindcss/vite` par `vite-plugin-sass` ou utiliser le support SCSS natif de Vite (via `sass` npm package)
2. **Breeze et Tailwind** : Breeze installe par défaut Tailwind dans ses stubs — il faudra passer le flag `--blade` et supprimer manuellement les références Tailwind
3. **role CHECK** : ajouter via `DB::statement()` car Laravel Blueprint ne supporte pas nativement les CHECK constraints PostgreSQL
4. **Pas de register public** : la vue register existe mais ne sera accessible qu'à l'admin — protéger la route `/register` avec `role:admin`
