# Mazowe Heights College — School Management System

Build plan for VS Code + Claude. Three products, one backend.

| Product | Stack | Who uses it |
|---|---|---|
| Public website | CodeIgniter 4 views + Tailwind (server-rendered) | Public, prospective parents |
| Admin panel (SMS) | CodeIgniter 4 + HTMX + Alpine.js | Staff, admin, bursary, boarding |
| REST API | CodeIgniter 4 (`/api/v1`), JWT | Consumed by the mobile apps |
| Mobile apps | React Native on Expo (one app, three roles) | Parents, students, teachers |

**Frontend recommendation: CodeIgniter 4 views with Tailwind.** Not a separate SPA. One deployment, one auth session, SEO that works out of the box for admissions, and the admin panel can reuse the same layout partials and design tokens. HTMX covers the interactive bits (live menu, fixture updates, application tracker) without a build pipeline you have to babysit.

---

## 0. Design tokens (do this first)

Extract from the HTML design into `app/Views/assets/tokens.css` and mirror into `mobile/theme.ts`.

```
blue     #0b3d6b   blue-700 #08294a   blue-100 #e7eef5
red      #ec3013   red-600  #dd2b0f   red-100  #fff2ef
ink      #201e1d   ground   #f3f2f2   paper    #ffffff
font     Archivo (400 / 600 / 800)
radius   0px everywhere
rules    2px solid ink for section dividers, 1px divider inside cards
```

Rules that make the three surfaces look like one product: zero border radius, flush-left everything (including button labels), 2px section rules, red for primary action only, blue for structure and navigation, photography in grayscale.

---

## 1. Repository layout

```
mazowe/
├─ backend/              CodeIgniter 4 — website + admin + API
│  ├─ app/Controllers/Web/        public site
│  ├─ app/Controllers/Admin/      admin panel
│  ├─ app/Controllers/Api/V1/     mobile API
│  ├─ app/Models/  app/Entities/  app/Services/
│  ├─ app/Views/{web,admin,components}/
│  └─ app/Database/{Migrations,Seeds}/
├─ mobile/               Expo (expo-router, TypeScript)
└─ docs/                 this plan, ERD, API contract
```

---

## 2. Database — core tables

Multi-tenant-ready but single-school for now. MySQL 8, UUID v7 primary keys, soft deletes, `created_by` audit columns everywhere.

**Identity** — `users`, `roles`, `permissions`, `role_user`, `password_resets`, `devices` (push tokens), `audit_log`

**People** — `students`, `guardians`, `guardian_student` (pivot with relationship + portal permissions), `staff`, `staff_subjects`

**Academic** — `academic_years`, `terms`, `year_groups` (Form 1–Upper Sixth), `classes`, `subjects`, `pathways` (Cambridge / ZIMSEC), `class_subject_teacher`, `timetable_periods`, `timetable_entries`, `enrolments`

**Assessment** — `assessments`, `assessment_scores`, `effort_grades`, `reports`, `report_comments`, `exam_entries` (Cambridge/ZIMSEC external)

**Attendance & conduct** — `attendance_marks` (per period), `merits`, `demerits`, `incidents`, `safeguarding_notes` (restricted visibility)

**Boarding** — `houses`, `dormitories`, `beds`, `bed_allocations`, `exeat_requests`, `sanatorium_visits`, `tuck_accounts`, `tuck_transactions`

**Catering** — `menu_cycles`, `menu_days`, `menu_items`, `dietary_flags`, `student_dietary_needs`, `meal_ratings`

**Finance** — `fee_structures`, `fee_items`, `invoices`, `invoice_lines`, `payments`, `payment_methods`, `discounts`, `instalment_plans`, `bursaries`, `donations`, `refunds`

**Admissions** — `enquiries`, `applications`, `application_documents`, `assessment_sittings`, `application_events` (the tracker timeline), `offers`

**Transport** — `routes`, `stops`, `vehicles`, `route_subscriptions`, `vehicle_pings`, `boarding_scans`

**Content** — `pages`, `posts`, `post_categories`, `events`, `event_tickets`, `ticket_purchases`, `notices`, `media`, `documents`, `vacancies`, `job_applications`

**Library** — `catalogue_items`, `copies`, `loans`, `reservations`

**Comms** — `message_threads`, `messages`, `notifications`, `notification_preferences`, `sms_log`, `email_log`

---

## 3. Roles and permissions

`super_admin, head, deputy_academic, deputy_pastoral, bursar, finance_clerk, admissions_officer, registrar, hod, teacher, tutor, house_parent, matron, nurse, counsellor, librarian, transport_manager, catering_manager, parent, student`

Permission checks go through a single `AuthService::can($user, $ability, $subject)` filter, not scattered `if ($role ===` checks. Safeguarding notes are visible only to `deputy_pastoral`, `head`, and named DSLs — enforce at the query layer, not the view.

---

## 4. API surface (`/api/v1`)

JWT access token (15 min) + refresh token (30 days), device-bound. Every list endpoint is cursor-paginated and returns `updated_at` so the app can sync incrementally.

```
POST   /auth/login  /auth/refresh  /auth/logout  /auth/device
GET    /me                                    role, children, permissions
GET    /students/{id}/timetable
GET    /students/{id}/attendance?term=
GET    /students/{id}/grades  /reports  /reports/{id}.pdf
GET    /students/{id}/conduct
GET    /students/{id}/homework      POST /homework/{id}/submit
GET    /invoices  /invoices/{id}    POST /payments/initiate  /payments/{id}/verify
GET    /menu/week?date=             POST /menu/{itemId}/rate
GET    /events  /events/{id}        POST /events/{id}/tickets
GET    /fixtures?team=              GET  /results
GET    /notices  /posts  /posts/{slug}
GET    /transport/routes/{id}/live  POST /transport/subscribe
POST   /exeat-requests              GET  /exeat-requests
GET    /threads  /threads/{id}      POST /threads/{id}/messages
GET    /library/search              GET  /library/loans
POST   /applications                GET  /applications/{ref}/status
GET    /library/... etc.
```

Teacher-only: `POST /attendance/bulk`, `POST /assessments/{id}/scores`, `POST /conduct`, `POST /homework`.

---

## 5. Admin panel modules

Each is a CRUD module plus one or two workflows. Build them in this order — the earlier ones unblock the later ones.

1. **Setup** — academic years, terms, year groups, subjects, pathways, houses, rooms
2. **People** — students, guardians, staff; bulk CSV import; photo capture
3. **Admissions** — enquiry inbox, application review queue, document verification, assessment scheduling, offer generation, tracker events (each status change writes an `application_events` row the public tracker reads)
4. **Enrolment** — accept offer → create student + user + guardian accounts + invoice, all in one transaction
5. **Timetable** — periods, class-subject-teacher assignment, clash detection, per-teacher and per-class views
6. **Attendance** — per-period register (teacher app + web), absence follow-up queue, parent auto-notification
7. **Assessment & reports** — assessment definitions, mark entry with set averages, effort grades, comment bank, report generation to PDF, publish-to-portal switch
8. **Finance** — fee structures, invoice run (batch generate per term), payment capture and reconciliation, instalment plans, discounts, bursaries, statements, aged-debt report
9. **Boarding** — bed allocation board (drag and drop), exeat approval flow, sanatorium log, tuck accounts
10. **Catering** — 4-week menu cycle editor, publish-week action, allergen flags, rating dashboard
11. **Transport** — routes, stops, vehicle assignment, live map, boarding scan log
12. **Content** — pages, posts, events + ticketing, notices, media library, vacancies
13. **Library** — catalogue, copies, issue/return, overdue list
14. **Comms** — message threads, broadcast to segment (year group / house / team / route), SMS and push, delivery log
15. **Reports & analytics** — enrolment funnel, fee collection rate, attendance trends, grade distributions, house points

---

## 6. Payment integrations

Abstract behind `PaymentGatewayInterface` with `initiate()`, `verify()`, `webhook()`. One adapter per provider so a failing gateway is swappable.

- **EcoCash** — Econet merchant API, push-to-subscriber then poll/webhook for confirmation
- **ZIPIT / bank transfer** — no API; upload bank statement CSV, auto-match on invoice reference, manual match queue for the rest
- **Visa / Mastercard** — Paynow or Stripe depending on settlement preference; 3-D Secure redirect
- **InnBucks** — QR on invoice, webhook confirmation
- **Cash** — bursary till interface, numbered receipt, end-of-day cash-up
- **PayPal** — diaspora, USD, fee shown before confirm

Every payment writes an immutable `payments` row and a receipt PDF; never mutate a payment, reverse it with a linked refund row.

---

## 7. Mobile app (Expo)

One app, role-switched at login. `expo-router` file-based routing, TanStack Query for server state with offline persistence, `expo-notifications` for push, `expo-secure-store` for tokens.

```
app/
├─ (auth)/login.tsx
├─ (parent)/  index  children/[id]  fees  menu  events  bus  messages  exeat
├─ (student)/ index  timetable  homework  grades  menu  library  clubs
├─ (teacher)/ index  register  marks  homework  conduct  classes
└─ _layout.tsx        role guard → redirects to the right group
```

Offline: cache timetable, menu, grades and notices; queue attendance marks and homework submissions and flush on reconnect. Zimbabwean data reality — assume patchy 3G and design every screen to render from cache first.

Theme: import the same tokens. Square corners, Archivo, red primary buttons with flush-left labels, blue headers, white cards with 1px divider borders.

---

## 8. Build order

**Phase 1 — foundation (2 weeks).** CI4 skeleton, auth, roles/permissions, audit log, design tokens, layout partials, admin shell with sidebar nav.

**Phase 2 — public website (2 weeks).** All 40 public pages as CI4 views against seeded content tables. Contact form, enquiry capture, online application form, application tracker.

**Phase 3 — core school data (3 weeks).** Setup module, people, enrolment, timetable. Seeders for a realistic 420-pupil school so every later module has data to work against.

**Phase 4 — daily operations (3 weeks).** Attendance, homework, conduct, assessment and reports. This is where teachers first touch the system.

**Phase 5 — finance (3 weeks).** Fee structures, invoice runs, all six payment gateways, statements, bursaries, donations.

**Phase 6 — boarding and catering (2 weeks).** Bed allocation, exeats, sanatorium, tuck, menu cycle, ratings.

**Phase 7 — API (2 weeks).** Every endpoint above, versioned, documented with OpenAPI, Postman collection committed.

**Phase 8 — mobile app (4 weeks).** Parent role first (highest value), then student, then teacher.

**Phase 9 — the rest (3 weeks).** Transport tracking, library, events and ticketing, comms broadcast, analytics.

**Phase 10 — hardening (2 weeks).** Load test, penetration test, backup and restore drill, staff training material, data migration from whatever the school currently uses.

---

## 9. Working with Claude in VS Code

- Commit `docs/BUILD-PLAN.md` and `docs/ERD.md` and reference them in every prompt: *"Following docs/BUILD-PLAN.md section 5.8, build the invoice run."*
- Keep a `docs/CONVENTIONS.md`: naming, controller/model/service split, validation location, response envelope shape. Claude drifts without it.
- Work one module at a time: migration → model → service → controller → view → test. Do not let it generate a whole phase in one pass.
- Ask for the migration and the seeder together, always. A module with no seed data cannot be reviewed.
- After each module: `php spark migrate`, run the seeder, click through it, then commit. Never stack two unreviewed modules.
- For the mobile app, give Claude the OpenAPI spec as context rather than describing endpoints in prose.

---

## 10. Non-negotiables

- Safeguarding data is access-controlled at the query layer and every read is logged.
- Nightly encrypted off-site backups, with a restore tested monthly.
- Payments are append-only; a mistake is corrected with a reversal, never an edit.
- Parents can only ever see their own children — enforce with a global scope, not per-controller checks.
- All money in USD cents as integers. No floats anywhere near an invoice.
- SMS matters more than email in this market. Budget for it and log every send.
