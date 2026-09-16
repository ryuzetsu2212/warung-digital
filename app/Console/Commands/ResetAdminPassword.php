<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password {password?} {--clear-lockout}';

    protected $description = 'Reset password user admin (default: admin123). Opsi --clear-lockout hapus rate-limit login.';

    public function handle(): int
    {
        $password = $this->argument('password') ?? 'admin123';

        $user = User::where('username', 'admin')->first();

        if (!$user) {
            $this->error("User 'admin' tidak ditemukan di database.");
            return self::FAILURE;
        }

        $user->forceFill(['password' => Hash::make($password)])->save();

        if ($this->option('clear-lockout')) {
            // Rate limiter keys dari UnifiedLogin + ThrottleStaffLogin + StaffDashboardBase
            RateLimiter::clear('staff-login:' . request()->server('REMOTE_ADDR', '0.0.0.0'));
            // Hapus semua key rate-limit admin berdasarkan username
            RateLimiter::clear('admin|0.0.0.0');
            $this->info('Rate-limit login dibersihkan.');
        }

        $this->info("Password admin berhasil direset ke: {$password}");
        return self::SUCCESS;
    }
}
