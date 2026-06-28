# Assidua - Breeze + Bootstrap 5 Implementation Plan
**Date:** 2026-06-29

## Tasks
1. Install Laravel Breeze (Blade)
2. Replace Tailwind with Bootstrap 5 + SCSS  
3. Add role column + EnsureRole middleware
4. Create guest.blade.php (split layout)
5. Rewrite auth views (login, forgot-password, reset-password, verify, confirm)
6. Create app.blade.php (sidebar + topbar)
7. Conditional post-login redirect + role-protected routes
8. Create dashboard/admin.blade.php (4 stat cards + sessions + alerts)
9. Create dashboard/apprenant.blade.php (personal rate + history)

## Key Files
- resources/css/_variables.scss (Bootstrap tokens: primary=#1E8296, bg=#F4F6F9)
- resources/css/app.scss (Bootstrap + custom .sidebar, .stat-card, .table-assidua)
- resources/js/app.js (import bootstrap)
- vite.config.js (input: app.scss, no tailwind plugin)
- app/Http/Middleware/EnsureRole.php
- database/migrations/xxxx_add_role_to_users_table.php
- resources/views/layouts/guest.blade.php (52/48 split)
- resources/views/layouts/app.blade.php (242px fixed sidebar)
- resources/views/dashboard/admin.blade.php
- resources/views/dashboard/apprenant.blade.php

See full spec: docs/superpowers/specs/2026-06-29-breeze-bootstrap-auth-design.md
See mockup: HTML file provided in conversation (Claude Design export)
