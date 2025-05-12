
# 🌿 Regenerative Farm Site & Online Store

A lightweight PHP 8 website that powers a small regenerative farm:  
*showcases the land, sells farm‑made products, runs a private admin dashboard, and keeps page‑weight low with AVIF/WebP images and vanilla JS.*

![Product Grid](docs/screenshots/store‑grid.png)  
*Responsive grid with quick‑add buttons and toast notifications.*

---

## ✨  Features

| Category | Highlights |
| -------- | ---------- |
| **Front‑of‑House** | Hero banner, blog, contact form, services pages, full store with product‑detail views. |
| **Cart & Checkout (session‑based)** | Add from card or detail page, in‑cart quantity edits, thumbnails, “Empty Cart” confirmation. |
| **Instant Toast** | Vanilla JS (`/js/store.js`) adds to cart via AJAX and shows a 3 s toast. |
| **Live Cart Count** | 🛒 Cart in the nav shows total quantity (`Cart (4)`). |
| **Admin Area** | `/admin/` login (env‑based credentials), dashboard, order export, CSV product imports, 10‑level products.json backups & restore. |
| **Responsive Images** | `includes/responsive-image.php` prints a `<picture>` with AVIF/WebP/JPEG sources. All images present at 640–2560 px and `_thumb`. |
| **Security Touches** | `.env` secrets, HTTP‑only cookies, CAPTCHA endpoint (`/captcha.php`), bot filtering rules in `.htaccess`. |
| **Routing** | `router.php` for PHP built‑in server; graceful 404 page & XML sitemap. |

---

## 🗂️  Project Map
```text
farm-website/
├── index.php                 # landing page
├── about.php
├── services/                 # simple service pages
├── blog/                     # 3 example posts
├── contact/                  # contact form + JS validation
├── store/
│   ├── index.php             # product grid
│   ├── product.php
│   ├── cart.php
│   ├── add_to_cart.php       # JSON endpoint
│   ├── empty-cart.php        # POST endpoint
│   ├── products.json         # ← editable catalog
│   └── products.csv          # sample import file
├── admin/
│   ├── login.php │ dashboard.php │ logout.php
│   └── product-manager/      # modular CRUD & CSV import logic
├── assets/images/            # hero_*.{avif,webp,jpeg}, product_XXXX.webp/avif/jpeg, *_thumb.*
├── includes/                 # header.php, nav.php, footer.php, responsive-image.php
├── css/ (base|layout|components|responsive).css
├── js/  (store.js, utils.js, main.js, contact.js)
├── .env                      # ADMIN_USERNAME + ADMIN_PASSWORD
└── .htaccess + robots.txt + sitemap.xml
```

---

## 🚀  Quick Start

### Prerequisites

* **PHP ≥ 8.0** with `json`, `gd` extensions.
* **Composer** – *optional*; no packages required.
* For AVIF/WebP generation you may use `cwebp` & `avifenc` *(not needed at runtime).*

### Local Dev

```bash
git clone https://github.com/your‑name/farm‑website.git
cd farm‑website

# Update environment secrets
cp .env .env.local && nano .env.local           # change admin creds

# Serve (PHP built‑in)
php -S localhost:8000 -t farm-website

# browse http://localhost:8000
```

> **Router note**   
> `router.php` lets `php -S` mimic Apache/Nginx: static files pass through, everything else falls back to the requested `.php` file or `404.php`.

---

## 🔧  Configuration

| What | File / Location | Notes |
| ---- | --------------- | ----- |
| **Admin credentials** | `.env` | `ADMIN_USERNAME`, `ADMIN_PASSWORD` (read by `admin/login.php`). |
| **Product catalog** | `store/products.json` | Array of 7 objects → rendered automatically; **id** must be unique integer. |
| **Images** | `assets/images/` | Naming: `product_slug_[640,1024,1536,2048,2560].(avif|webp|jpeg)` + `product_slug_thumb.jpeg`. Helper derives the paths. |
| **Backups** | `store/products.json.bak01–10` | Admin “Restore” page rotates backups automatically. |
| **Bot rules / redirects** | `.htaccess` | Includes security headers and basic rate limit examples. |
| **Email** | `contact/contact.php` | Uses PHPMailer; point SMTP creds to Hostinger or Gmail. |

---

## 🧩  Key Implementation Points

* **Cart Array** – `$_SESSION['cart'][<id>] = quantity`.  
* **`store.js`** – single IIFE:  
  * Detects `.add-to-cart-form` and `.quick-add` buttons.  
  * Posts to `add_to_cart.php`, shows toast, disables buttons while pending.  
* **Toast** – created on the fly, slides in/out with pure CSS opacity transition.  
* **Thumbnail Helper** – `thumbName()` swaps trailing `_####` for `_thumb` and converts `.jpg → .jpeg` to match disk files.  
* **Admin Import** – CSV preview vs. apply logic lives in `admin/product-manager/actions/`.

---

## 🛣️  Roadmap Ideas

- [ ] Payment gateway integration (Stripe Checkout).  
- [ ] Order database (SQLite) & email receipts.  
- [ ] CI/CD via GitHub Actions: run PHP‑Stan, PHPUnit, Lighthouse perf test.  
- [ ] Image build script (`npm run images`) to regenerate AVIF/WebP/thumbs.  
- [ ] i18n – EN/ES product descriptions.  

---

## 🤝  Contributing

1. **Fork** → `git checkout -b feature/your‑feature`  
2. Make changes & **commit**  
3. **PR** against `main` with a clear description 🚀

---

## 📜  License

**MIT** © 2025 Brandon Baker  
Use it, tweak it, share it.  
If you improve performance or accessibility, let us know!

---

> *This README was generated after a deep static analysis of `farm‑website.zip` – directory listing, PHP modules, JS helpers, CSS contents, and `.env` values were inspected to ensure accuracy.*
