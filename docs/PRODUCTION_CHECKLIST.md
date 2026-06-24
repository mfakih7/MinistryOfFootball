# Ministry Of Football — Production Checklist

Go through this list before announcing the site is live on `ministryoffootball.online`. See [DEPLOYMENT.md](DEPLOYMENT.md) for the step-by-step commands behind each item.

## Environment

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://ministryoffootball.online` (must match the live URL exactly, including `https`)
- [ ] `APP_KEY` is set (`php artisan key:generate`, once)
- [ ] Database configured (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` for the cPanel MySQL database)

## Database

- [ ] Migrations run (`php artisan migrate --force`)
- [ ] Seeders run (`AdminUserSeeder`, `SettingSeeder` at minimum — see DEPLOYMENT.md)

## Store Configuration (Admin → Settings)

- [ ] Default admin password changed (default is `admin@ministryfootball.test` / `password` — **must not stay default in production**)
- [ ] WhatsApp number updated to the real store number
- [ ] Delivery fee updated to the real value
- [ ] Customization fee updated to the real value (default is `$0.00`)
- [ ] Store logo and favicon uploaded (Admin → Branding)
- [ ] Store phone, email, and address updated
- [ ] Policy page content filled in (shipping, returns, privacy, terms)
- [ ] SEO title/description updated

## SSL & Domain

- [ ] SSL certificate active (AutoSSL run and issued in cPanel)
- [ ] Force HTTPS redirect enabled for the domain
- [ ] Document root confirmed as `ministryoffootball.online/public`

## Storage & Assets

- [ ] Storage link working (`php artisan storage:link`; `public/storage` resolves)
- [ ] Product images loading correctly on homepage, shop, and product pages (not broken/placeholder)
- [ ] `public/build/` present and up to date (built locally via `npm run build`, committed, pulled on server)

## Functional Smoke Tests

- [ ] Checkout flow tested end-to-end (cart → checkout → order created)
- [ ] WhatsApp redirect tested (order success page sends a real, correctly formatted message to the configured number)
- [ ] Product customization tested (checkbox → required details → fee added to total)
- [ ] Admin login tested with the **new**, non-default password
- [ ] `/track-order` tested with a real order's phone number
- [ ] `/search?q=...` returns results

## SEO & Crawling

- [ ] `/sitemap.xml` tested and accessible
- [ ] `robots.txt` reviewed (confirm it allows the right pages and disallows `/admin`, `/cart`, `/checkout`, etc. as appropriate)

## Caching

- [ ] `php artisan optimize:clear` run before re-caching (clears any stale cache from a previous deploy)
- [ ] `php artisan config:cache` run
- [ ] `php artisan route:cache` run
- [ ] `php artisan view:cache` run

## Operations (recommended, not blocking)

- [ ] Regular database backups scheduled (cPanel → Backup, or a cron-based dump)
- [ ] Storage/upload backups scheduled (`storage/app/public`)
- [ ] `storage/logs/laravel.log` checked for errors after go-live
- [ ] Real WhatsApp number tested from a real phone, not just simulated
