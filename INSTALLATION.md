# JB Tech Nepal - Installation Guide

## Prerequisites

- **PHP** 8.1 or higher (with extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML)
- **Composer** 2.x
- **Node.js** 18+ and npm (for frontend assets)
- **MySQL** 5.7+ or MariaDB 10.3+
- **Redis** (optional, for cache and queues)

## Installation Steps

### 1. Clone or Download Project

```bash
cd c:\xampp\htdocs\jbtechwebsite
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit `.env` with your settings:

```env
APP_NAME="JB Tech Nepal"
APP_URL=http://localhost/jbtechwebsite

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jbtechwebsite
DB_USERNAME=root
DB_PASSWORD=

# For subdomain tenancy (production)
# APP_DOMAIN=jbtechnepal.com
```

### 4. Create Database

```sql
CREATE DATABASE jbtechwebsite CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Run Migrations

```bash
php artisan migrate --force
```

### 6. Seed Database

```bash
php artisan db:seed --force
```

This creates:
- 3 subscription plans (Starter, Professional, Enterprise)
- Super Admin user: **admin@jbtechnepal.com** / **password**

### 7. Build Frontend Assets (Optional)

```bash
npm install
npm run build
```

### 8. Storage & Permissions

```bash
php artisan storage:link
# On Linux: chmod -R 775 storage bootstrap/cache
```

### 9. Start Development Server

```bash
php artisan serve
```

Access: **http://localhost:8000**

## Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@jbtechnepal.com | password |
| Tenant Admin | (created when you add a tenant) | (set during tenant creation) |

## Super Admin Panel

- URL: `http://localhost:8000/admin`
- Login with Super Admin credentials
- Create tenants, manage plans, view subscriptions and invoices

## Tenant Admin Panel

- URL: `http://localhost:8000/tenant`
- Login with tenant user credentials (created by Super Admin)
- Manage website settings, pages, and content

## Subdomain Configuration (Production)

For subdomain-based tenancy (e.g., `client1.jbtechnepal.com`):

1. Configure wildcard DNS: `*.jbtechnepal.com` → your server IP
2. Configure web server (Apache/Nginx) for wildcard vhost
3. Set `APP_DOMAIN=jbtechnepal.com` in `.env`

## XAMPP Quick Setup

1. Place project in `c:\xampp\htdocs\jbtechwebsite`
2. Create database `jbtechwebsite` in phpMyAdmin
3. Run: `php artisan migrate --force && php artisan db:seed --force`
4. Access: `http://localhost/jbtechwebsite/public`

## Troubleshooting

- **500 Error**: Check `storage/logs/laravel.log`, ensure storage is writable
- **CSRF Token Mismatch**: Clear cache: `php artisan config:clear`
- **Class not found**: Run `composer dump-autoload`
