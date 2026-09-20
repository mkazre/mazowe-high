# Mazowe Heights College — School Management System

Public website, admin panel (CMS + admissions inbox), REST API, and mobile app for Mazowe Heights College, built per `BUILD-PLAN.md`.

## Structure

```
backend/     CodeIgniter 4 — public website + admin panel + REST API (see backend/README.md)
mobile/      Expo React Native app — parent / student / teacher (see mobile/README.md)
BUILD-PLAN.md   the original full roadmap this was built against
```

The three original `*.dc.html` files and `_ds/`, `support.js`, `image-slot.js` are the
original design-tool mockups this project was built from — kept for reference, not deployed.

## Quick start

**Backend + website + admin panel:**

```bash
cd backend
composer install
php spark migrate
php spark db:seed DatabaseSeeder
php -S localhost:8080 -t public
```

- Website: http://localhost:8080/
- Admin panel: http://localhost:8080/admin/login (`admin@mazoweheights.ac.zw` / `MazoweHeights2027!`)
- API: http://localhost:8080/api/v1/...

**Mobile app:**

```bash
cd mobile
npm install
npx expo start
```

See each app's own README for details, environment variables, and what's built vs. still scaffolded against `BUILD-PLAN.md`'s longer roadmap (finance, timetable, boarding operations, transport, library, etc.).

## Deployment

- `docs/DEPLOY-CPANEL-SUBDOMAIN.md` — putting this on a cPanel demo subdomain for client review.
- `docs/MIGRATION-SUBDOMAIN-TO-PRODUCTION.md` — the plan for moving from that demo subdomain to the dedicated production domain once approved.
