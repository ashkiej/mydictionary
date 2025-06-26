# MyDictionary

A full-stack dictionary application built with Laravel and Vue.js.

## Features

-   🔐 User authentication with Laravel Sanctum
-   🔍 Word search using Free Dictionary API
-   ⭐ Personal favorites management
-   📝 Add personal notes to saved words
-   🧹 Automatic cleanup of old favorites
-   📱 Responsive design with TailwindCSS

## Tech Stack

**Backend:**

-   Laravel 10
-   SQLite Database
-   Laravel Sanctum (Authentication)
-   Free Dictionary API Integration

**Frontend:**

-   Vue.js 3
-   Vue Router
-   Pinia (State Management)
-   TailwindCSS
-   Axios

## Quick Start

### Prerequisites

-   PHP 8.1+
-   Composer
-   Node.js 16+
-   NPM or Yarn

### Backend Setup

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan serve

### Backend Setup
cd frontend
npm install
npm run dev

Backend Commands
bash# Run tests
php artisan test

# Run migrations
php artisan migrate

# Clear cache
php artisan cache:clear

# Run scheduler (for cleanup task)
php artisan schedule:work

Frontend Commands
bash# Development server
npm run dev

# Build for production
npm run build

# Run tests
npm run test

# Lint code
npm run lint
```
