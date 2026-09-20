# Mazowe Heights College — Backend (CodeIgniter 4)

One CodeIgniter 4 application serving three things, per `docs/BUILD-PLAN.md`:

- **Public website** — `app/Controllers/Web`, `app/Views/web` — 43 content-driven pages rendered from the `pages` / `page_blocks` tables.
- **Admin panel (CMS)** — `app/Controllers/Admin`, `app/Views/admin`, at `/admin` — edit every page's text/images, site settings (logo, contact details), notices, events, blog posts, vacancies, and view admissions/contact enquiries.
- **REST API for the mobile app** — `app/Controllers/Api/V1`, at `/api/v1` — JWT-based auth plus read endpoints the Expo app consumes.

## Local setup

```bash
composer install
cp env .env            # already done in this repo; edit as needed
php spark migrate
php spark db:seed DatabaseSeeder
php -S localhost:8080 -t public
```

Visit `http://localhost:8080/` for the website and `http://localhost:8080/admin/login` for the admin panel.

**Default admin login:** `admin@mazoweheights.ac.zw` / `MazoweHeights2027!` — change this immediately in Users & roles once you have real staff accounts.

## Database

Ships with SQLite (`writable/mazowe.db`) so it runs with zero external services. To move to MySQL for production, edit `.env`:

```
database.default.hostname = <host>
database.default.database = <db>
database.default.username = <user>
database.default.password = <pass>
database.default.DBDriver = MySQLi
```

then re-run `php spark migrate` and `php spark db:seed DatabaseSeeder` against the new database.

## Content model

Every public page is a row in `pages` with an ordered list of `page_blocks` (type + JSON `data`). The admin panel's Pages screen edits that JSON through generated forms — simple fields become text inputs, list/array fields become a labeled JSON textarea. Website templates in `app/Views/web/blocks/*.php` render each block type; adding a new block type means adding one partial there and offering it in the admin "Add a block" dropdown (`app/Views/admin/pages/edit.php`).

Site-wide settings (logo, address, phone, emails, banner text, social links) live in the `site_settings` key/value table, edited from `/admin/settings` — this is also where the header and footer logo images are uploaded and swapped.

## Contact & admissions

- The public contact form and the admissions application form both write to the `enquiries` table and attempt to email `admissions_email`/`contact_email` (via `Config\Services::email()` — configure SMTP in `.env` for production; failures are logged, not fatal).
- Staff review and update the status of every submission from `/admin/enquiries`.

## API (mobile app)

```
POST /api/v1/auth/login        { email, password } -> { access_token, refresh_token, user }
POST /api/v1/auth/refresh      { refresh_token }    -> { access_token }
GET  /api/v1/me                (Bearer token)
GET  /api/v1/notices
GET  /api/v1/events
GET  /api/v1/posts
GET  /api/v1/pages/{group}/{slug}
POST /api/v1/contact
POST /api/v1/applications
```

Access tokens are short-lived JWTs (15 min); refresh tokens last 30 days. Set `JWT_SECRET` in `.env` to a long random value in production (already generated for local dev).

## What's built vs scaffolded

Built and working end-to-end: the full public website (43 pages), the CMS content/media/settings editor, admissions & contact capture with an inbox, notices/events/blog/vacancies CRUD, staff user management, and a JWT API layer.

Not yet built (see `docs/BUILD-PLAN.md` for the full roadmap — timetable, attendance, assessment/reports, finance/payment gateways, boarding operations, transport tracking, library circulation): those need their own migrations, models, admin modules and API endpoints, to be built module-by-module against real school data once the founding intake is closer.
