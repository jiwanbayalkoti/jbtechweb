# JB Tech Nepal - Architecture Documentation

## Overview

JB Tech Nepal is an enterprise-level SaaS multi-tenant platform for website and content management services.

## Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 10 |
| Frontend | Blade, Bootstrap 4 (AdminLTE 3), jQuery |
| Database | MySQL |
| Auth | Laravel Breeze + Fortify |
| Roles | Spatie Laravel Permission |
| Admin UI | AdminLTE 3 |

## Multi-Tenant Architecture

### Approach: Shared Database, Tenant-Scoped Tables

- All tenant data in single database
- Every tenant table has `tenant_id` foreign key
- Tenant isolation via middleware and model scopes

### Tenant Resolution

- **Subdomain**: `client1.jbtechnepal.com` → Tenant slug: `client1`
- **Custom Domain**: Stored in `tenant_domains` table
- **IdentifyTenant** middleware resolves tenant from host

### User Types

1. **Super Admin** (`is_super_admin = true`, `tenant_id = null`)
   - Full system access
   - Manages tenants, plans, billing
   - Access: `/admin`

2. **Tenant Admin** (`tenant_id` set)
   - Manages their tenant's website
   - Access: `/tenant`

## Database Schema

### Core Tables

- `tenants` - Client organizations
- `tenant_domains` - Subdomain/custom domain mapping
- `users` - All users (super admin + tenant users)
- `plans` - Subscription plans
- `subscriptions` - Tenant plan assignments
- `invoices` - Billing records
- `websites` - Tenant website configs
- `pages` - CMS pages
- `menus` / `menu_items` - Navigation
- `blogs`, `services`, `portfolios`, `testimonials`, `careers` - Content modules
- `media` - File uploads
- `settings` - Key-value settings
- `contact_submissions`, `newsletter_subscribers` - Lead capture

## Directory Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/     # Super Admin controllers
│   │   └── Tenant/    # Tenant Admin controllers
│   └── Middleware/    # IdentifyTenant, EnsureSuperAdmin, EnsureTenantUser
├── Models/            # Eloquent models (Tenant, Plan, User, etc.)
└── Services/
    └── TenantResolver.php

resources/views/
├── admin/             # Super Admin views
├── tenant/            # Tenant Admin views
├── layouts/           # admin.blade.php, tenant.blade.php
└── auth/              # Login, register
```

## Modal Forms & Confirm Dialogs

- **JBAdmin.confirm()** - Custom confirm modal
- **JBAdmin.alert()** - Custom alert modal
- Forms in modals: Tenants, Plans (Super Admin)

## Routes

| Prefix | Middleware | Purpose |
|--------|------------|---------|
| `/admin` | auth, super.admin | Super Admin panel |
| `/tenant` | auth, tenant.admin | Tenant Admin panel |
| `/login`, `/register` | guest | Authentication |
