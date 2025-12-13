<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\IncomingItem;
use App\Models\OutgoingItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT USER
        if (!User::where('email', 'admin@gudang.com')->exists()) {
            User::create([
                'name' => 'Kepala Gudang',
                'email' => 'admin@gudang.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
        }

        if (!User::where('email', 'staff@gudang.com')->exists()) {
            User::create([
                'name' => 'Staff Operator',
                'email' => 'staff@gudang.com',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
            ]);
        }

        $adminId = User::first()->id;

        // 2. MASTER DATA
        $categories = ['Elektronik', 'Makanan Ringan', 'Minuman', 'Bahan Baku', 'Alat Tulis Kantor'];
        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat]);
        }

        $units = [
            ['name' => 'Pcs', 'symbol' => 'pcs'],
            ['name' => 'Kilogram', 'symbol' => 'kg'],
            ['name' => 'Box', 'symbol' => 'box'],
            ['name' => 'Liter', 'symbol' => 'L'],
        ];
        foreach ($units as $unit) {
            Unit::firstOrCreate(['symbol' => $unit['symbol']], ['name' => $unit['name']]);
        }

        $suppliers = [
            ['name' => 'PT. Sumber Makmur', 'contact' => '08123456789', 'address' => 'Jakarta'],
            ['name' => 'CV. Abadi Jaya', 'contact' => '08987654321', 'address' => 'Bandung'],
        ];
        foreach ($suppliers as $sup) {
            Supplier::firstOrCreate(['name' => $sup['name']], ['contact' => $sup['contact'], 'address' => $sup['address']]);
        }

        // 3. DATA BARANG
        $catElektronik = Category::where('name', 'Elektronik')->first()->id;
        $unitPcs = Unit::where('symbol', 'pcs')->first()->id;

        $itemsData = [
            ['sku' => 'LAP-DELL-001', 'name' => 'Laptop Dell Latitude', 'category_id' => $catElektronik, 'unit_id' => $unitPcs, 'stock' => 15, 'stock_minimum' => 5],
            ['sku' => 'MOU-LOG-102', 'name' => 'Mouse Logitech B100', 'category_id' => $catElektronik, 'unit_id' => $unitPcs, 'stock' => 50, 'stock_minimum' => 10],
        ];

        foreach ($itemsData as $data) {
            Item::updateOrCreate(
                ['sku' => $data['sku']],
                [
                    'name' => $data['name'],
                    'category_id' => $data['category_id'],
                    'unit_id' => $data['unit_id'],
                    'stock' => $data['stock'],
                    'stock_minimum' => $data['stock_minimum'],
                    'barcode' => $data['sku']
                ]
            );
        }

        // 4. DATA TRANSAKSI (PERBAIKAN NAMA KOLOM DI SINI)
        $items = Item::all();
        $supplierIds = Supplier::pluck('id');

        foreach ($items as $item) {
            // Transaksi Masuk
            for ($i = 0; $i < 5; $i++) {
                $date = Carbon::now()->subMonths(rand(0, 6))->subDays(rand(1, 28));
                IncomingItem::create([
                    'item_id' => $item->id,
                    'operator_id' => $adminId, // PERBAIKAN: user_id -> operator_id
                    'supplier_id' => $supplierIds->random(),
                    'qty' => rand(10, 50),
                    'date_in' => $date,       // PERBAIKAN: transaction_date -> date_in
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }

            // Transaksi Keluar
            for ($i = 0; $i < 5; $i++) {
                $date = Carbon::now()->subMonths(rand(0, 6))->subDays(rand(1, 28));
                OutgoingItem::create([
                    'item_id' => $item->id,
                    'operator_id' => $adminId, // PERBAIKAN: user_id -> operator_id
                    'qty' => rand(1, 10),
                    'purpose' => 'Permintaan Divisi Dummy', // PERBAIKAN: notes -> purpose
                    'date_out' => $date,      // PERBAIKAN: transaction_date -> date_out
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
