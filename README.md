# Ominimo Blog

A simple blog application built with **Laravel 11**, **Inertia.js**, **React**, and **Tailwind CSS**.

## Features

- User registration and authentication (Laravel Breeze)
- Create, read, update, and delete blog posts
- Comments on posts — open to both authenticated users and guests
- Authorization via Laravel Policies (owners can edit/delete their own content)
- Admin role — can delete any post or comment
- Paginated post listing
- Dockerized for local development

---

## Requirements

- [Docker](https://www.docker.com/products/docker-desktop) and Docker Compose

That's it. PHP and Node.js run inside the containers.

---

## Local Setup

### 1. Clone the repository

```bash
git clone https://github.com/Rale27/Ominimo-blog.git
cd Ominimo-blog
```

### 2. Create the environment file

```bash
cp .env.example .env
```

The default `.env.example` is pre-configured for the Docker MySQL service. No changes needed for local development.

### 3. Start the containers

```bash
docker compose up -d --build
```

This will:
- Build the PHP image (installs Composer dependencies and builds frontend assets)
- Start PHP-FPM, Nginx, and MySQL
- Auto-generate the app key and run migrations on first start

### 4. Seed the database

```bash
docker compose exec app php artisan db:seed
```

### 5. Open the app

Visit [http://localhost:8080](http://localhost:8080)

---

## Seeded Accounts

| Role  | Email              | Password |
|-------|--------------------|----------|
| Admin | admin@example.com  | password |
| User  | alice@example.com  | password |
| User  | bob@example.com    | password |

---

## Running Tests

Tests use an in-memory SQLite database and do not touch the Docker MySQL instance.

```bash
docker compose exec app php artisan test
```

Run only a specific suite:

```bash
docker compose exec app php artisan test --testsuite=Feature
docker compose exec app php artisan test --testsuite=Unit
```

---

## Project Structure

```
app/
  Http/
    Controllers/
      PostController.php       # CRUD for posts
      CommentController.php    # Store / delete comments
      Auth/                    # Registration, login, logout
    Middleware/
      HandleInertiaRequests.php
    Requests/
      StorePostRequest.php
      UpdatePostRequest.php
  Models/
    User.php
    Post.php
    Comment.php
  Policies/
    PostPolicy.php             # update / delete authorization
    CommentPolicy.php          # delete authorization
  Providers/
    AppServiceProvider.php     # Policy registration

database/
  migrations/                  # Schema definitions
  factories/                   # Model factories for testing
  seeders/                     # Sample data

resources/js/
  Pages/
    Posts/Index.jsx            # Post listing with pagination
    Posts/Show.jsx             # Single post + comments
    Posts/Create.jsx           # Create post form
    Posts/Edit.jsx             # Edit post form
    Auth/Login.jsx
    Auth/Register.jsx
  Layouts/
    MainLayout.jsx             # Navigation, flash messages

tests/
  Feature/
    PostTest.php               # CRUD + authorization flow tests
    CommentTest.php            # Comment store / delete tests
    Auth/AuthenticationTest.php
  Unit/
    PostPolicyTest.php         # Direct policy unit tests

docker/
  nginx/default.conf
  entrypoint.sh
```

---

## Authorization Rules

| Action               | Who can do it                              |
|----------------------|--------------------------------------------|
| View posts/comments  | Everyone (including guests)                |
| Create post          | Authenticated users                        |
| Edit / Update post   | Post owner or Admin                        |
| Delete post          | Post owner or Admin                        |
| Add comment          | Everyone (including guests)                |
| Delete comment       | Comment owner, Post owner, or Admin        |

---

## Docker Services

| Service | Description            | Port         |
|---------|------------------------|--------------|
| app     | PHP 8.3-FPM            | internal     |
| nginx   | Web server             | 8080 → 80    |
| mysql   | MySQL 8.0              | 3306 → 3306  |

---

## Useful Commands

```bash
# Rebuild after code changes (usually not needed due to volume mounts)
docker compose up -d --build

# Run a fresh migration + seed
docker compose exec app php artisan migrate:fresh --seed

# Open a shell inside the container
docker compose exec app bash

# Stop all containers
docker compose down

# Stop and remove volumes (resets the database)
docker compose down -v
```
