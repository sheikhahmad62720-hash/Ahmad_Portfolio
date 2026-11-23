# Muhammad Ahmad | Full-Stack Portfolio Platform

A modern, feature-rich portfolio and blogging platform built with **Laravel 13**, **Vue.js 3**, and **TypeScript**. Designed to showcase professional projects, share technical articles, manage content, and provide an interactive experience through a powerful admin dashboard and AI-powered chatbot.

> Built with passion for clean code, modern web technologies, and scalable application development.

**Developer:** [Muhammad Ahmad](https://github.com/sheikhahmad62720)  
**Role:** Vue.js & Laravel Developer at NBTHUB, Pakistan

---

## 🚀 Features

### 🌐 Portfolio & Personal Branding
- Showcase personal projects and technical expertise.
- Display professional experience, education, certifications, and skills.
- Highlight projects with detailed descriptions and technology stacks.

### 📝 Blog & Content Management
- Create, edit, publish, and manage blog posts.
- Organize articles with categories and tags.
- Rich content editing for structured blog posts.

### 📊 Admin Dashboard
- Centralized dashboard for platform management.
- Analytics and insightful data visualization.
- Complete content management with CRUD operations.
- Manage portfolio, blog posts, contacts, and other content.

### 🤖 AI-Powered Chatbot
- Interactive AI chatbot integrated into the platform.
- Support for multiple AI providers:
  - OpenAI
  - Google Gemini
  - Anthropic Claude
- Designed to provide intelligent responses and improve visitor engagement.

### 📬 Contact & Communication
- Contact form for professional inquiries.
- Newsletter subscription functionality.
- Centralized contact management.

### 🗂️ Media Management
- Centralized media and file management.
- Organized assets for portfolio and blog content.

### 🔐 Authentication & Permissions
- Secure authentication with Laravel Sanctum.
- Role-based access control.
- Permission-based access to administrative features.

### 🎨 UI & Experience
- Dark and light theme support.
- Responsive layouts for desktop, tablet, and mobile.
- Modern and clean user interface.
- Smooth and user-friendly navigation.

---

## 🛠️ Tech Stack

| Technology | Purpose |
|---|---|
| PHP 8.3+ | Backend programming language |
| Laravel 13 | Backend framework |
| Laravel Sanctum | Authentication |
| Vue.js 3 | Frontend framework |
| TypeScript | Type-safe development |
| Pinia | State management |
| Vue Router | Frontend routing |
| Tailwind CSS | UI styling |
| SQLite | Database |
| MySQL | Alternative database support |
| Vite | Frontend build tooling |
| Composer | PHP dependency management |
| NPM | JavaScript dependency management |
| Git | Version control |

---

## 📁 Project Structure

```text
Ahmad_Portfolio/
├── app/                 # Laravel application logic
├── bootstrap/            # Application bootstrap
├── config/               # Configuration files
├── database/              # Migrations, factories & seeders
├── docs/                 # Project documentation
├── public/               # Public assets
├── resources/
│   ├── js/              # Vue frontend
│   ├── css/             # Stylesheets
│   └── views/           # Blade templates
├── routes/                # Application routes
├── storage/               # Files and application storage
├── tests/                 # Automated tests
├── package.json            # NPM dependencies and scripts
└── composer.json           # Composer dependencies and scripts
```

---

## ⚙️ Requirements

Make sure the following tools are installed before starting:

- **PHP:** 8.3 or higher
- **Composer:** 2.x
- **Node.js:** 20 or higher
- **NPM:** 10 or higher

---

## 📥 Installation & Setup

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

Create your environment file and generate the application key:

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure the Database

This project uses SQLite by default.

Create the database file:

**Linux / macOS:**

```bash
touch database/database.sqlite
```

**Windows PowerShell:**

```powershell
New-Item database/database.sqlite -ItemType File
```

Make sure your `.env` database configuration is set to SQLite.

### 5. Run Database Migrations

```bash
php artisan migrate --seed
```

This will create the required database tables and populate initial data using the available seeders.

### 6. Build Frontend Assets

```bash
npm run build
```

### 7. Start the Application

```bash
php artisan serve
```

Open the application in your browser:

**http://localhost:8000**

---

## 💻 Development Commands

| Command | Description |
|---|---|
| `composer setup` | Install dependencies and configure the application |
| `composer dev` | Start the development environment |
| `composer test` | Execute the test suite |
| `npm run dev` | Start the Vite development server |
| `npm run build` | Build frontend assets for production |
| `php artisan migrate` | Run database migrations |
| `php artisan migrate --seed` | Run migrations and seed initial data |
| `php artisan serve` | Start the Laravel development server |

---

## 📚 Documentation

Additional technical documentation, architecture details, database information, and project requirements are available in the `docs/` directory.

---

## 👨‍💻 About the Developer

I'm a Full-Stack Developer focused on building modern web applications using Laravel, Vue.js, PHP, TypeScript, and JavaScript.

I enjoy developing scalable backend systems, interactive frontend experiences, REST APIs, and practical solutions to real-world problems.

**GitHub:** [@sheikhahmad62720](https://github.com/sheikhahmad62720)

---

## 📄 License

**Proprietary License**

Copyright © 2026 Muhammad Ahmad. All rights reserved.

This project is proprietary software. Redistribution, modification, or commercial use is not permitted without explicit permission from the copyright holder.
