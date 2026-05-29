<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryAndProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan data lama untuk menghindari duplicate entry error
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $makananId = DB::table('categories')->insertGetId([
            'name' => 'Makanan', 
            'description' => 'Kategori untuk semua jenis produk makanan matang atau instan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $minumanId = DB::table('categories')->insertGetId([
            'name' => 'Minuman', 
            'description' => 'Kategori untuk minuman dingin, hangat, maupun kemasan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('products')->insert([
            [
                'category_id' => $makananId, 
                'code' => 'KBL-01', 
                'barcode' => '899123456701', 
                'name' => 'Nasi Kebuli Ayam Abuya', 
                'purchase_price' => 15000.00, 
                'selling_price' => 25000.00, 
                'stock' => 50, 
                'min_stock' => 5, 
                'is_active' => true, 
                'description' => 'Nasi kebuli khas Timur Tengah dengan lauk ayam bakar rempah.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $makananId, 
                'code' => 'KBL-02', 
                'barcode' => '899123456702', 
                'name' => 'Nasi Kebuli Kambing Abuya', 
                'purchase_price' => 22000.00, 
                'selling_price' => 38000.00, 
                'stock' => 30, 
                'min_stock' => 5, 
                'is_active' => true, 
                'description' => 'Nasi kebuli premium dengan potongan daging kambing empuk tidak bau prengus.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $minumanId, 
                'code' => 'MNM-01', 
                'barcode' => '899123456703', 
                'name' => 'Es Teh Manis', 
                'purchase_price' => 1000.00, 
                'selling_price' => 4000.00, 
                'stock' => 100, 
                'min_stock' => 10, 
                'is_active' => true, 
                'description' => 'Es teh manis segar pelepas dahaga.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $minumanId, 
                'code' => 'MNM-02', 
                'barcode' => '899123456704', 
                'name' => 'Jus Kurma', 
                'purchase_price' => 5000.00, 
                'selling_price' => 12000.00, 
                'stock' => 4, 
                'min_stock' => 5,
                'is_active' => true, 
                'description' => 'Jus kurma asli yang sehat dan manis alami.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
