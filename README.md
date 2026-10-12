# SubTrack — Business Subscription Revenue Tracker

A web-based system for a business to manage subscription customers, track
recurring payments, calculate revenue metrics (MRR, ARR, ARPU, churn), monitor
renewals and cancellations, and visualize revenue on an interactive dashboard.

Built with **PHP + MySQL + Chart.js**, structured around SOLID principles.

## 1. Requirements

- PHP 7.4+ (works out of the box with XAMPP / WAMP / MAMP, including older bundled PHP versions)
- MySQL / MariaDB
- A browser

No Composer packages are required — everything runs on plain PHP + PDO.
Chart.js is vendored locally at `public/assets/js/chart.umd.min.js`, so the
charts work even with no internet connection.

## 2. Setup (XAMPP)

1. Copy the `subtrack/` folder into your XAMPP `htdocs/` directory, e.g.
   `C:\xampp\htdocs\subtrack`.
2. Start **Apache** and **MySQL** from the XAMPP control panel.
3. Open **phpMyAdmin** (`http://localhost/phpmyadmin`) and import, in order:
   - `database/schema.sql` — creates the `subtrack` database and its 5 tables
   - `database/seed.sql` — loads demo data (optional, but recommended for
     your first look / project demo)
4. If your MySQL root user has a password, update `config/config.php`:
   ```php
   define('DB_USER', 'root');
   define('DB_PASS', 'your_password_here');
   ```
5. Visit `http://localhost/subtrack/public/` in your browser.

### Configuration (.env)

Copy `.env.example` to `.env` and edit it; `.env` is git-ignored and never committed.

### Using Supabase (PostgreSQL) instead of MySQL

1. In the Supabase SQL editor run `database/schema.pgsql.sql`, then (optionally) `database/seed.pgsql.sql`.
2. In `.env` set `DB_DRIVER=pgsql` and the pooler host/port/user/password from
   Supabase → Project Settings → Database (see the commented block in `.env.example`).
   The PHP `pdo_pgsql` extension must be enabled (uncomment `extension=pdo_pgsql` in `php.ini`).

### Demo login

If you imported `seed.sql`:

```
Email:    owner@subtrack.test
Password: password123
```

This account comes with ~40 demo customers, 4 plans, and ~6 months of
subscription/payment history, so every chart and metric has real numbers
to show right away. You can also just click **Register** and create your
own business account from scratch — every account only ever sees its own
data.

## 3. Project structure

```
subtrack/
├── config/
│   ├── config.php        # DB credentials & app constants (edit this)
│   └── database.php      # PDO connection (singleton)
├── database/
│   ├── schema.sql         # table definitions
│   └── seed.sql           # demo data
├── src/
│   ├── Models/             # plain data objects (User, Customer, Plan, Subscription, Payment)
│   ├── Repositories/       # one interface + one PDO implementation per entity
│   └── Services/           # business rules: AuthService, SubscriptionService, RevenueCalculatorService
└── public/                 # the web root — every page here is a thin controller
    ├── index.php, about.php, contact.php   # public marketing pages
    ├── login.php, register.php, logout.php
    ├── dashboard.php
    ├── customers.php / customer_form.php
    ├── plans.php / plan_form.php
    ├── subscriptions.php / subscription_form.php
    ├── revenue.php, renewals.php, reports.php
    ├── bootstrap.php       # wires repositories → services (composition root)
    ├── assets/js/chart.umd.min.js   # vendored Chart.js (no CDN dependency)
    └── partials/           # shared header/nav/footer for both the public
                             # site and the logged-in app
```

### How SOLID principles are applied

- **Single Responsibility** — Models only hold data; Repositories only talk
  SQL for one table; Services only hold business rules (e.g.
  `RevenueCalculatorService` only computes metrics, `SubscriptionService`
  only enforces subscription lifecycle rules); pages only wire things
  together and render HTML.
- **Open/Closed** — new revenue metrics can be added to
  `RevenueCalculatorService` without changing any repository or page. New
  plan types or billing cycles need no changes to the persistence layer.
- **Liskov Substitution** — any class implementing e.g.
  `CustomerRepositoryInterface` can replace `PdoCustomerRepository`
  anywhere it's used, because callers only depend on the interface's
  contract.
- **Interface Segregation** — each repository interface only exposes the
  operations that entity actually needs (`PaymentRepositoryInterface` has
  no `delete()`, because nothing in the app deletes a payment directly),
  rather than one giant generic `RepositoryInterface`.
- **Dependency Inversion** — Services depend on repository *interfaces*,
  not concrete PDO classes; `public/bootstrap.php` is the one place that
  wires concrete implementations in. Swapping storage engines, or writing
  unit tests with fake repositories, needs no changes to `src/Services/`.

## 4. Database schema

| Table | Purpose |
|---|---|
| `users` | one row per business owner / admin account |
| `customers` | each business's end-customers (subscribers) |
| `plans` | subscription plans a business offers (name, price, billing cycle) |
| `subscriptions` | links a customer to a plan, tracks start/renewal dates and status |
| `payments` | every recurring charge collected for a subscription |

All business tables are scoped by `user_id`, so each registered business
account only ever sees its own customers, plans, subscriptions and revenue.

## 5. How the revenue metrics are calculated

- **MRR** (Monthly Recurring Revenue) — sum of every *active* subscription's
  price, with annual plans normalized to a monthly figure (`price ÷ 12`).
- **ARR** (Annual Recurring Revenue) — `MRR × 12`.
- **ARPU** (Average Revenue Per User) — `MRR ÷ active subscribers`.
- **Churn rate** — cancellations in the trailing 30 days ÷ (active
  subscribers + those cancellations).
- **Renewal rate** — `100% − churn rate`.

These all live in `src/Services/RevenueCalculatorService.php`, so the
Dashboard, Revenue Analytics and Reports pages are guaranteed to agree.

## 6. Key features by page

- **Dashboard** — total revenue, MRR/ARR, active subscribers, cancellations,
  a 6-month revenue trend chart, and a plan-popularity breakdown.
- **Customers** — add / edit / delete customers.
- **Subscription Plans** — add / edit / delete plans (monthly or annual
  billing); a plan in use by a subscription can't be deleted until it's
  freed up, to protect data integrity.
- **Subscriptions** — create a subscription (the renewal date and first
  payment are calculated and logged automatically), renew it (extends the
  renewal date by one billing cycle and logs a new payment), or cancel it.
- **Revenue Analytics** — MRR, ARR, ARPU, churn/renewal rate, a 12-month
  revenue chart, and a plan-mix donut chart.
- **Renewals** — subscriptions overdue for renewal, and those due in the
  next 14 days, with one-click renew/cancel.
- **Reports** — pick any month and see its revenue, new subscriptions,
  cancellations, and active-subscriber count as of that month.

## 7. Security notes

- Passwords are hashed with `password_hash()` (bcrypt).
- All SQL uses parameterized PDO queries (no string-concatenated SQL).
- Every state-changing form is protected by a CSRF token.
- All data access is scoped to the logged-in user's `user_id`.

This is a college/demo-grade project: for a production deployment you'd
also add rate limiting on login, HTTPS enforcement, and environment-based
secrets instead of `config/config.php`.

## 8. Colour palette

The whole site and dashboard use one warm "Sunset" palette (coral-orange accent on
white). Every colour is a CSS variable in the `:root` block at the top of
`public/assets/css/style.css`, so the palette can be adjusted in one place, and the
charts read their colours from the same variables (`public/assets/js/chart-colors.js`).

## 9. Assets & credits

Everything is bundled locally, so the site works without an internet connection:

- **Font:** Inter (SIL Open Font License) — `public/assets/fonts/`, via the
  `@fontsource/inter` package.
- **Charts:** Chart.js 4.4.1 (MIT) — `public/assets/js/chart.umd.min.js`.
- **Photos:** `public/assets/img/photos/` — taken from Creative Tim's MIT-licensed
  *Soft UI Design System* and *Material Kit* templates (the photos originally come
  from Unsplash and are free to use). License files are in the same folder.
- **Product screenshots:** `public/assets/img/screens/` are real screenshots of
  this app with the demo data, used on the home page.
