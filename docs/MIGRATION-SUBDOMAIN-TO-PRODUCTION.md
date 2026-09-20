# Migrating from the demo subdomain to the production domain

This is the plan for the day the client approves the site and you move it from the
demo subdomain (e.g. `mazowe.yourhostdomain.com`) to the real production domain
(e.g. `mazoweheights.ac.zw`). It assumes the production domain will be **hosted on
its own dedicated hosting account** (its own cPanel account, not another subdomain
of the reseller/demo account) — adjust step 1 if it's actually staying on the same
account under its own domain instead of a fresh one.

Budget half a day for this with a low-traffic window (early morning) for the final
DNS cutover, plus 24–48 hours of DNS propagation monitoring afterward.

---

## 0. Before you start — decide what carries over

Talk to the client about which of these should carry over from the demo, vs. start
clean in production:

- **Content edited during the demo period** (any page text/images the client
  changed via the admin panel while reviewing) — normally yes, carry it over.
- **Enquiries/applications submitted during the demo** — these are almost
  certainly test submissions, not real families. Confirm with the client, but
  default to **not** carrying these over (clear the `enquiries` table on the
  production database instead of copying demo test data into it).
- **Admin user accounts** — recreate real staff accounts fresh in production
  rather than copying demo ones; this is a natural point to retire any shared
  demo login and give every staff member their own account.

## 1. Provision the production hosting

- New hosting account (or new addon domain on an existing account) with the
  production domain pointed at it via nameservers or an A record, per your host's
  instructions.
- Same requirements as the demo: PHP 8.1+, MySQL, SSH access if at all possible
  (makes steps 3–5 far faster), a mailbox for `email.SMTPUser`.
- Follow `docs/DEPLOY-CPANEL-SUBDOMAIN.md` steps 1–3 again against the new
  hosting account and the production domain, with the document root convention
  `mazowe/backend/public` (or whatever convention you used for the demo — keep it
  consistent). Stop before step 4 (`.env`) and come back here.

## 2. Export the demo database

On the demo server:

```bash
mysqldump -u <demo_db_user> -p <demo_db_name> > mazowe-demo-export.sql
```

If the client wants a clean start on submissions (per step 0), strip those tables
before importing into production:

```bash
mysqldump -u <demo_db_user> -p <demo_db_name> \
  --ignore-table=<demo_db_name>.enquiries \
  > mazowe-demo-export-no-enquiries.sql
```

Download the `.sql` file to your machine (via SFTP or cPanel File Manager), then
delete it from the demo server once you've confirmed it downloaded correctly.

## 3. Import into the production database

Create the production database and user in the new hosting account's cPanel
(**MySQL Databases** — same as demo step 2), then import:

```bash
mysql -u <prod_db_user> -p <prod_db_name> < mazowe-demo-export.sql
```

If you stripped `enquiries` in step 2, run migrations instead to recreate that
table empty:

```bash
cd ~/mazowe/backend
php spark migrate
```

## 4. Deploy the application code to production

Same as `docs/DEPLOY-CPANEL-SUBDOMAIN.md` step 3 (upload + `composer install
--no-dev --optimize-autoloader` + `chmod -R 755 writable`), pointed at the new
hosting account. If your git remote is reachable from the new server, `git clone`
is cleaner than the zip-upload approach — either works.

## 5. Configure `.env` for production

This is the step with the real differences from the demo:

```ini
CI_ENVIRONMENT = production

app.baseURL = 'https://mazoweheights.ac.zw/'

database.default.hostname = localhost
database.default.database = <prod_db_name>
database.default.username = <prod_db_user>
database.default.password = '<prod db password>'
database.default.DBDriver = MySQLi
database.default.port = 3306

app.forceGlobalSecureRequests = true

JWT_SECRET = '<generate a NEW random value — do not reuse the demo one>'

email.SMTPHost = <production mailbox's SMTP host>
email.SMTPUser = admissions@mazoweheights.ac.zw
email.SMTPPass = '<its password>'
email.SMTPPort = 587
email.SMTPCrypto = tls
email.fromEmail = admissions@mazoweheights.ac.zw
email.fromName = 'Mazowe Heights College'
```

Then update the site's own contact details from the **admin panel → Site
settings** (not `.env` — these are content, stored in the database): admissions
email, phone, address, social links, if any of these were demo placeholders.

Remove the `robots.txt` block-all rule that was added on the demo (see the last
section of `DEPLOY-CPANEL-SUBDOMAIN.md`) — production should be indexable.

## 6. Recreate real staff accounts

From **Admin → Users & roles**, create accounts for actual staff with their real
email addresses and roles, then either delete the seeded default admin account or
change its password to something known only to you, held securely, for emergency
access. Don't leave `admin@mazoweheights.ac.zw` / `MazoweHeights2027!` active in
production — this password is public (it's in the repo).

## 7. Test production end-to-end, on the new domain, before cutting over

Use the production hosting account's temporary access URL (every host provides
one, e.g. `https://server-ip/~cpaneluser/` or a temporary subdomain) so you can
verify the app fully works **before** DNS points real traffic there:

- Homepage, admin login, a content edit reflecting live, contact form submission
  and email delivery, application form submission
- Every nav link (all 43 pages) loads without error
- SSL will not be issuable via AutoSSL until DNS actually points at the new
  server, so this pre-cutover check will show a certificate warning for the
  temporary URL — that's expected and not a problem to fix, ignore it here.

## 8. DNS cutover

- Lower the DNS TTL on the production domain's A/CNAME records to 300 seconds
  at least 24 hours before the cutover, if it isn't already low — this shortens
  how long the old value can linger in resolvers.
- At the agreed low-traffic window, update the domain's DNS (A record, or
  nameservers, per how the domain is currently pointed) to the new production
  hosting account's IP.
- Once DNS has propagated (check with `dig mazoweheights.ac.zw` from a few
  different networks, or a tool like whatsmydns.net), issue the SSL certificate:
  cPanel's AutoSSL should pick it up automatically within a few hours of DNS
  resolving correctly; you can also trigger it manually from **SSL/TLS Status**.
- Confirm `https://mazoweheights.ac.zw/` serves the site with a valid certificate
  (no more "not secure" warning) before considering the cutover done.

## 9. Retire the demo subdomain

Once production is confirmed stable (give it a few days):

- Point the demo subdomain (`mazowe.yourhostdomain.com`) to redirect to the
  production domain, rather than deleting it outright — anyone with the demo
  link bookmarked lands in the right place instead of a dead page. A simple
  `.htaccess` redirect in the demo's document root:
  ```apache
  RewriteEngine On
  RewriteRule ^(.*)$ https://mazoweheights.ac.zw/$1 [R=301,L]
  ```
- After a few weeks of the redirect being in place with no incoming traffic of
  note, you can safely delete the demo subdomain, its database, and its
  filesystem copy of the app.

## 10. Update the mobile app

- Update `EXPO_PUBLIC_API_URL` (see `mobile/README.md`) to
  `https://mazoweheights.ac.zw/api/v1` in whatever build configuration you use
  for the release build (EAS build profile env var, or `mobile/.env`).
- Rebuild and resubmit to app stores / redistribute via EAS — anyone who
  installed a build pointed at the demo API needs the new build to keep working
  once the demo subdomain redirect (step 9) or eventual deletion happens.

## Rollback plan

If something goes wrong immediately after the DNS cutover (step 8):

1. Revert the DNS record back to the demo subdomain's IP (or to a "site under
   maintenance" holding page if you have one) — this takes effect as fast as DNS
   propagates, same as the cutover itself.
2. The demo subdomain is untouched at this point (you haven't deleted it — see
   step 9), so it's immediately available as a fallback while you debug
   production.
3. Fix the issue against the production hosting account without time pressure,
   re-test via its temporary access URL (step 7), then re-attempt the DNS
   cutover.

Because step 9 (retiring the demo) only happens well after a confirmed-stable
cutover, this rollback is always available for as long as you need it.
