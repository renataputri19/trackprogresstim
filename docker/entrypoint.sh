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

SEED_FILE="${SEED_FILE:-}"   # explicit override; otherwise auto-resolved below
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

# Find the seed dump: an explicit SEED_FILE, else a mounted file, else the
# dump bundled in the image at database/seed/. Supports plain .sql and .sql.gz.
resolve_seed_file() {
    for f in \
        "$SEED_FILE" \
        /var/www/seed/init.sql \
        /var/www/seed/init.sql.gz \
        "${APP_DIR}/database/seed/init.sql" \
        "${APP_DIR}/database/seed/init.sql.gz"; do
        if [ -n "$f" ] && [ -f "$f" ]; then
            echo "$f"
            return 0
        fi
    done
    return 1
}

# Stream a dump into the database, decompressing on the fly when gzipped.
import_dump() {
    dump="$1"
    case "$dump" in
        *.gz) gunzip -c "$dump" | $MYSQL "$DB_DATABASE" ;;
        *)    $MYSQL "$DB_DATABASE" < "$dump" ;;
    esac
}

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

# nginx workers run as www-data, so its temp dirs must be writable by www-data.
# Without this, buffering a request body (e.g. a file upload) fails with
# "client_body ... Permission denied" and returns 500.
mkdir -p \
    /var/lib/nginx/tmp/client_body \
    /var/lib/nginx/tmp/proxy \
    /var/lib/nginx/tmp/fastcgi \
    /var/lib/nginx/tmp/uwsgi \
    /var/lib/nginx/tmp/scgi
chown -R www-data:www-data /var/lib/nginx /run/nginx

# ---------------------------------------------------------------------------
# 2. Wait for the database to accept connections
# ---------------------------------------------------------------------------
log "Waiting for database at ${DB_HOST}:${DB_PORT} ..."
DB_WAIT_TRIES="${DB_WAIT_TRIES:-90}"   # 90 * 2s = 180s
tries=0
until $MYSQL -e "SELECT 1;" >/dev/null 2>&1; do
    tries=$((tries + 1))
    if [ "$tries" -ge "$DB_WAIT_TRIES" ]; then
        waited=$((DB_WAIT_TRIES * 2))
        log "ERROR: database not reachable after ${waited}s. Real client error:"
        $MYSQL -e "SELECT 1;" 2>&1 | sed 's/^/[entrypoint]   /' || true
        log "Hints:"
        log "  - 'Unknown MySQL server host'  -> DB_HOST is wrong or app & DB are"
        log "    not on the same network. Use the DB's Internal Host from Dokploy."
        log "  - 'Connection refused'         -> the MariaDB service is not running"
        log "    (or is still starting). Check the database service in Dokploy."
        log "  - 'Access denied'              -> DB_USERNAME / DB_PASSWORD is wrong."
        log "  Current: DB_HOST=${DB_HOST} DB_PORT=${DB_PORT} DB_USERNAME=${DB_USERNAME} DB_DATABASE=${DB_DATABASE}"
        exit 1
    fi
    sleep 2
done
log "Database is up."

# ---------------------------------------------------------------------------
# 3. Seed the database.
#
#   Auto:  import only when the `migrations` table does not yet exist (a truly
#          fresh DB). This never touches a database that already has data.
#   Force: set FORCE_SEED=true to re-import on purpose. It DROPS every table
#          first for a clean slate, so it is DESTRUCTIVE — use it only for a
#          dev reset or to recover a half-initialized database (e.g. one where
#          migrations ran before the seed file was mounted). Remove the var
#          again after the deploy so you don't wipe data on the next redeploy.
# ---------------------------------------------------------------------------
FORCE_SEED="${FORCE_SEED:-false}"

MIGRATIONS_TABLE=$(
    $MYSQL -N -s -e \
    "SELECT COUNT(*) FROM information_schema.tables \
     WHERE table_schema='${DB_DATABASE}' AND table_name='migrations';" \
    2>/dev/null || echo 0
)

DO_SEED=false
if [ "$FORCE_SEED" = "true" ]; then
    log "FORCE_SEED=true — forcing a clean re-import (this DROPS existing tables)."
    DO_SEED=true
elif [ "${MIGRATIONS_TABLE:-0}" = "0" ]; then
    log "Fresh database detected (no 'migrations' table)."
    DO_SEED=true
else
    log "Existing database detected — skipping seed import (set FORCE_SEED=true to re-import)."
fi

if [ "$DO_SEED" = "true" ]; then
    if SEED_RESOLVED=$(resolve_seed_file); then
        # Clean slate: drop any existing tables so the import is deterministic
        # (covers a half-migrated DB with leftover tables not in the dump).
        EXISTING_TABLES=$(
            $MYSQL -N -s -e \
            "SELECT table_name FROM information_schema.tables \
             WHERE table_schema='${DB_DATABASE}';" 2>/dev/null || true
        )
        if [ -n "$EXISTING_TABLES" ]; then
            log "Dropping existing tables for a clean seed ..."
            {
                echo "SET FOREIGN_KEY_CHECKS=0;"
                for t in $EXISTING_TABLES; do echo "DROP TABLE IF EXISTS \`$t\`;"; done
                echo "SET FOREIGN_KEY_CHECKS=1;"
            } | $MYSQL "$DB_DATABASE"
        fi
        log "Importing seed dump: $SEED_RESOLVED"
        import_dump "$SEED_RESOLVED"
        log "Seed import finished."
    else
        log "WARNING: a seed was requested but no dump was found (checked the mount"
        log "         at /var/www/seed/ and the bundled database/seed/init.sql.gz)."
        log "         Migrations will build an empty schema instead."
    fi
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

# Re-own anything the root-run artisan commands just wrote (logs, caches) so the
# www-data queue worker & scheduler can write to them.
chown -R www-data:www-data storage bootstrap/cache

log "Startup complete. Handing off to: $*"
exec "$@"
