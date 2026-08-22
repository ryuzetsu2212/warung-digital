<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin account
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => null,
            'phone' => '081234567890',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create Staff account if not exists
        if (!User::where('role', 'staff')->where('username', 'staff')->exists()) {
            User::create([
                'name' => 'Staff Warung',
                'username' => 'staff',
                'email' => null,
                'phone' => '081234567891',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
            ]);
        }
    }
}