# Project Overview

This is a Laravel web application designed to manage various content types such as products, training, news, marketing campaigns, and a library. It features user authentication, a role-based permission system (likely powered by `spatie/laravel-permission`), and a dedicated administration section. The frontend utilizes Vite for asset compilation, Tailwind CSS for styling, and Alpine.js for interactivity. The application is set up for local development using Docker Compose.

**Key Technologies:**

*   **Backend:** Laravel (PHP 8.2+)
*   **Database:** MySQL (used with Docker Compose)
*   **Frontend:** Vite, Tailwind CSS, Alpine.js
*   **Other PHP Packages:** Livewire, Intervention Image, Maatwebsite Excel, Spatie Laravel Permission
*   **Containerization:** Docker, Docker Compose
*   **Testing:** PHPUnit

**Architecture Highlights:**

*   Standard Laravel MVC architecture with custom services and repositories.
*   UI visibility control via `UiVisibilityService`.
*   Automated actions/notifications triggered by model observers for `Product`, `News`, `Library`, and `Training` models.
*   Custom middleware for access control (`permission`, `admin_only`) and file serving (`private.files`).
*   Queue system for background job processing (implied by `composer.json` dev script).
*   OneDrive integration for file management (inferred from file names like `OneDriveSync.php`, `cron-onedrive-worker.sh`, `ONEDRIVE_SYNC_SYSTEM.md`).

## Building and Running

The project is designed to be run using Docker Compose for local development.

### Prerequisites

*   Docker Desktop (or Docker Engine and Docker Compose) installed.

### Development Setup

1.  **Environment Configuration:**
    *   Copy `.env.docker.example` to `.env`.
    *   Adjust the `.env` file, especially `DB_HOST=db`, `APP_URL`, and `VITE_DEV_SERVER_URL`.

2.  **Start Docker Containers:**
    ```bash
    docker compose up --build
    ```
    This command will:
    *   Build the `app` and `vite` Docker images (if not already built).
    *   Start the `app` service, which runs `composer install`, `php artisan migrate --force`, and `php artisan serve`.
    *   Start the `vite` service, which runs `npm install` and `npm run dev` for hot module replacement.
    *   Start the `db` service (MySQL 8.4).

3.  **Access the Application:**
    *   **Laravel Application:** `http://localhost:8000`
    *   **Vite HMR (Hot Module Replacement):** `http://localhost:5173`

### Running Tests

The project uses PHPUnit for testing.

To run tests:

```bash
docker compose exec app php artisan test
```

Alternatively, the `composer.json` script provides a direct way to run tests:

```bash
docker compose exec app composer test
```

### Other Useful Commands

*   **Development Script (from `composer.json`):**
    This script runs the Laravel development server, queue listener, logs tail, and Vite development server concurrently.
    ```bash
    docker compose exec app composer dev
    ```

## Development Conventions

*   **Coding Style:** PHP code likely adheres to PSR standards, and `laravel/pint` is used for code style fixing (as seen in `require-dev` in `composer.json`).
*   **Frontend Assets:** Managed with Vite, Tailwind CSS, and Alpine.js. Assets are located in the `resources/` directory.
*   **Configuration:** Relies heavily on environment variables (`.env` file) for sensitive data and application settings.
*   **Testing:** Unit and Feature tests are located in `tests/Unit` and `tests/Feature` respectively. Tests use an in-memory SQLite database for isolation.
*   **Middleware:** Custom middleware (`ForceHttps`, `UpdateLastAccess`, `ServePrivateFiles`, `CheckPermission`, `AdminOnly`) is used for various cross-cutting concerns.
*   **Services and Repositories:** The `app/Services` and `app/Repositories` directories indicate a structured approach to business logic and data access.
*   **UI Visibility:** A dedicated `UiVisibilityService` and `@canSee` Blade directive are in place to control the visibility of UI elements.
