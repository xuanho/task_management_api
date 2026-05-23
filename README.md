# 🚀 Task Management API

Task Management API built with Laravel following a layered architecture (Controller → Service → Repository). It supports asynchronous processing via queues and records email logs when tasks are created or updated.

## **Overview**

- **Purpose:** Demonstrate a backend API for managing tasks with lifecycle management, status history, email notifications on create/update, and background processing for heavy jobs.
- **Requirements:** Laravel 13+, PHP 8.3+, Laravel Sanctum for authentication, Redis/Queue (Horizon) for background job handling.

## **Tech Stack**

- **Language & framework:** PHP, Laravel
- **Auth:** Laravel Sanctum
- **Queue:** Laravel Queue + Horizon (Redis/Predis)
- **DB:** MySQL (or SQLite for test/dev)
- **Optional:** Node/npm for assets

## **Key Features**

- **CRUD Tasks:** create, read, update, delete tasks
- **Task lifecycle:** statuses managed by `TaskStatus` and changes recorded in `TaskHistory`
- **Email logs:** create `EmailLog` entries on task creation/update and dispatch jobs to send emails
- **Queue processing:** email sending is pushed to the `emails` queue using `SendTaskCreatedEmailJob` and `SendTaskUpdatedEmailJob`
- **Business rules:** per-user task limit and unique-title validation

## **Installation & Run**

1. Clone repository and open the project folder:
    - git clone <repo>
    - cd task_api
2. Install PHP dependencies:
    - composer install
3. Copy environment file and generate app key:
    - cp .env.example .env
    - php artisan key:generate
4. Configure the database in `.env` and run migrations:
    - php artisan migrate
5. (Optional) Install npm packages and build assets:
    - npm install
    - npm run build
6. Start queue worker / Horizon:
    - php artisan queue:work --queue=emails --tries=3
    - Or run Horizon if configured: php artisan horizon
7. Start the application server:
    - php artisan serve

## **Important Environment Variables**

- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`
- Redis/Queue config for Horizon/Redis: `REDIS_HOST`, `REDIS_PASSWORD`, `REDIS_PORT`

## **API Endpoints (summary)**

- Base path: `/api/v1`
- **Auth** (`/api/v1/auth`):
    - `POST /login` — authenticate and return token
    - `POST /register` — create a new user
    - `POST /logout` — revoke token / logout
- **Task** (`/api/v1/task`) — protected by Sanctum:
    - `GET /` — list tasks (paginated)
    - `POST /` — create a task
    - `GET /{id}` — get task details
    - `PATCH /{id}` — update task
    - `DELETE /{id}` — delete task

Use header `Authorization: Bearer <token>` (Sanctum)

## **Business Flows**

- On task creation: `TaskCommandService::create()` checks limits and uniqueness, creates the task and fires `TaskCreated` event.
- Listener `CreateEmailLog` creates an `EmailLog` (status = pending) and dispatches a job using `TaskQueue::sendTaskCreatedEmail()`.
- On task update: `TaskHistory` is recorded, `TaskUpdated` event is fired, an `EmailLog` is created and an email job is dispatched.

## **Notable files / components**

- [app/Http/Controllers/Api/AuthController.php](app/Http/Controllers/Api/AuthController.php) — authentication
- [app/Http/Controllers/Api/v1/Task/TaskController.php](app/Http/Controllers/Api/v1/Task/TaskController.php) — task endpoints
- [app/Services/Task/TaskCommandService.php](app/Services/Task/TaskCommandService.php) — create/update/delete business logic
- [app/Services/Task/TaskQueryService.php](app/Services/Task/TaskQueryService.php) — read/query logic
- [app/Repositories/Task/TaskRepository.php](app/Repositories/Task/TaskRepository.php) — DB access for Task
- [app/Events/TaskCreated.php](app/Events/TaskCreated.php), [app/Events/TaskUpdated.php](app/Events/TaskUpdated.php) — events
- [app/Listeners/CreateEmailLog.php](app/Listeners/CreateEmailLog.php), [app/Listeners/UpdateEmailLogListener.php](app/Listeners/UpdateEmailLogListener.php) — listeners that create email logs and dispatch queue jobs
- [app/Jobs/Task/Email/SendTaskCreatedEmailJob.php](app/Jobs/Task/Email/SendTaskCreatedEmailJob.php) — queued job to send email
- [app/Infrastructure/MailService.php](app/Infrastructure/MailService.php) — mail wrapper using Mailables in `app/Mail`

## **Database & Factories**

- Migrations are in `database/migrations` (tasks, users, task_statuses, task_histories, email_logs, etc.)
- Factories for test/seeding are in `database/factories`

## **Testing**

- Run tests: `composer test` or `php artisan test`.
- The repository contains `tests/` with unit tests for listeners and services.

## **Operational Tips**

- Use Horizon to monitor queues and retry policies (configured in `config/horizon.php`).
- Ensure a worker is running for the `emails` queue to process email sending jobs.
