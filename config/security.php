<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Staff IP Whitelist
    |--------------------------------------------------------------------------
    |
    | Daftar IP address yang diizinkan untuk mengakses staff login.
    | Hanya perangkat dengan IP yang terdaftar yang dapat login sebagai staff.
    |
    | PENTING: Tambahkan IP address komputer kasir/staff warung Anda di sini.
    | Untuk mendapatkan IP address, jalankan: ipconfig (Windows) atau ifconfig (Linux/Mac)
    |
    */
    'staff_ip_whitelist' => [
        '127.0.0.1',           // Localhost untuk development
        '::1',                  // IPv6 localhost
        
        // ⚠️ TAMBAHKAN IP WARUNG ANDA DI BAWAH INI ⚠️
        // Contoh:
        // '192.168.1.100',     // Komputer kasir
        // '192.168.1.101',     // Komputer manager
        // '10.0.0.50',         // Laptop staff
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Security Settings
    |--------------------------------------------------------------------------
    */
    'session' => [
        // Admin session timeout (minutes) - lebih ketat dari staff
        'admin_timeout' => 30,
        
        // Staff session timeout (minutes)
        'staff_timeout' => 60,
        
        // Session fingerprinting enabled
        'fingerprint_enabled' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'rate_limit' => [
        // Staff login attempts per IP per 5 minutes
        'staff_login_attempts' => 5,
        'staff_login_decay_minutes' => 5,
    ],
];