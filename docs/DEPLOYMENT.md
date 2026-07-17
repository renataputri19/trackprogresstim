# Deployment & Operations

Laravel 10 + Filament + Livewire app, containerized and deployed with **Dokploy**.

---

## 1. How the container behaves

One image runs **nginx + php-fpm + queue worker + scheduler** (via supervisor).
On every start, [`docker/entrypoint.sh`](../docker/entrypoint.sh) does:

| Boot                     | What happens                                                            |
| ------------------------ | ---------------------------------------------------------------------- |
| **First boot** (empty DB) | Imports the seed dump → runs `php artisan migrate` (only newer files run) |
| **Every redeploy**        | Skips the import → runs `php artisan migrate` (only *pending* migrations) |

"Empty DB" is detected by the absence of the `migrations` table. Because the
seed dump already contains the `migrations` table with its rows, migrations you
add *after* the dump was taken are the only ones that ever run on redeploys.
**Seeding never repeats** and existing data is never overwritten.

> To add a schema change in the future: commit a new migration file. The next
> redeploy runs it automatically. No manual DB steps.

**Forcing a re-seed (`FORCE_SEED`).** The auto-seed only runs on a truly empty
DB. If a database got half-initialized (e.g. migrations ran before the seed
file was mounted, so you have tables but no data), set the env var
`FORCE_SEED=true` on the app and redeploy. It **drops all tables** and re-imports
the dump for a clean slate — so it is **destructive**; use it only on dev or
during first-time recovery, and **remove the var afterward** so a later redeploy
never wipes real data.

---

## 2. The seed dump (`u762815253_Rentak2171.sql`)

This dump contains **real production data** and is deliberately kept **out of
git and out of the Docker image** (`.gitignore` + `.dockerignore`). It reaches
the container only by being mounted for the **first** deploy.

- **Local:** already wired in `docker-compose.yml` as a read-only mount.
- **Dokploy:** add a **Volume Mount / File Mount** on the app service:
  - Source: the uploaded `u762815253_Rentak2171.sql`
  - Mount path: `/var/www/seed/init.sql`
  - **Remove this mount after the first successful deploy.** Later boots ignore
    it anyway (the guard skips import), but removing it keeps prod data off the box.

---

## 3. Local development

```bash
cp .env.docker.example .env.docker
# set APP_KEY:  php artisan key:generate --show   (paste into .env.docker)
docker compose up --build
```

- App:      http://localhost:8080
- Mailpit:  http://localhost:8025
- DB:       localhost:3307 (MariaDB)

The first `up` imports the seed dump. To start over from scratch:
`docker compose down -v` (wipes the DB volume) then `up` again.

---

## 4. Dokploy setup

### 4a. Create the database (managed service)
1. **Create → Database → MariaDB** (v11).
2. Note the internal host, database name, user, password.

### 4b. Create the application
1. **Create → Application**, source = this GitHub repo.
   - **prod** app → branch `main`
   - (optional) **staging** app → branch `dev`
2. Build type: **Dockerfile** (`./Dockerfile`).
3. **Port:** the container listens on **80** (Dokploy's default Target Port), so
   just attach your domain — no port change needed.

### 4c. Environment variables
Paste from `.env.docker.example`. Minimum required:

```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:...            # php artisan key:generate --show
APP_URL=https://your-domain
DB_CONNECTION=mysql
DB_HOST=<dokploy-mariadb-host>
DB_PORT=3306
DB_DATABASE=<db>
DB_USERNAME=<user>
DB_PASSWORD=<password>
LOG_CHANNEL=stderr
```

> **APP_KEY must be stable.** Generate it once and keep it. Changing it
> invalidates existing sessions and any encrypted values. The container refuses
> to start without it.

### 4d. First deploy
1. Add the seed **file mount** → `/var/www/seed/init.sql` (see §2).
2. Deploy. Logs show: `Fresh database detected` → `Importing seed dump` → `Running migrations`.
3. Remove the seed mount.

### 4e. Persist uploads
User uploads (ticket photos) live in `storage/app/public`. Add a **persistent
volume** on the app service mounted at:

```
/var/www/html/storage/app/public
```

so uploads survive redeploys.

---

## 5. Git workflow (dev → main)

- `dev`  — integration branch. Do work here (or in feature branches merged into `dev`).
- `main` — production. Dokploy's prod app auto-deploys on push/merge to `main`.

```bash
git checkout dev
# ...work...
git commit -am "feature: ..."
git push origin dev            # deploys to staging (if configured)

# promote to production
git checkout main
git merge --ff-only dev
git push origin main           # deploys to production
```

---

## 6. One-time history cleanup (already performed)

`vendor/`, `node_modules/`, and `public/build/` used to be committed (they were
commented out in `.gitignore`). They were purged from the **entire git history**
and the branches were force-pushed.

**Anyone with an existing clone must re-clone** (or reset to the rewritten
remote) — old local copies still contain the removed history and will conflict:

```bash
# discard local copy and get the clean one
git fetch origin
git reset --hard origin/main
```
