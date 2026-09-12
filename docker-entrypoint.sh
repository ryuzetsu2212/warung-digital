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

# Never reuse a config cache created with the old/invalid key.
php artisan config:clear
php artisan migrate --force
php artisan db:seed --class=AdminUserSeeder --force

# Populate the menu and initial application data once on a fresh database.
# DatabaseSeeder also creates demo orders/reservations; do not rerun it when
# products already exist, otherwise every container restart would duplicate data.
if ! php artisan tinker --execute="exit(DB::table('products')->exists() ? 0 : 1);" >/dev/null 2>&1; then
    php artisan db:seed --class=DatabaseSeeder --force
fi

php artisan config:cache
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
