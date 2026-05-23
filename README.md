# QR Attendance (Laravel 12 + MySQL + Tailwind)

QR attendance system with two roles:
- **Officer**: imports students, manages subjects/classes/rosters/sessions, scans QR on mobile, exports attendance CSV.
- **Student**: claims account, logs in with student number + password, views QR and attendance history.

## Core Rules Implemented

- Auth uses **username + password** for both roles.
- Students use `student_no` as username after claim.
- Roles: `officer`, `student`.
- Option A claim flow:
  - officers import CSV (`full_name`, `section`)
  - student claims by `full_name + section`, then sets `student_no + password`
  - students cannot self-change password (no reset/change routes)
- Domain: subjects, classes (`school_year` + `semester` are free text), manual roster via pivot.
- Sessions per class: create/open/close.
- On open: pre-create attendance for roster students with default `ABSENT`.
- Scan result:
  - before late cutoff => `PRESENT`
  - after late cutoff but before end => `LATE`
  - after end => rejected
  - duplicate scans are rejected as already recorded
- Student QR payload format:
  - `sn=<student_no>&sig=<hmac>`
  - `sig = base64url(HMAC_SHA256(student_no, QR_SECRET))`

## Setup

1. Copy environment:
   ```bash
   cp .env.example .env
   ```
2. Configure `.env` for MySQL:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=qr_attendance
   DB_USERNAME=root
   DB_PASSWORD=

   QR_SECRET=replace-with-a-long-random-secret
   ```
3. Install dependencies and generate key:
   ```bash
   composer install
   php artisan key:generate
   npm install
   ```
4. Run migrations + seed:
   ```bash
   php artisan migrate --seed
   ```
5. Build assets:
   ```bash
   npm run build
   ```
6. Start app:
   ```bash
   php artisan serve
   ```

## Default Seeded Officer Account

- Username: `officer`
- Password: `password123`

## Main Routes

- `GET /login`
- `GET /claim`
- Officer area (auth + role:officer):
  - `/officer/students/import`
  - `/officer/subjects`
  - `/officer/classes`
  - `/officer/classes/{classroom}/roster`
  - `/officer/classes/{classroom}/sessions`
  - `/officer/sessions/{session}/scan`
  - `/officer/sessions/{session}/attendance`
  - `/officer/sessions/{session}/export`
- Student area (auth + role:student):
  - `/student/qr`
  - `/student/history`
