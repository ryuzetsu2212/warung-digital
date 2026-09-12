#!/bin/sh
set -eu

# Laravel requires a base64-encoded 32-byte key for AES-256.
# Normalize Render's value before Laravel reads it; otherwise generate one.
KEY_B64="$(php -r '
$key = trim(getenv("APP_KEY") ?: "");
if (strncmp($key, "base64:", 7) === 0) $key = substr($key, 7);
$decoded = base64_decode($key, true);
if ($decoded !== false && strlen($decoded) === 32) echo base64_encode($decoded);
')"
if [ -z "$KEY_B64" ]; then
    KEY_B64="$(php -r 'echo base64_encode(random_bytes(32));')"
    echo "APP_KEY was missing or invalid; generated a valid temporary Laravel key."
fi
export APP_KEY="base64:${KEY_B64}"

# Render Free + Supabase pooler: keep web sessions/cache local to avoid
# prepared-statement/session-table issues during authentication.
export APP_TIMEZONE="Asia/Jakarta"
export APP_DEBUG="true"
export SESSION_DRIVER="file"
export CACHE_STORE="file"
export QUEUE_CONNECTION="sync"

# Never reuse a config cache created with the old/invalid key.
php artisan config:clear
php artisan migrate --force
php artisan db:seed --class=AdminUserSeeder --force

# Seed the initial menu only when products is empty.
PRODUCT_COUNT="$(php artisan tinker --execute="echo \\App\\Models\\Product::count();" 2>/dev/null | tr -d '[:space:]')"
if [ "$PRODUCT_COUNT" = "0" ] || [ -z "$PRODUCT_COUNT" ]; then
    echo "Products empty; seeding initial menu data..."
    php artisan db:seed --class=DatabaseSeeder --force
else
    echo "Products already seeded ($PRODUCT_COUNT); skipping demo data."
fi

# Seed demo data safely; do not abort container startup if pooler drops statement
php artisan db:seed --class=AiMenuSeeder --force || true
php artisan db:seed --class=DemoCustomerReservationSeeder --force || true
php artisan db:seed --class=DemoOrderSeeder --force || true
php artisan config:cache
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
