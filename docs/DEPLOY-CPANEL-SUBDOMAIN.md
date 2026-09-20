# Deploying to a cPanel demo subdomain

This covers putting the CodeIgniter 4 app (website + admin panel + API) live on a
demo subdomain of your existing cPanel hosting account — the same account that
already hosts other CodeIgniter projects on their own subdomains. Follow the same
document-root convention your other CI4 subdomains already use if it differs from
the one below; the important rule either way is **the web-facing document root must
be the app's `public/` folder, never the app root**, so `.env`, `app/`, `vendor/`
and the SQLite/writable folder are never directly downloadable.

The mobile app is **not** part of this deployment — Expo apps aren't hosted on a web
server. It's distributed separately (Expo EAS / TestFlight / Play internal testing)
once the subdomain gives it a live API to point at. See "Point the mobile app at the
demo API" at the end.

---

## 1. Decide the subdomain and confirm the file layout

Pick the demo subdomain, e.g. `mazowe.yourhostdomain.com`. In cPanel:

**Domains → Create A New Domain** (or **Subdomains**, on older cPanel themes)

- Domain: `mazowe.yourhostdomain.com`
- Document root: change the suggested path from `public_html/mazowe` to
  **`mazowe/backend/public`**

This creates `/home/<cpanel_user>/mazowe/backend/public` as the document root, with
`/home/<cpanel_user>/mazowe/backend/` (the app root: `app/`, `.env`, `vendor/`,
`writable/`, etc.) sitting one level above it — outside what the subdomain serves.
If your other CodeIgniter subdomains use a different folder name pattern (e.g.
everything under `public_html/<name>/public`), mirror that pattern instead for
consistency — just keep `public/` as the document root either way.

Click **Create**. cPanel will create the folder structure and issue a free AutoSSL
certificate for the subdomain automatically (usually within a few minutes — check
**Security → SSL/TLS Status** if it doesn't appear immediately).

## 2. Create the MySQL database

The app ships configured for SQLite (zero-setup local dev) but should run on MySQL
in any shared-hosting deployment, matching your other CI4 sites.

**Databases → MySQL Databases**

1. Create a database, e.g. `mazowe_demo` (cPanel will prefix it:
   `<cpanel_user>_mazowe_demo`).
2. Create a database user with a strong generated password.
3. Add that user to the database with **All Privileges**.
4. Note the three values: full database name, full username, password — you'll
   need them for `.env` in step 4.

## 3. Upload the application

From your machine (replace paths/host as appropriate — ask your host for the
SSH/SFTP hostname and port if unsure, it's often the same as the cPanel hostname):

```bash
# Package the backend (excludes vendor/, .env, writable DB, node_modules — none of
# that should travel as-is; vendor is reinstalled on the server, .env is written
# fresh there).
cd backend
zip -r ../mazowe-backend.zip . -x "vendor/*" ".env" "writable/mazowe.db" "writable/cache/*" "writable/logs/*" "writable/session/*" "writable/debugbar/*"
```

Upload `mazowe-backend.zip` via cPanel's **File Manager** into
`/home/<cpanel_user>/mazowe/backend/` and extract it there, or via SFTP:

```bash
sftp -P <port> <cpanel_user>@<host>
sftp> mkdir mazowe
sftp> mkdir mazowe/backend
sftp> put mazowe-backend.zip mazowe/backend/
sftp> exit

ssh -p <port> <cpanel_user>@<host>
cd mazowe/backend
unzip mazowe-backend.zip && rm mazowe-backend.zip
```

If SSH isn't available on the account, cPanel File Manager's **Extract** feature
does the same job after an ordinary upload — just slower for a repo this size.

### Install dependencies on the server

If the account has SSH with Composer available:

```bash
cd ~/mazowe/backend
composer install --no-dev --optimize-autoloader
```

If Composer isn't available server-side, run `composer install --no-dev
--optimize-autoloader` locally and upload the resulting `vendor/` folder too
(it's large — prefer SSH+Composer if at all possible).

### Fix permissions

```bash
chmod -R 755 ~/mazowe/backend/writable
```

`writable/` is where CI4 writes cache, logs, sessions, and (for now) the SQLite
file if you keep it — see step 4 for switching to MySQL instead.

## 4. Configure `.env` for the subdomain

Create `~/mazowe/backend/.env` (copy `env` as a starting point, this file is
intentionally not part of the uploaded zip) with:

```ini
CI_ENVIRONMENT = production

app.baseURL = 'https://mazowe.yourhostdomain.com/'

database.default.hostname = localhost
database.default.database = <cpanel_user>_mazowe_demo
database.default.username = <cpanel_user>_mazoweuser
database.default.password = '<the password from step 2>'
database.default.DBDriver = MySQLi
database.default.port = 3306

app.forceGlobalSecureRequests = true

JWT_SECRET = '<a long random string — generate a fresh one for this environment, don't reuse the local dev one>'

email.SMTPHost = <your host's SMTP server, e.g. mail.yourhostdomain.com>
email.SMTPUser = <a mailbox created in cPanel Email Accounts>
email.SMTPPass = '<its password>'
email.SMTPPort = 587
email.SMTPCrypto = tls
email.fromEmail = admissions@yourhostdomain.com
email.fromName = 'Mazowe Heights College'
```

Generate a fresh `JWT_SECRET`:

```bash
php -r "echo bin2hex(random_bytes(32));"
```

Set `email.*` to match a real mailbox on this hosting account (cPanel → **Email
Accounts**) so the contact/application forms actually deliver — without this,
submissions still save to the database and show up in the admin inbox, but no
email goes out.

## 5. Run migrations and seed the database

Via SSH:

```bash
cd ~/mazowe/backend
php spark migrate
php spark db:seed DatabaseSeeder
```

If there's no SSH access, most cPanel accounts have a **Terminal** icon in the
dashboard that gives the same shell — use that instead. As a last resort, some
hosts offer a "Cron Jobs" one-off trick (schedule a job that runs the command
once, then delete it) but Terminal or SSH is far simpler if either is available;
ask your host to enable one if neither is.

**Immediately after seeding**, log into `/admin/login` with the seeded default
(`admin@mazoweheights.ac.zw` / `MazoweHeights2027!`) and change that password from
**Users & roles** — this default is public (it's in the repo's README).

## 6. Select the right PHP version

**Software → MultiPHP Manager** → select the subdomain → set PHP **8.1 or newer**
(the app was built and tested against 8.2). Then **MultiPHP INI Editor** for that
domain and confirm/raise:

- `upload_max_filesize` and `post_max_size` — at least `10M` (for logo/media
  uploads in the admin panel)
- `memory_limit` — at least `128M`

## 7. Verify

- `https://mazowe.yourhostdomain.com/` — homepage loads, nav works, no PHP errors
- `https://mazowe.yourhostdomain.com/admin/login` — log in with the admin account
- Edit a page block or upload a logo in the admin panel → confirm it appears on
  the live site immediately
- Submit the contact form → confirm it lands in **Admin → Enquiries** and (if SMTP
  is configured) arrives by email
- `https://mazowe.yourhostdomain.com/api/v1/notices` → returns JSON

If you see a CodeIgniter error page instead of the site, double-check
`CI_ENVIRONMENT` — leave it as `production` for the live subdomain (a `development`
environment shows full stack traces to any visitor, which you don't want even on a
demo).

## 8. Point the mobile app at the demo API

For anyone testing the Expo app against this demo instead of a local backend:

```bash
cd mobile
EXPO_PUBLIC_API_URL=https://mazowe.yourhostdomain.com/api/v1 npx expo start
```

or set it permanently for this phase by adding an `.env` file in `mobile/`:

```
EXPO_PUBLIC_API_URL=https://mazowe.yourhostdomain.com/api/v1
```

---

## Notes specific to "demo, then migrate later"

- Keep the demo's `JWT_SECRET` and database credentials **separate** from whatever
  you'll use in production — don't reuse them, so nothing needs to be treated as
  "already leaked" once this subdomain is retired.
- Don't index the demo for search engines: add to `public/robots.txt`:
  ```
  User-agent: *
  Disallow: /
  ```
  (swap this back to normal once the real domain goes live).
- See `docs/MIGRATION-SUBDOMAIN-TO-PRODUCTION.md` for the full cutover plan once
  the client approves and you're moving to the dedicated production domain.
