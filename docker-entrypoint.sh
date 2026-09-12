#!/bin/sh
set -eu

# Laravel requires a base64-encoded 32-byte key for AES-256.
# Generate a temporary valid key only when Render's APP_KEY is missing/invalid.
if ! php -r '
$key = getenv("APP_KEY") ?: "";
if (strncmp($key, "base64:", 7) !== 0) exit(1);
$decoded = base64_decode(substr($key, 7), true);
exit ($decoded !== false && strlen($decoded) === 32) ? 0 : 1;
'; then
    export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
    echo "APP_KEY was missing or invalid; generated a valid temporary Laravel key."
fi

php artisan migrate --force
php artisan config:cache
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
