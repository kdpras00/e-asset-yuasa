<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;
use App\Models\AssetLoan;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable FK checks to allow truncation
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        
        // Clear existing assets and loans
        Asset::truncate();
        AssetLoan::truncate();

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $data = [
            'Peralatan Kerja' => [
                'Meja kerja', 'Kursi kantor', 'Lemari arsip', 'Rak dokumen', 'Brankas'
            ],
            'Peralatan Elektronik' => [
                'Komputer / PC', 'Laptop', 'Printer', 'Scanner', 'Mesin fotokopi', 'Telepon kantor', 'Proyektor'
            ],
            'Perlengkapan ATK' => [
                'Kertas (HVS, folio, dll.)', 'Pulpen, pensil', 'Spidol, stabilo', 'Map, ordner', 'Stapler & isi staples', 'Paper clip'
            ],
            'Peralatan Pendukung' => [
                'AC', 'Kipas angin', 'Jam dinding', 'Dispenser air', 'CCTV'
            ],
            'Aset Penunjang Lainnya' => [
                'Kendaraan operasional (motor/mobil kantor)', 'ID card karyawan', 'Akses internet & jaringan', 'Software/aplikasi kantor (misalnya Microsoft Office)'
            ]
        ];

        // Real Locations (Mapped to 'group' column)
        $groups = [
            'Kantor Pusat (Head Office)', 'Gudang Utama (Warehouse)', 'Pabrik (Factory Plant)', 
            'Ruang Meeting (Meeting Room)', 'Pos Security', 'Lobby & Resepsionis', 'Kantin'
        ];

        foreach ($data as $category => $items) {
            foreach ($items as $index => $itemName) {
                $type = ($category == 'Perlengkapan ATK') ? 'consumable' : 'fixed';
                $qty = ($type == 'consumable') ? rand(50, 200) : 1;
                
                // Add some variety to locations
                $group = $groups[array_rand($groups)];

                Asset::create([
                    'name' => $itemName,
                    'code' => strtoupper(substr($category, 0, 3)) . '-' . rand(1000, 9999), 
                    'sap_code' => 'SAP-' . rand(10000, 99999),
                    'category' => $category,
                    'group' => $group,
                    'type' => $type,
                    'description' => 'Standard ' . $itemName . ' for ' . $group,
                    'purchase_date' => now()->subDays(rand(1, 700)),
                    'price' => rand(50000, 15000000),
                    'quantity' => $qty,
                    'location' => $group, 
                    'status' => 'baik',
                ]);
            }
        }
    }
}
