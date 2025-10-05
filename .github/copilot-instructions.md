# Copilot Instructions for Red Box (PHP + MySQL)

## Project Overview
- This is a PHP web application using MySQL for data storage.
- The `/public` directory is the web document root; all public-facing pages and assets are here.
- Core business logic and configuration are in `/inc`.
- Database schema and migrations are in `/sql/schema.sql`.

## Setup Workflow
1. Copy `inc/config.example.php` to `inc/config.php` and set DB credentials.
2. Import `sql/schema.sql` into a MySQL database named `redbox` (or update DB name in config).
3. Place the project so `/public` is the web server's document root.

## Key Directories & Files
- `/public`: Entry points (e.g., `index.php`, `login.php`, `perfil.php`), assets, and temp scripts.
- `/inc`: Auth, config, DB connection, rendering helpers.
- `/sql/schema.sql`: Database schema.

## Coding Patterns
- All user-facing logic is routed through `/public/*.php` files.
- Shared logic (DB, auth, config) is included from `/inc` using `require` or `include`.
- Use `$_SESSION` for authentication state; see `inc/auth.php`.
- Database access is via `inc/db.php` (uses mysqli).
- Configuration is loaded from `inc/config.php`.
- Minimal use of external libraries; most logic is custom PHP.

## Developer Workflows
- No build step; edit PHP files directly.
- For DB changes, update `sql/schema.sql` and re-import as needed.
- Debug by editing PHP and reloading in browser; errors appear in browser output.

## Conventions
- Keep all public pages in `/public`.
- Use `/inc` for reusable logic and configuration.
- Use `/public/assets` for static files (CSS, images).
- Temporary or admin scripts go in `/public/temp`.

## Example: Adding a New Page
1. Create `public/newpage.php`.
2. Include shared logic as needed:
   ```php
   require_once '../inc/auth.php';
   require_once '../inc/db.php';
   ```
3. Add page logic and output HTML.

## Integration Points
- MySQL database (see `inc/db.php` and `inc/config.php`).
- Session-based authentication (see `inc/auth.php`).

---
_If any conventions or workflows are unclear, please ask for clarification or provide feedback to improve these instructions._
