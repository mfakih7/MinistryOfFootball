# Ministry Of Football — Production Deployment Checklist

Use this checklist before handing the site to the client or deploying to a live server.

## Environment (`.env`)

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://your-domain.com` (must match the public site URL exactly)
- [ ] `APP_KEY` is set (run `php artisan key:generate` once if missing)

## Database

- [ ] `DB_CONNECTION=mysql`
- [ ] `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` configured for production
- [ ] Database created and user has correct privileges
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Run seeders only if needed on fresh install: `php artisan db:seed --force`

## Storage & Assets

- [ ] Run `php artisan storage:link`
- [ ] Ensure `storage/` and `bootstrap/cache/` are writable by the web server
- [ ] Run `npm ci` (or `npm install`) then `npm run build`
- [ ] Confirm uploaded product images load from `/storage/...`

## Laravel Optimization

- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] `php artisan event:cache` (if events are used)
- [ ] Optional: `php artisan optimize`

## Admin & Store Settings

- [ ] Change default admin password (`admin@ministryfootball.test` / `password`) immediately
- [ ] Update **WhatsApp number** in Admin → Settings (`whatsapp_number`)
- [ ] Update store phone, email, and address
- [ ] Review delivery fee and free shipping threshold
- [ ] Fill in policy page content (shipping, returns, privacy, terms)
- [ ] Update SEO title and description

## Mail (Optional)

- [ ] Configure `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`
- [ ] Set `MAIL_FROM_ADDRESS` and `MAIL_FROM_NAME`
- [ ] Send a test email if contact/order notifications are enabled later

## Web Server

- [ ] Document root points to `/public` (not project root)
- [ ] HTTPS enabled with valid SSL certificate
- [ ] `mod_rewrite` / nginx rewrite rules configured for Laravel
- [ ] PHP 8.3+ with required extensions (pdo_mysql, mbstring, openssl, fileinfo, gd or imagick)

## Security & Operations

- [ ] Remove or protect any dev/test routes and debug tools
- [ ] Set up **regular database backups** (daily recommended)
- [ ] Set up file/storage backups for uploaded images
- [ ] Monitor disk space for `storage/app/public`
- [ ] Restrict admin URL if desired (e.g. IP allowlist or separate subdomain)

## Post-Deploy Smoke Tests

- [ ] Homepage loads with slides and product sections
- [ ] Shop filters and product pages work
- [ ] Cart → checkout → order success → WhatsApp redirect
- [ ] `/track-order` finds orders by phone
- [ ] `/search?q=...` returns results
- [ ] Policy pages load from settings content
- [ ] Admin login, orders, reports, coupons, and CRUD all work
- [ ] Confirm order status **confirmed** deducts stock once; **cancelled** restores stock
- [ ] `/sitemap.xml` accessible

## Client Handoff Reminders

- [ ] Provide admin login credentials securely (not by email in plain text if possible)
- [ ] Document how to add products, manage orders, and update homepage slides
- [ ] Confirm real WhatsApp number is live and tested end-to-end
- [ ] Replace placeholder social media URLs if still using defaults
