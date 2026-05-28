<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Bersihkan data lama terlebih dahulu (opsional, untuk mencegah duplicate error saat di-seed ulang)
        // Nonaktifkan foreign key check sementara agar bisa truncate data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Insert Data Master Roles (admin dan kasir)
        // Kita simpan ID-nya ke dalam variabel untuk digunakan oleh User di bawah
        $adminRoleId = DB::table('roles')->insertGetId([
            'name' => 'admin', // [cite: 8]
            'description' => 'Administrator dengan akses penuh ke seluruh sistem POS.', // [cite: 6, 21]
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kasirRoleId = DB::table('roles')->insertGetId([
            'name' => 'kasir', // [cite: 9]
            'description' => 'Kasir dengan akses terbatas pada transaksi dan riwayat mandiri.', // [cite: 22]
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Insert Data User Default (Satu Admin dan Satu Kasir)
        DB::table('users')->insert([
            [
                'role_id' => $adminRoleId, // Terhubung ke role admin [cite: 29, 44]
                'name' => 'Kurniawan Admin',
                'email' => 'admin@pos.com', // 
                'username' => 'admin', // 
                'password' => Hash::make('password123'), // Password di-hash demi keamanan 
                'phone' => '081234567890',
                'is_active' => true, // 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => $kasirRoleId, // Terhubung ke role kasir [cite: 29, 44]
                'name' => 'Siti Kasir',
                'email' => 'kasir@pos.com', // 
                'username' => 'kasir', // 
                'password' => Hash::make('kasir123'), // 
                'phone' => '081234567891',
                'is_active' => true, // 
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
