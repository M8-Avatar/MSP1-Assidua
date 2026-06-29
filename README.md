# Assidua — Gestion des présences en formation

![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-17-336791?logo=postgresql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?logo=bootstrap&logoColor=white)
![Licence MIT](https://img.shields.io/badge/Licence-MIT-green)

---

## Description

**Assidua** est une application web de gestion des présences destinée aux organismes de formation.  
Elle permet à un administrateur de pointer les apprenants séance par séance, de suivre les taux d'assiduité en temps réel et de recevoir des alertes automatiques lorsqu'un apprenant descend sous le seuil de 75 % de présence.

### Fonctionnalités principales

| Module | Description |
|---|---|
| **Authentification** | Connexion sécurisée via Laravel Breeze, deux rôles : `admin` et `apprenant` |
| **Dashboard admin** | Statistiques globales, séances récentes, alertes non vues |
| **Dashboard apprenant** | Taux d'assiduité personnel, historique des présences |
| **Apprenants** | CRUD complet avec recherche et filtrage par formation |
| **Formations** | Gestion des formations (création, modification, suppression) |
| **Saisie des présences** | Pointage par séance avec statuts : Présent / Absent / Retard / Justifié |
| **Taux d'assiduité** | Recalcul automatique via trigger PostgreSQL après chaque pointage |
| **Alertes** | Génération automatique si taux < 75 %, avec marquage "vue admin / vue apprenant" |
| **Export PDF** | Feuille de présence exportable par formation et par date |
| **Sécurité** | CSP, X-Frame-Options, Referrer-Policy, politique de complexité des mots de passe |

---

## Stack technique

| Couche | Technologie |
|---|---|
| Backend | PHP 8.3 · Laravel 13 · Laravel Breeze |
| Base de données | PostgreSQL 17 (triggers natifs pour taux + alertes) |
| Frontend | Bootstrap 5.3 · Sass · Vite 8 |
| Authentification | Laravel Breeze (sessions, middleware rôles) |
| Moteur de templates | Blade |
| PDF | (DomPDF / Browsershot — selon configuration) |

---

## Installation

### Prérequis

- PHP ≥ 8.2 avec extensions `pdo_pgsql`, `mbstring`, `openssl`, `tokenizer`, `xml`
- PostgreSQL ≥ 14
- Composer ≥ 2
- Node.js ≥ 20 · npm ≥ 10

### 1. Cloner le dépôt

```bash
git clone https://github.com/<votre-compte>/MSP1-Assidua.git
cd MSP1-Assidua
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Installer les dépendances JS et compiler les assets

```bash
npm install
npm run build
```

> En développement, lancez `npm run dev` dans un terminal séparé pour activer le Hot Module Replacement de Vite.

### 4. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Éditez `.env` et renseignez vos paramètres PostgreSQL :

```dotenv
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=assidua
DB_USERNAME=postgres
DB_PASSWORD=votre_mot_de_passe

APP_URL=http://localhost:8000
APP_LOCALE=fr
```

### 5. Créer la base de données

```sql
-- Dans psql ou pgAdmin :
CREATE DATABASE assidua;
```

### 6. Exécuter les migrations

```bash
php artisan migrate
```

Les migrations créent les tables suivantes et installent les **triggers PostgreSQL** :
- `trigger_recalcul_taux` — recalcule `assiduites.taux` après chaque présence
- `trigger_alerte_assiduité` — insère une alerte si `taux < 75 %`

### 7. Peupler la base de données (données de démonstration)

```bash
php artisan db:seed
```

Le seeder génère :
- 1 compte admin · 10 apprenants
- 3 formations · 23 inscriptions
- Des centaines de présences réparties sur plusieurs mois
- Des taux d'assiduité calculés automatiquement par les triggers

### 8. Lancer le serveur

```bash
php artisan serve
```

L'application est accessible sur `http://localhost:8000`.

---

## Comptes de test

> Mot de passe universel : **`password`**

| Rôle | Nom | Email |
|---|---|---|
| **Admin** | Marie Laurent | `admin@assidua.fr` |
| Apprenant | Thomas Dupont | `thomas.dupont@apprenant.fr` |
| Apprenant | Sophie Martin | `sophie.martin@apprenant.fr` |
| Apprenant | Emma Petit | `emma.petit@apprenant.fr` |
| Apprenant | Maxime Roux | `maxime.roux@apprenant.fr` |

Les apprenants ont des profils d'assiduité variés (excellent, bon, fragile, problématique) pour permettre de tester toutes les situations d'alerte.

---

## Captures d'écran

> Les captures ci-dessous illustrent les pages principales de l'application.

### Page de connexion
![Login](public/images/screenshots/login.png)

### Dashboard administrateur
![Dashboard Admin](public/images/screenshots/dashboard-admin.png)

### Saisie des présences
![Présences](public/images/screenshots/presences.png)

### Dashboard apprenant
![Dashboard Apprenant](public/images/screenshots/dashboard-apprenant.png)

### Liste des apprenants
![Apprenants](public/images/screenshots/apprenants.png)

---

## Architecture du projet

```
MSP1-Assidua/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ApprenantController.php     # CRUD apprenants
│   │   │   ├── DashboardController.php     # Vues admin + apprenant
│   │   │   ├── FormationController.php     # CRUD formations
│   │   │   ├── PresenceController.php      # Saisie des présences
│   │   │   ├── AlerteController.php        # Gestion des alertes
│   │   │   ├── PdfController.php           # Export PDF
│   │   │   └── ProfileController.php       # Profil utilisateur
│   │   └── Middleware/
│   │       ├── EnsureRole.php              # Vérification du rôle (admin/apprenant)
│   │       └── SecurityHeaders.php         # CSP + headers de sécurité
│   └── Models/
│       ├── User.php
│       ├── Formation.php
│       ├── Inscription.php
│       ├── Presence.php
│       ├── Assiduite.php
│       ├── Alerte.php
│       └── Animation.php
├── database/
│   ├── migrations/                         # Schéma + triggers PostgreSQL
│   └── seeders/
│       └── DatabaseSeeder.php              # Données de démonstration réalistes
├── resources/
│   ├── css/
│   │   └── app.scss                        # Design system Assidua (couleur #1E8296)
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php               # Layout principal (sidebar + topbar)
│       │   └── guest.blade.php             # Layout authentification
│       ├── dashboard/
│       │   ├── admin.blade.php
│       │   └── apprenant.blade.php
│       ├── presences/
│       │   └── index.blade.php             # Pointage interactif temps réel
│       ├── apprenants/                     # CRUD apprenants
│       ├── formations/                     # CRUD formations
│       └── auth/                           # Vues Breeze (login, register…)
└── routes/
    └── web.php                             # Routes avec middleware rôles
```

### Schéma de la base de données

```
users ──────────────────────────────────────────────┐
  id, nom, prenom, email, role                      │
       │                                            │
       └── inscriptions ──────── formations         │
             id, user_id,          id, nom,         │
             formations_id         date_debut/fin    │
                  │                                 │
                  ├── presences                     │
                  │     id, inscription_id,         │
                  │     date, statut, observation   │
                  │     ▲ TRIGGER → recalcul taux   │
                  │                                 │
                  └── assiduites                    │
                        id, inscription_id, taux    │
                        ▲ TRIGGER → alerte si <75%  │
                              │                     │
                              └── alertes           │
                                    id,             │
                                    assiduite_id,   │
                                    date_alerte,    │
                                    vue_admin,      │
                                    vue_apprenant   │
                                                    │
animations ─────────────────────────────────────────┘
  id, user_id, formations_id
```

**Triggers PostgreSQL :**
- `trigger_recalcul_taux` — AFTER INSERT/UPDATE/DELETE sur `presences` → met à jour `assiduites.taux`
- `trigger_alerte_assiduité` — AFTER INSERT/UPDATE OF taux sur `assiduites` → insère dans `alertes` si taux < 75 % (contrainte `UNIQUE(assiduite_id)` empêche les doublons)

---

## Sécurité

- **Politique de mots de passe** : 8 caractères minimum, majuscule, chiffre, caractère spécial, vérification HaveIBeenPwned (création de compte)
- **Content Security Policy** : header CSP appliqué sur toutes les réponses via middleware
- **Protection CSRF** : token Blade `@csrf` sur tous les formulaires POST
- **Contrôle d'accès** : middleware `role:admin` et `role:apprenant` sur chaque groupe de routes
- **En production** : remplacer `'unsafe-inline'` dans le CSP par des nonces Vite

---

## Licence

Ce projet est distribué sous licence **MIT**.  
Voir le fichier [LICENSE](LICENSE) pour les détails.