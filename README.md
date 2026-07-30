# Muhammad Ahmad - Portfolio Platform

Full-stack portfolio and blog platform built with **Laravel 13** and **Vue.js 3 (Inertia)**, featuring a modern admin dashboard with analytics, AI chatbot integration, and responsive design.

## Features

- Portfolio showcase with projects and skills management
- Blog system with categories, tags, and rich content
- Admin dashboard with analytics (visitors, page views, geography, devices, browsers)
- AI-powered chatbot (OpenAI/Gemini/Claude integration)
- Contact form with message management
- Newsletter subscription
- Authentication via Laravel Sanctum
- Responsive design with dark/light theme
- Social media integration

## Tech Stack

**Backend:** Laravel 13 | PHP 8.3+ | SQLite | Laravel Sanctum | Inertia  
**Frontend:** Vue.js 3 | TypeScript | Vite | Pinia | Tailwind CSS | Chart.js | Heroicons

## Quick Start

```bash
# Clone
git clone <repo-url>
cd Ahmad_Portfolio

# Install dependencies
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate

# Database (SQLite)
touch database/database.sqlite
php artisan migrate --seed

# Start
php artisan serve
npm run dev
```

**Default Admin:** admin@portfolio.com / password

## Database Seeders

Run with: `php artisan migrate --seed`

### RoleSeeder
Creates 3 roles:
- **Admin** — Full system access
- **Client** — Client portal access for project management
- **Guest** — Limited public access

### UserSeeder
Creates the default admin user:
- **Name:** Muhammad Ahmad
- **Email:** sheikhahmad62720@gmail.com
- **Password:** ahmad@720
- **Bio:** Vue.js & Laravel Developer at NBTHUB

### TechnologySeeder
Seeds 27 technologies across categories: Backend (Laravel, PHP, Node.js, Express.js), Frontend (Vue.js, React, JavaScript, TypeScript, HTML5, CSS3), CSS Frameworks (Tailwind CSS, Bootstrap), Database (MySQL, PostgreSQL, MongoDB, Redis), Tools (Git, GitHub, Docker, Vite, Webpack), APIs (REST API, GraphQL), State Management (Pinia, Vuex), and more.

### SettingSeeder
Seeds default settings in 5 groups:
- **general** — site name, description, tagline
- **contact** — email, phone, address
- **social** — GitHub, LinkedIn, Facebook, Instagram, Twitter URLs
- **system** — maintenance mode, items per page
- **ai** — chatbot enabled toggle
- **seo** — meta title, description, keywords

## Project Structure

- `app/Http/Controllers/API/` - API Controllers
- `app/Models/` - Eloquent Models
- `app/Http/Middleware/` - Custom Middleware (analytics tracking, auth)
- `resources/js/views/` - Vue.js Components
- `resources/js/views/admin/` - Admin Panel Views
- `resources/js/views/public/` - Public Portfolio Views
- `routes/api.php` - API Routes
- `routes/web.php` - Inertia SPA Routes

## Contact

**Muhammad Ahmad** - Full Stack Developer  
Haroonabad, Pakistan

## License

Proprietary - Muhammad Ahmad &copy; 2026
