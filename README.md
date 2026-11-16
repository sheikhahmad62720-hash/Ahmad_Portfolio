# Muhammad Ahmad — Portfolio Platform

A modern full-stack portfolio and blog platform built with **Laravel 13** and **Vue.js 3 (TypeScript)**. It showcases professional work, manages blog content, and provides an admin dashboard with analytics, AI-powered chatbot integration, and a responsive user interface.

**Built by [Muhammad Ahmad](https://github.com/sheikhahmad62720)** — Vue.js & Laravel Developer at NBTHUB, Pakistan.

## Features

- **Portfolio:** Showcase projects, technical skills, professional experience, education, and certificates.
- **Blog Management:** Create and manage blog posts with categories, tags, and rich content editing.
- **Admin Dashboard:** Analytics, dashboard insights, and complete content management (CRUD).
- **AI Chatbot:** Integrated AI chatbot supporting OpenAI, Google Gemini, and Anthropic Claude.
- **Contact Management:** Contact form and newsletter subscription functionality.
- **Media Library:** Centralized media and file management.
- **Role-Based Access Control:** Manage user permissions and access.
- **Theme Customization:** Dark and light mode support.
- **Responsive Design:** Optimized experience across desktop, tablet, and mobile devices.

## Tech Stack

| Category | Technologies |
|---|---|
| Backend | Laravel 13, PHP 8.3+, Laravel Sanctum |
| Frontend | Vue.js 3, TypeScript |
| State Management | Pinia |
| Routing | Vue Router |
| Styling | Tailwind CSS |
| Database | SQLite (MySQL-ready) |
| Build Tool | Vite |
| Package Managers | Composer, NPM |
| Version Control | Git |

## Requirements

- PHP 8.3 or higher
- Composer 2.x
- Node.js 20 or higher
- NPM 10 or higher

## Installation & Setup

### 1. Clone the Repository

```bash
git clone <repo-url>
cd Ahmad_Portfolio
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure the Database

Create an SQLite database file:

```bash
touch database/database.sqlite
```

On Windows PowerShell, use:

```powershell
New-Item database/database.sqlite -ItemType File
```

### 5. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

### 6. Build Frontend Assets

```bash
npm run build
```

### 7. Start the Development Server

```bash
php artisan serve
```

Open your browser and visit:

[http://localhost:8000](http://localhost:8000)

## Quick Commands

| Command | Description |
|---|---|
| `composer setup` | Install dependencies and configure the project |
| `composer dev` | Start development servers |
| `composer test` | Run the test suite |
| `npm run dev` | Start the Vite development server |
| `npm run build` | Build production assets |
| `php artisan migrate --seed` | Run migrations and seed the database |

## Documentation

For detailed technical information, architecture, database schema, and project requirements, refer to the `docs/` directory.

## License

**Proprietary License** — © 2026 Muhammad Ahmad. All rights reserved.

This project is not licensed for redistribution, modification, or commercial use without explicit permission from the copyright holder.
