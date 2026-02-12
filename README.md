# GMS Backend

Backend API for Event Management System built with Laravel 12.

## Tech Stack

- **Framework**: Laravel 12
- **Database**: PostgreSQL (Neon Cloud)
- **Authentication**: Laravel Sanctum (Token-based)
- **API Documentation**: Scramble (Auto-generated)
- **PHP Version**: 8.2+

## Struktur Folder

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── ConfigController.php
│   │   │   │   ├── EventController.php
│   │   │   │   └── PromoController.php
│   │   │   └── Auth/
│   │   │       └── AuthController.php
│   │   └── Requests/
│   │       ├── StoreEventRequest.php
│   │       └── UpdateEventRequest.php
│   │
│   ├── Models/
│   │   ├── BaseModel.php
│   │   ├── Config.php
│   │   ├── Event.php
│   │   └── User.php
│   │
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   │
│   ├── Rules/
│   │   └── NoLocationConflict.php
│   │
│   ├── Services/
│   │   └── PromoService.php
│   │
│   └── Traits/
│       └── ApiResponse.php
│
├── config/
│   ├── cors.php
│   └── sanctum.php
│
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── ConfigSeeder.php
│       └── EventSeeder.php
│
├── routes/
│   ├── api.php
│   └── web.php
│
└── public/
    └── .htaccess
```

## Setup & Installation

1. Install dependencies:
```bash
composer install
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Generate application key:
```bash
php artisan key:generate
```

4. Configure database di `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=gms_db
DB_USERNAME=postgres
DB_PASSWORD=

FRONTEND_URL=http://localhost:3000
```

5. Run migrations dan seeders:
```bash
php artisan migrate --seed
```

6. Start development server:
```bash
php artisan serve
```

API berjalan di [http://localhost:8000](http://localhost:8000)

## API Documentation

Auto-generated docs tersedia di:
- **Scramble**: http://localhost:8000/docs/api

## API Endpoints

### Authentication
```
POST   /api/login          # Login (public)
POST   /api/logout         # Logout (protected)
GET    /api/user           # Get authenticated user (protected)
```

### Events
```
GET    /api/events                    # List events dengan pagination (protected)
POST   /api/events                    # Create event (protected)
GET    /api/events/{id}               # Get single event (public)
PUT    /api/events/{id}               # Update event (protected)
DELETE /api/events/{id}               # Delete event (protected)
GET    /api/events/today              # Event hari ini (public)
GET    /api/events/floor-availability # Cek ketersediaan lantai (public)
```

### Configurations
```
GET    /api/configs        # List configs dengan pagination (protected)
GET    /api/configs/active # List configs aktif untuk dropdown (protected)
POST   /api/configs        # Create config (protected)
GET    /api/configs/{id}   # Get single config (protected)
PUT    /api/configs/{id}   # Update config (protected)
DELETE /api/configs/{id}   # Delete config (protected)
```

### Promotions
```
GET    /api/promos         # Get promo images dari external API (public)
```

## Database Models

### User
- Fields: `name`, `email`, `password`
- Auth via Sanctum token

### Config
- Fields: `group_code` (Location/Floor), `parent_id`, `value`, `descr`, `is_active`
- Hierarkis: Location → Floor (via `parent_id`)
- Soft deletes

### Event
- Fields: `title`, `location_id`, `floor_id`, `event_start_datetime`, `event_end_datetime`, `description`
- Relasi ke `Config` untuk lokasi dan lantai
- Soft deletes, audit trail (`created_by`, `updated_by`)

## Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_URL` | URL backend | `http://localhost:8000` |
| `FRONTEND_URL` | URL frontend untuk CORS (koma-separated) | `http://localhost:3000` |
| `DB_CONNECTION` | Driver database | `pgsql` |
| `DB_HOST` | Host database | `127.0.0.1` |

## Artisan Commands

```bash
php artisan serve              # Start dev server
php artisan migrate            # Run migrations
php artisan migrate:fresh --seed  # Fresh migration + seed
php artisan db:seed            # Run semua seeders
php artisan db:seed --class=EventSeeder  # Run seeder tertentu
php artisan config:clear       # Clear config cache
php artisan route:list         # List semua routes
```
