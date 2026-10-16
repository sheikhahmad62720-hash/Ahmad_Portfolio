# Muhammad Ahmad - Portfolio Platform

A full-stack portfolio and blog platform built with **Laravel 13** and **Vue.js 3 (TypeScript)**, featuring an admin dashboard, analytics, AI chatbot, and responsive UI.

Built by **[Muhammad Ahmad](https://github.com/sheikhahmad62720)** — Vue.js & Laravel Developer at NBTHUB, Pakistan.

## Features

- **Portfolio:** Projects, skills, experience, education, and certificates.
- **Blog:** Categories, tags, and rich content management.
- **Admin Dashboard:** Analytics and complete content management (CRUD).
- **AI Chatbot:** Integration with OpenAI, Gemini, and Claude.
- **Additional Features:** Contact form, newsletter, media library, role-based access, and dark/light theme.

## Tech Stack

- **Backend:** Laravel 13, PHP 8.3+, Laravel Sanctum
- **Frontend:** Vue.js 3, TypeScript, Pinia, Vue Router, Tailwind CSS
- **Tools:** Vite, Composer, NPM, Git
- **Database:** SQLite (MySQL ready)

## Requirements

- PHP 8.3+
- Composer 2.x
- Node.js 20+
- NPM 10+

## Quick Start

```bash
git clone <repo-url>
cd Ahmad_Portfolio
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Create the SQLite database file at `database/database.sqlite`, then run:

```bash
php artisan migrate --seed
npm run build
php artisan serve
```

Open **http://localhost:8000** in your browser.

### Quick Commands

```bash
composer setup  # Install and configure the project
composer dev    # Run development servers
composer test   # Run tests
```

## Documentation

See the `docs/` directory for architecture, database schema, and technical requirements.

## License

Proprietary — © 2026 Muhammad Ahmad. All rights reserved.
