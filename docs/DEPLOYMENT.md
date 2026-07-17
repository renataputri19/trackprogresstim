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

## 2. Seeding

The prod and dev databases were seeded once from the original dump during
initial setup, and that dump was then **removed from the repo/history** — no
production data lives in git anymore. The seeding *plumbing* stays in place so a
brand-new environment can still be bootstrapped:

The entrypoint imports a dump only on a **fresh** DB (no `migrations` table). It
looks for one, in order:
1. `SEED_FILE=/path` (explicit override)
2. a mount at `/var/www/seed/init.sql` or `/var/www/seed/init.sql.gz`
3. a bundled `database/seed/init.sql[.gz]` (not committed by default)

**To bootstrap a new environment**, pick one:
- **Mount** the dump at `/var/www/seed/init.sql[.gz]` for the first deploy (then
  remove the mount), **or**
- temporarily drop `init.sql.gz` into `database/seed/` and deploy (then remove it
  again — remember it's production PII), **or**
- import manually via the DB terminal / `php artisan db:seed` (`SqlDumpSeeder`
  imports a dump from `database/seed/` when present).

`FORCE_SEED=true` forces a clean re-import on an already-initialized DB
(destructive — see §1).

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
For a **new** environment that needs the initial data, provide a dump first
(see §2 — mount it or drop it in `database/seed/`), then **Deploy**. On a fresh
DB the logs show: `Fresh database detected` → `Importing seed dump: ...` →
`Running migrations`. Afterwards, remove the dump.

The existing prod & dev DBs are already seeded, so their redeploys just run
`migrate` — nothing to do here.

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
