# GMS Backend

Backend API for Event Management System built with Laravel 11.

## Tech Stack

- **Framework**: Laravel 11
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Sanctum
- **API Documentation**: Scramble (Auto-generated)
- **PHP Version**: 8.2+

## Struktur Folder

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # HTTP Controllers
│   │   │   ├── AuthController.php
│   │   │   ├── ConfigController.php
│   │   │   └── EventController.php
│   │   └── Requests/             # Form requests
│   │
│   ├── Models/                   # Eloquent models
│   │   ├── BaseModel.php
│   │   ├── User.php
│   │   ├── Config.php
│   │   └── Event.php
│   │
│   ├── Rules/                    # Custom validation rules
│   │   └── NoLocationConflict.php
│   │
│   ├── Services/                 # Business logic services
│   │   └── PromoService.php
│   │
│   └── Traits/                   # Reusable traits
│       └── ApiResponse.php
│
├── config/                       # Configuration files
│   ├── cors.php                 # CORS settings
│   └── sanctum.php              # Sanctum auth settings
│
├── database/
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders
│
├── routes/
│   ├── api.php                  # API routes
│   └── web.php                  # Web routes
│
└── storage/                     # File storage
    ├── app/
    │   └── public/
    │       └── events/          # Event images and assets
    └── logs/                    # Application logs
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

4. Configure database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gms_db
DB_USERNAME=root
DB_PASSWORD=
```

5. Configure CORS in `.env`:
```env
FRONTEND_URL=http://localhost:3000
```

6. Run migrations:
```bash
php artisan migrate
```

7. Create storage symlink:
```bash
php artisan storage:link
```

8. Start development server:
```bash
php artisan serve
```

API will run at [http://localhost:8000](http://localhost:8000)

## API Documentation

Auto-generated API documentation available at:
- **Scramble Docs**: http://localhost:8000/docs/api

## API Endpoints

### Authentication
```
POST   /api/login          # Login user
POST   /api/logout         # Logout user
GET    /api/user           # Get authenticated user
```

### Configurations
```
GET    /api/configs        # List all configs
POST   /api/configs        # Create config
GET    /api/configs/{id}   # Get single config
PUT    /api/configs/{id}   # Update config
DELETE /api/configs/{id}   # Delete config
```

### Events
```
GET    /api/events         # List all events
POST   /api/events         # Create event
GET    /api/events/{id}    # Get single event
PUT    /api/events/{id}    # Update event
DELETE /api/events/{id}    # Delete event
```

### Promotions
```
GET    /api/promos         # Get active promotional events
```

## Database Models

### User
- Authentication user model
- Fields: name, email, password

### Config
- System configuration model
- Fields: key, value, description, is_active

### Event
- Event information model
- Fields: title, description, location, start_date, end_date, image_url, opening_hours, is_active

## Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_URL` | Application URL | `http://localhost:8000` |
| `FRONTEND_URL` | Frontend URL for CORS | `http://localhost:3000` |
| `DB_*` | Database connection settings | - |
| `SANCTUM_STATEFUL_DOMAINS` | Sanctum stateful domains | `localhost:3000` |

## Testing

Run tests:
```bash
php artisan test
```

## Artisan Commands

```bash
php artisan serve              # Start dev server
php artisan migrate           # Run migrations
php artisan migrate:fresh     # Fresh migration
php artisan db:seed           # Run seeders
php artisan route:list        # List all routes
php artisan make:controller   # Create controller
php artisan make:model        # Create model
php artisan make:migration    # Create migration
```

## Notes

- All API routes are protected with Sanctum middleware except login and public promo endpoints
- CORS is configured for frontend access
- File uploads are stored in `storage/app/public/events`
- API uses JSON format for requests and responses
- Event validation includes location conflict checking
- Promotional service automatically filters active events
