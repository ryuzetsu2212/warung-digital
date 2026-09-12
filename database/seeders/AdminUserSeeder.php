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
        // Create/update Admin account for fresh deployments
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => null,
                'phone' => '081234567890',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Create/update Staff account for fresh deployments
        User::updateOrCreate(
            ['username' => 'staff'],
            [
                'name' => 'Staff Warung',
                'email' => null,
                'phone' => '081234567891',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
            ]
        );
    }
}