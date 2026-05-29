<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSimulatedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('transaction_items')->truncate();
        DB::table('transactions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $cashierId = DB::table('users')->where('username', 'kasir')->value('id');
        
        $kebuliAyam = DB::table('products')->where('code', 'KBL-01')->first();
        $kebuliKambing = DB::table('products')->where('code', 'KBL-02')->first();
        $esTeh = DB::table('products')->where('code', 'MNM-01')->first();

        if (!$cashierId || !$kebuliAyam || !$kebuliKambing || !$esTeh) {
            $this->command->error('Jalankan RoleAndUserSeeder dan CategoryAndProductSeeder terlebih dahulu!');
            return;
        }

        $item1_subtotal = (2 * $kebuliAyam->selling_price) - 0;
        $item2_subtotal = (2 * $esTeh->selling_price) - 0;
        $grand_total1 = $item1_subtotal + $item2_subtotal;

        $transactionId1 = DB::table('transactions')->insertGetId([
            'invoice_number' => 'INV-' . date('Ymd') . '-0001', 
            'cashier_id' => $cashierId, 
            'transaction_date' => now()->subHours(2), 
            'total_item' => 4, 
            'subtotal' => $grand_total1, 
            'discount_amount' => 0, 
            'grand_total' => $grand_total1, 
            'paid_amount' => 60000.00,
            'change_amount' => 2000.00,
            'payment_method' => 'cash', 
            'status' => 'paid', 
            'notes' => 'Makan di tempat (Dine-in)', 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('transaction_items')->insert([
            [
                'transaction_id' => $transactionId1, 
                'product_id' => $kebuliAyam->id, 
                'qty' => 2, 
                'price' => $kebuliAyam->selling_price, 
                'discount_amount' => 0, 
                'subtotal' => $item1_subtotal, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'transaction_id' => $transactionId1, 
                'product_id' => $esTeh->id,
                'qty' => 2, 
                'price' => $esTeh->selling_price, 
                'discount_amount' => 0, 
                'subtotal' => $item2_subtotal, 
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);


        $item3_subtotal = (1 * $kebuliKambing->selling_price) - 3000; 
        $grand_total2 = $item3_subtotal;

        $transactionId2 = DB::table('transactions')->insertGetId([
            'invoice_number' => 'INV-' . date('Ymd') . '-0002', 
            'cashier_id' => $cashierId, 
            'transaction_date' => now()->subMinutes(15), 
            'total_item' => 1, 
            'subtotal' => $kebuliKambing->selling_price, 
            'discount_amount' => 3000.00, 
            'grand_total' => $grand_total2, 
            'paid_amount' => 35000.00, 
            'change_amount' => 0, 
            'payment_method' => 'qris', 
            'status' => 'paid', 
            'notes' => 'Take away / bungkus', 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Simpan item rincian untuk Transaksi 2
        DB::table('transaction_items')->insert([
            'transaction_id' => $transactionId2, 
            'product_id' => $kebuliKambing->id, 
            'qty' => 1, 
            'price' => $kebuliKambing->selling_price, 
            'discount_amount' => 3000.00, 
            'subtotal' => $item3_subtotal, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
