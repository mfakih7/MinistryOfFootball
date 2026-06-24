# Deployment Guide — Namecheap cPanel

Target environment for **Ministry Of Football**:

| Setting | Value |
|---|---|
| Domain | `ministryoffootball.online` |
| Hosting | Namecheap shared hosting (cPanel) |
| PHP version | 8.3 |
| Production project path | `~/ministryoffootball.online` |
| Document root | `~/ministryoffootball.online/public` |
| Composer | `~/composer.phar` (no global `composer` command) |
| Node/npm | **Not available on the server** — build assets locally and commit them |

> **Critical decision:** the domain must point **directly** at `ministryoffootball.online/public`.
> Do **not** clone into something like `repositories/MinistryOfFootball` and point the domain at `repositories/MinistryOfFootball/public` — clone straight into `~/ministryoffootball.online` so the document root is simply `ministryoffootball.online/public`.

---

## A. First-Time Deployment

### 1. Clone the repository

SSH into the server, then:

```bash
cd ~
git clone <repository-url> ministryoffootball.online
cd ministryoffootball.online
```

### 2. Set the cPanel document root

In cPanel → **Domains** (or **Addon Domains**), set the document root for `ministryoffootball.online` to:

```
ministryoffootball.online/public
```

### 3. Install Composer dependencies

```bash
php ~/composer.phar install --optimize-autoloader --no-dev
```

### 4. Create and configure `.env`

```bash
cp .env.example .env
```

Edit `.env` with cPanel's File Manager or `nano .env` and set at minimum:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ministryoffootball.online

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_cpanel_db_name
DB_USERNAME=your_cpanel_db_user
DB_PASSWORD=your_cpanel_db_password
```

> cPanel MySQL databases/usernames are usually prefixed with your cPanel account name (e.g. `cpaneluser_mof`). Create the database and a user with full privileges on it via cPanel → **MySQL Databases** before this step.

### 5. Generate the application key

```bash
php artisan key:generate
```

### 6. Run migrations

```bash
php artisan migrate --force
```

### 7. Seed required data

```bash
php artisan db:seed --class=AdminUserSeeder --force
php artisan db:seed --class=SettingSeeder --force
```

> Only run these on a **fresh** database. If you need the full demo catalog too, see the other seeders in `database/seeders/` — but for a real launch you'll typically want just the admin user and default settings, then add real products through the admin panel.

### 8. Link storage

```bash
php artisan storage:link
```

This creates `public/storage` → `storage/app/public`, which is how uploaded product images, logos, and homepage slides are served.

### 9. Cache for production

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 10. SSL

1. In cPanel → **SSL/TLS Status**, run **AutoSSL** for the domain and wait for the certificate to issue (can take a few minutes).
2. Once active, enable **Force HTTPS Redirect** for the domain.
3. Update `.env`:
   ```env
   APP_URL=https://ministryoffootball.online
   ```
4. Re-cache config so Laravel picks up the change:
   ```bash
   php artisan config:cache
   ```

First-time deployment is complete. Now go through **[PRODUCTION_CHECKLIST.md](PRODUCTION_CHECKLIST.md)** before announcing the site is live.

---

## B. Future Deployments

Every deploy after the first follows this two-part flow: build locally, then pull + cache on the server.

### On your local machine

```bash
npm run build
git add .
git commit -m "your message"
git push origin main
```

`public/build/` (the compiled Tailwind CSS and Vite manifest) **must** be committed — the server has no npm to build it.

### On the server

```bash
cd ~/ministryoffootball.online
git pull origin main
php ~/composer.phar install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Or, using the `deploy.sh` helper script committed at the project root, simply:

```bash
cd ~/ministryoffootball.online
./deploy.sh
```

`deploy.sh` runs the exact same `git pull` → `composer install` → `migrate` → cache sequence shown above. The script is committed with the executable bit already set, but if the server ever reports `Permission denied` when running it, make it executable once:

```bash
chmod +x deploy.sh
```

---

## C. Important Notes

- **cPanel has no npm** — `public/build/` must be built locally and committed to git. Never rely on `npm run build` running on the server.
- **Never commit `.env`** — it holds production secrets (DB password, app key). It's already in `.gitignore`.
- **Never commit `vendor/`** — run `composer install` on the server instead. Already in `.gitignore`.
- **Do not change `public/index.php`** — Laravel's front controller assumes the standard directory layout relative to it; modifying paths there breaks autoloading.
- **Do not manually copy the `public/` folder elsewhere** — the document root must be the actual `public/` folder inside the cloned repo, not a duplicate.
- **Document root must remain** `ministryoffootball.online/public` — not the repo root, not a `repositories/...` subpath.
- **If CSS/JS is missing or stale after a deploy:** check that `public/build/manifest.json` exists and was actually updated by `npm run build` before you committed and pushed.
- **If uploaded images are missing (404):** check that `public/storage` is a working symlink — re-run `php artisan storage:link` on the server if needed.

---

## D. Troubleshooting

### 404 from LiteSpeed/Apache before Laravel even loads
The document root is wrong, or `.htaccess` is missing/misconfigured. Confirm cPanel's document root is exactly `ministryoffootball.online/public` and that `public/.htaccess` exists (Laravel ships one by default — don't delete it).

### Laravel 404 (Laravel's own "not found" page, not a server 404)
The route doesn't exist, or the route cache is stale after a deploy that changed routes. Run:
```bash
php artisan route:clear
php artisan route:cache
```

### SSL stuck on "pending" / AutoSSL not issuing
- Confirm DNS for `ministryoffootball.online` actually points at the cPanel server's IP (propagation can take up to 24–48h after a DNS change).
- In cPanel → SSL/TLS Status, try **Run AutoSSL** again manually.
- Make sure there's no conflicting/expired certificate already attached to the domain.

### `npm: command not found`
Expected — the server doesn't have Node/npm by design. Build assets locally with `npm run build` and commit `public/build/` instead of running npm on the server.

### `composer: command not found`
There's no global Composer on shared hosting. Always invoke it as:
```bash
php ~/composer.phar install --optimize-autoloader --no-dev
```
If `composer.phar` itself is missing from your home directory, download it once:
```bash
cd ~
curl -sS https://getcomposer.org/installer | php
```

### Storage symlink issue (`public/storage` missing or broken)
Some shared hosts don't allow symlinks via certain PHP execution contexts. Re-run:
```bash
php artisan storage:link
```
If that fails silently, check with your host whether `symlink()` is disabled in `php.ini` (`disable_functions`) — if so, you'll need to ask Namecheap support to enable it, or manually create the symlink via cPanel File Manager / SSH (`ln -s ../storage/app/public public/storage` run from inside the `public/` directory).

### `APP_URL` wrong (mixed content, broken asset URLs, redirect loops)
`APP_URL` in `.env` must exactly match the live URL including scheme:
```env
APP_URL=https://ministryoffootball.online
```
After changing it, always re-run `php artisan config:cache`.

### Database credentials wrong (`SQLSTATE[HY000] [1045] Access denied`)
- Re-check `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` in `.env` against cPanel → MySQL Databases.
- Confirm the database user is added to the database with **All Privileges** in cPanel → MySQL Databases → "Add User to Database".
- `DB_HOST` is almost always `127.0.0.1` or `localhost` on cPanel, not a remote host.

### Permissions issues (500 error, "Permission denied" in `storage/logs/laravel.log`)
`storage/` and `bootstrap/cache/` must be writable by the web server process:
```bash
chmod -R 775 storage bootstrap/cache
```
If the host runs PHP under a different user/group than your SSH user, you may need `chown` adjustments too — ask Namecheap support for the correct web server user if `chmod 775` alone doesn't resolve it.

### Generic 500 error with no obvious cause
Always check the log first:
```bash
tail -n 100 storage/logs/laravel.log
```
Most 500s trace back to one of: missing `.env`/`APP_KEY`, stale cache after a code change (run `php artisan optimize:clear`), or a missing PHP extension (check `php -m` for `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`/`imagick`).
