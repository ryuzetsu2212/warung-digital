# Reservasi & data lama di Railway TIDAK dipindah otomatis.
# Jalankan manual 1x setelah Supabase siap:
#   php scripts/import-backup-pgsql.php "<DB_URL>"
<?php
/**
 * Import database/backup.sql (SQLite dump, statements joined dengan literal \n)
 * ke Postgres. Membuat tabel via migrasi Laravel dulu (artisan migrate --force),
 * lalu script ini COPY data saja (skip CREATE TABLE, sessions, cache, migrations).
 */

$url = $argv[1] ?? exit("Usage: php import-backup-pgsql.php <postgres-url>\n");

$parts = parse_url($url);
$dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s;sslmode=require', $parts['host'], $parts['port'] ?? 5432, ltrim($parts['path'], '/'));
$pdo = new PDO($dsn, $parts['user'], urldecode($parts['pass'] ?? ''), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$raw = file_get_contents(__DIR__ . '/../database/backup.sql');
// dump dibuat dengan statement dipisah literal "\n" (backslash-n), bukan newline asli
$statements = array_filter(array_map('trim', explode('\\n', $raw)));

$skipTables = ['migrations', 'sessions', 'cache', 'cache_locks', 'password_reset_tokens'];
$imported = 0;

foreach ($statements as $stmt) {
    if (preg_match('/^(CREATE TABLE|CREATE INDEX|DROP TABLE)/i', $stmt)) continue; // struktur dari migrate
    if (preg_match('/^INSERT INTO (\w+)/i', $stmt, $m)) {
        if (in_array($m[1], $skipTables)) continue;
        // SQLite: '1' untuk boolean/tinyint → Postgres butuh true/false di kolom boolean.
        // Kolom tinyint(1) di dump: products.is_available, tables.qr_available
        if (in_array($m[1], ['products', 'tables'])) {
            $stmt = preg_replace("/VALUES \((.*), '([01])'\)$/VALUES ($1, $2)", $stmt, $stmt);
        }
    }
    try {
        $pdo->exec($stmt);
        $imported++;
    } catch (PDOException $e) {
        echo "GAGAL: " . substr($stmt, 0, 80) . "...\n  {$e->getMessage()}\n";
    }
}

echo "Selesai. $imported statement dijalankan.\n";
