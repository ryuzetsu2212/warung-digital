<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan kolom boolean di Postgres memiliki tipe atau cast yang kompatibel,
        // atau jika ada sisa tipe tinyint/integer, convert menjadi boolean murni.
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE "tables" ALTER COLUMN "qr_available" DROP DEFAULT');
            DB::statement('ALTER TABLE "tables" ALTER COLUMN "qr_available" TYPE boolean USING CASE WHEN "qr_available"::text = \'1\' THEN true WHEN "qr_available"::text = \'true\' THEN true ELSE false END');
            DB::statement('ALTER TABLE "tables" ALTER COLUMN "qr_available" SET DEFAULT true');

            DB::statement('ALTER TABLE "products" ALTER COLUMN "is_available" DROP DEFAULT');
            DB::statement('ALTER TABLE "products" ALTER COLUMN "is_available" TYPE boolean USING CASE WHEN "is_available"::text = \'1\' THEN true WHEN "is_available"::text = \'true\' THEN true ELSE false END');
            DB::statement('ALTER TABLE "products" ALTER COLUMN "is_available" SET DEFAULT true');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed
    }
};
