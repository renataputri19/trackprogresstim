#!/usr/bin/env bash
#
# Container entrypoint.
#
#   First boot (fresh database):   import the seed SQL dump  ->  run migrations
#   Every boot after that:         skip the import           ->  run migrations
#
# "Fresh" is detected by the ABSENCE of the `migrations` table. The seed dump
# already contains the `migrations` table with its rows, so after a one-time
# import `php artisan migrate` treats existing migrations as applied and only
# runs migration files added later. Seeding never repeats.
#
set -euo pipefail

APP_DIR=/var/www/html
cd "$APP_DIR"

SEED_FILE="${SEED_FILE:-/var/www/seed/init.sql}"
DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-}"
DB_USERNAME="${DB_USERNAME:-root}"

log() { echo "[entrypoint] $*"; }

# ---------------------------------------------------------------------------
# 0. Sanity checks
# ---------------------------------------------------------------------------
if [ -z "${APP_KEY:-}" ]; then
    log "ERROR: APP_KEY is not set. Set it in the Dokploy environment"
    log "       (generate one locally with: php artisan key:generate --show)."
    exit 1
fi

if [ -z "$DB_DATABASE" ]; then
    log "ERROR: DB_DATABASE is not set."
    exit 1
fi

# mariadb client reads the password from MYSQL_PWD (keeps it out of process args)
export MYSQL_PWD="${DB_PASSWORD:-}"
MYSQL="mariadb -h${DB_HOST} -P${DB_PORT} -u${DB_USERNAME}"

# ---------------------------------------------------------------------------
# 1. Runtime dirs & permissions (covers volumes mounted over storage)
# ---------------------------------------------------------------------------
mkdir -p /run/nginx
mkdir -p \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache/data \
    storage/logs \
    storage/app/public \
    bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# ---------------------------------------------------------------------------
# 2. Wait for the database to accept connections
# ---------------------------------------------------------------------------
log "Waiting for database at ${DB_HOST}:${DB_PORT} ..."
tries=0
until $MYSQL -e "SELECT 1;" >/dev/null 2>&1; do
    tries=$((tries + 1))
    if [ "$tries" -ge 60 ]; then
        log "ERROR: database not reachable after 120s. Giving up."
        exit 1
    fi
    sleep 2
done
log "Database is up."

# ---------------------------------------------------------------------------
# 3. First-boot seed (only when the `migrations` table does not yet exist)
# ---------------------------------------------------------------------------
MIGRATIONS_TABLE=$(
    $MYSQL -N -s -e \
    "SELECT COUNT(*) FROM information_schema.tables \
     WHERE table_schema='${DB_DATABASE}' AND table_name='migrations';" \
    2>/dev/null || echo 0
)

if [ "${MIGRATIONS_TABLE:-0}" = "0" ]; then
    log "Fresh database detected (no 'migrations' table)."
    if [ -f "$SEED_FILE" ]; then
        log "Importing seed dump: $SEED_FILE"
        $MYSQL "$DB_DATABASE" < "$SEED_FILE"
        log "Seed import finished."
    else
        log "No seed file at $SEED_FILE — migrations will build the schema from scratch."
    fi
else
    log "Existing database detected — skipping seed import."
fi

# ---------------------------------------------------------------------------
# 4. Apply migrations (idempotent: only pending migration files run)
# ---------------------------------------------------------------------------
log "Running migrations ..."
php artisan migrate --force --no-interaction

# ---------------------------------------------------------------------------
# 5. Optimize (each cache step falls back to clear so a bad cache can't
#    stop the container from booting)
# ---------------------------------------------------------------------------
php artisan storage:link      --no-interaction 2>/dev/null || true
php artisan package:discover  --no-interaction 2>/dev/null || true
php artisan config:cache      --no-interaction || php artisan config:clear
php artisan route:cache       --no-interaction || php artisan route:clear
php artisan view:cache        --no-interaction || php artisan view:clear

log "Startup complete. Handing off to: $*"
exec "$@"
