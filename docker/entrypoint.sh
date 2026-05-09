#!/bin/bash
set -e

# Copy .env if it doesn't exist
if [ ! -f /var/www/.env ]; then
    cp /var/www/.env.example /var/www/.env
fi

# Generate app key if not set
php /var/www/artisan key:generate --no-interaction --force 2>/dev/null || true

# Wait for MySQL and run migrations
php /var/www/artisan migrate --force --no-interaction 2>/dev/null || true

# Build frontend assets into shared volume so Nginx can serve them
cd /var/www && npm run build

exec "$@"
