# Ministry Of Football

Premium football jersey, NBA shirt, and sportswear e-commerce website. Customers browse and order through a WhatsApp-first checkout flow — there is no online payment gateway; every order is saved to the database and then confirmed with the customer over WhatsApp.

- **Live domain:** [ministryoffootball.online](https://ministryoffootball.online)
- **Stack:** Laravel 13, PHP 8.3, MySQL, Tailwind CSS 4, Alpine.js, Vite

---

## Project Overview

Ministry Of Football is a two-part application:

1. **Public storefront** — browse, filter, and order products via WhatsApp.
2. **Admin panel** — manage catalog, orders, coupons, homepage content, and store settings.

There is no payment gateway integration by design. The business model is: customer places an order on the site → order is saved → customer is redirected to WhatsApp with a pre-filled message → the team confirms and fulfills manually.

---

## Features

### Public website features

- Homepage with hero slider, category cards, "Shop by League" grid, featured/new-arrival product sections
- Shop page with category/league/team/price/size/color filters, sort, search, and pagination
- Product detail page with image gallery + lightbox zoom, size/color/quantity selection, related products
- Product customization (name/number) on jerseys marked customizable, with a configurable customization fee
- Cart with quantity controls, coupon codes, and live order summary
- WhatsApp-first checkout — no payment gateway, no card data ever collected
- Order success page and `/track-order` lookup by phone number
- Site search, static policy pages (shipping, returns, privacy, terms), `/sitemap.xml`, `robots.txt`

### Admin panel features

- Dashboard with key store metrics and reports
- Orders: view, update status, add admin notes, see the generated WhatsApp message and customization details
- Products: CRUD, multiple images (auto-generated thumbnail/medium/large/original variants), variants (size/color), customizable flag
- Catalog management: categories, leagues, teams, product types, sizes, colors
- Coupons (percentage/fixed, date-bound), homepage slides, customer feedback inbox
- Branding (logo/favicon/footer) and Settings (delivery fee, free shipping threshold, customization fee, currency, contact info, social links, SEO, policy page content)

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13, PHP 8.3 |
| Database | MySQL |
| Frontend | Blade, Tailwind CSS 4, Alpine.js (CDN) |
| Build tool | Vite |
| Images | Intervention Image (WebP-first, JPEG fallback) |

---

## Requirements

- PHP 8.3+ with extensions: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd` or `imagick`
- MySQL 5.7+ / MariaDB 10.3+
- Composer 2.x
- Node.js 18+ and npm (**local machine only** — the production server does not need Node/npm; see [docs/DEPLOYMENT.md](docs/DEPLOYMENT.md))

---

## Local Setup

```bash
git clone <repository-url> ministryoffootball
cd ministryoffootball

composer install
cp .env.example .env
php artisan key:generate

# Configure DB credentials in .env, then:
php artisan migrate --seed

php artisan storage:link

npm install
npm run dev   # or: npm run build
```

Visit `http://localhost:8000` (or your configured Herd/Valet/XAMPP URL).

### Admin login note

The `AdminUserSeeder` creates a default admin account:

```
Email:    admin@ministryfootball.test
Password: password
```

**Change this password immediately after the first production deploy** — see [docs/PRODUCTION_CHECKLIST.md](docs/PRODUCTION_CHECKLIST.md).

---

## Image Optimization Note

Product images are uploaded once and Intervention Image automatically generates four variants on disk:

| Variant | Max width | Used for |
|---|---|---|
| `thumbnail` | 400px | Product cards (homepage, shop, related products) |
| `medium` | 900px | Listing/detail previews |
| `large` | 1600px | Product detail main gallery |
| `original` | unscaled | Admin/reference only |

Images are encoded as **WebP** when supported by the server's image library, falling back to JPEG automatically. Product cards always use the `thumbnail` variant — never the original upload — to keep the homepage and shop page fast. Homepage slide images are capped at 1920px wide.

---

## WhatsApp Order Flow

1. Customer adds product(s) to cart and proceeds to checkout.
2. Customer fills in name, phone, address, and optional notes — **no payment is collected**.
3. On submit, an `Order` + `OrderItem` records are created in the database, and a formatted WhatsApp message (itemized products, customization, subtotal, delivery, total) is generated and stored on the order.
4. Customer is redirected to `wa.me/<store-whatsapp-number>?text=<message>` to send that message and continue the conversation with the store.
5. Admin manages the order status (`pending → confirmed → delivered`, etc.) from the admin Orders page.

The WhatsApp number used for this flow is configured in **Admin → Settings → WhatsApp Number**.

---

## Product Customization Flow

Jerseys (or any product) can be flagged `is_customizable` in the admin product form. When customizable:

1. At checkout, that cart item shows a "Customizable" badge and a **"Customize this item"** checkbox.
2. Checking it reveals a required textarea for customization details (e.g. `Name: RONALDO, Number: 7`).
3. The configured **Customization Fee** (Admin → Settings → Shipping, default `$0.00`) is added **once per customized item** (not per quantity) to the order total.
4. The customization request, details, and fee are stored per order item and included in the WhatsApp message and the admin order detail page.

---

## Production Deployment Summary

The production server (Namecheap cPanel) has **no Node/npm**, so:

- `npm run build` is run **locally** and the resulting `public/build/` directory is **committed to git**.
- The server only runs `composer install` and Laravel artisan commands — no `npm install`/`npm run build` on the server.
- The cPanel document root must point directly at `ministryoffootball.online/public` (not a `repositories/.../public` subpath).

Full step-by-step instructions: **[docs/DEPLOYMENT.md](docs/DEPLOYMENT.md)**
Pre-launch checklist: **[docs/PRODUCTION_CHECKLIST.md](docs/PRODUCTION_CHECKLIST.md)**

### Future deployment commands

**Local machine:**
```bash
npm run build
git add .
git commit -m "your message"
git push origin main
```

**Server** (`~/ministryoffootball.online`), or simply run `./deploy.sh`:
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
