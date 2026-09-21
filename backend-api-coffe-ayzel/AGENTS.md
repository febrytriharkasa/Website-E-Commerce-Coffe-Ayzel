# AGENTS.md

## Project

CodeIgniter 4 PHP backend for "Coffee Ayzel". API + views for product/variant management. Pure PHP, no Node/npm.

## Requirements

- PHP 8.2+ with `intl`, `mbstring`, `json`, `mysqlnd` (or `pdo`) extensions
- Copy `.env` from `.env.dist` and set `baseURL` and database config

## Setup

```bash
composer install         # install PHP deps (includes CodeIgniter framework)
cp .env .env             # tailor baseURL, database, etc.
# Ensure web server document root points to ./public/
```

## Commands

- `composer test` — run PHPUnit test suite
- `php spark` — list available Spark commands (if any installed)
- Browse `http://localhost:8080` after starting built-in server:
  ```bash
  php -S localhost:8080 -t public
  ```

## Structure

- `app/Controllers/` — HTTP controllers (Product, SizeProduct, etc.)
- `app/Models/` — Eloquent-style models (ProductModel, SizeProductModel)
- `app/Views/` — Blade/php views: product/, sizeProduct/, auth/, dashboard/
- `app/Config/` — CodeIgniter config files
- `public/` — document root for web server
- `tests/` — PHPUnit tests

## Gotchas

- **No double-submit**: The create form at `app/Views/product/create.php` now disables the submit button and shows a spinner on submit (see line 103-107). Prevents users from clicking twice before redirect.
- **Server must serve from `public/`** — pointing to project root exposes `app/` and `vendor/`
- **CSRF field required** — all forms include `<?= csrf_field(); ?>`; missing it will reject POST requests
- **Validation is server-side** — `is-invalid` classes + `validation_show_error()` provide feedback; no client-side validation framework
- **File uploads** — max 2MB, image mime types only; CodeIgniter moves uploads to `imgProducts/`
- **Tests** — run via `composer test`; uses PHPUnit 10; fixtures/live data may be in `tests/`

## Architecture notes

- MVC pattern: Controllers handle requests, Models interact with DB, Views render output
- Routes defined in `app/Config/Routes.php`
- CSRF protection enabled globally in `app/Config/Filters.php`
- Image preview uses vanilla JS `FileReader` (no framework)