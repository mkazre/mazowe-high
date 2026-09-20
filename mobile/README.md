# Mazowe Heights — Mobile App (Expo)

One Expo app, role-switched at login, per `docs/BUILD-PLAN.md` §7. Built with `expo-router` (file-based routing), TanStack Query for server state, `expo-secure-store` for tokens, and design tokens mirrored from the website (`src/theme/tokens.ts` ↔ `backend/public/assets/css/site.css`).

## Structure

```
app/
├─ _layout.tsx      role guard — redirects signed-out users to /login, and each
│                   signed-in role into its own screen group
├─ login.tsx        real screen, calls POST /api/v1/auth/login
├─ parent/          index (real: notices + events from the API), fees, menu,
│                   messages, events, bus, exeat, children/[id]
├─ student/         index (real), timetable, homework, grades, menu, library, clubs
└─ teacher/         index (real), register, marks, homework, conduct, classes
```

Screens marked "real" call the live backend. The rest render a clearly labelled
"coming soon" state (`src/components/ComingSoon.tsx`) because their backend
modules (finance, timetable, assessment, attendance, transport, boarding) are
scaffolded on the roadmap but not built yet — see `backend/README.md`.

## Running it

```bash
npm install
npx expo start
```

Point the app at your backend by setting `EXPO_PUBLIC_API_URL` (defaults to
`http://localhost:8080/api/v1`, which only works from a simulator on the same
machine as the backend). For a physical device on the same Wi-Fi as your dev
machine, run:

```bash
EXPO_PUBLIC_API_URL=http://<your-lan-ip>:8080/api/v1 npx expo start
```

## Auth

Sign in with a staff account seeded on the backend (`admin@mazoweheights.ac.zw`
/ `MazoweHeights2027!`) to see the app boot into a role's screen group — the
role guard in `app/_layout.tsx` maps `teacher`/`tutor`/`hod` → the teacher
group, `student` → the student group, and everything else → the parent group.
Parent and student portal logins open with the founding intake in January
2027, once real guardian/student accounts exist.
