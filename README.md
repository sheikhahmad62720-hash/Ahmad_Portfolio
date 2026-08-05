# Muhammad Ahmad - Portfolio Platform

A full-stack portfolio and blog platform built with **Laravel 13** and **Vue.js 3 (TypeScript)**, featuring a modern admin dashboard with analytics, AI chatbot integration, and a responsive design.

Built by **[Muhammad Ahmad](https://github.com/sheikhahmad62720)** — Vue.js & Laravel Developer at NBTHUB, Haroonabad, Pakistan.

---

## Features

### Public Website
- Modern, animated portfolio homepage (hero, typewriter, tech orbit, stats)
- About page with skills, experience, and education timeline
- Portfolio showcase with project filtering by category
- Blog system with categories, tags, and rich content
- Contact form with server-side validation
- Newsletter subscription
- Dark/light theme toggle
- Fully responsive design

### Admin Dashboard
- Real-time analytics: visitors, page views, geography, devices, browsers, top content
- Projects, Blogs, Skills, Services, Experience, Education, Certificates, Testimonials — full CRUD
- Message inbox with reply and bulk delete
- Media library with folders and file upload
- Settings management (general, contact, social, system, AI, SEO)
- Role-based access control (Admin / Client / Guest)

### AI & Automation
- AI-powered chatbot (OpenAI / Gemini / Claude integration)
- AI conversation history and training data management
- Configurable AI prompts

---

## Tech Stack

**Backend:** Laravel 13 | PHP 8.3+ | SQLite (MySQL ready) | Laravel Sanctum | Laravel Pint

**Frontend:** Vue.js 3 | TypeScript | Vite 8 | Pinia | Vue Router | Tailwind CSS | Chart.js | Swiper | Heroicons

**Dev Tools:** Composer | NPM | Git

---

## Requirements

- PHP 8.3 or higher
- Composer 2.x
- Node.js 20.x LTS or higher
- NPM 10.x or higher

---

## Quick Start

```bash
# 1. Clone the repository
git clone <repo-url>
cd Ahmad_Portfolio

# 2. Install PHP dependencies
composer install

# 3. Install frontend dependencies
npm install

# 4. Setup environment
cp .env.example .env
php artisan key:generate

# 5. Setup database (SQLite)
touch database/database.sqlite
php artisan migrate --seed

# 6. Build frontend assets
npm run build

# 7. Start the server
php artisan serve

# 8. (Development) Start Vite dev server
npm run dev
```

Open **http://localhost:8000** in your browser.

> **Note:** This is an API-first SPA. The Laravel server only serves the Vue application shell at `/`; all data flows through the REST API at `/api/v1`.

---

## One-Command Setup

```bash
composer setup
```

Runs install, key generation, migration, and frontend build automatically.

### Run All Dev Servers

```bash
composer dev
```

Runs Laravel server, queue worker, logs (Pail), and Vite concurrently.

---

## Seeders

Run with `php artisan migrate --seed`:

- **RoleSeeder** — Admin, Client, Guest roles
- **UserSeeder** — Default admin user
- **TechnologySeeder** — 27 technologies across categories
- **SettingSeeder** — Default settings in general, contact, social, system, ai, seo groups

---

## Tests

```bash
composer test
```

---

## Documentation

See the `docs/` directory for deeper documentation:

- `docs/ARCHITECTURE.md` — System architecture
- `docs/DATABASE_SCHEMA.md` — Database schema details
- `docs/TECHNICAL_REQUIREMENTS.md` — Requirements specification

---

## Roadmap / Planned Features

- Client portal for project management
- Invoicing and billing
- Support tickets and meetings
- Advanced SEO settings
- Email templates

---

## License

Proprietary — © 2026 Muhammad Ahmad. All rights reserved.
