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


        // Define ALL valid locations first
        $allLocations = [
            'Ruang Meeting', 'Ruang Kerja Staff', 'Ruang Manager', 
            'Lobby Utama', 'Distribusi', 'Ruang Arsip', 'Pantry Kantor',
            'Ruang Server', 'Resepsionis', 'Area Parkir', 'Pos Security'
        ];

        // Departments
        $departments = [
            'HRD', 'Finance', 'IT', 'Production', 'Logistics', 'Marketing', 'Procurement', 'GA (General Affair)'
        ];

        foreach ($data as $category => $items) {
            foreach ($items as $index => $itemName) {
                $type = ($category == 'Perlengkapan ATK') ? 'consumable' : 'fixed';
                $qty = ($type == 'consumable') ? rand(50, 200) : 1;
                
                // Determine valid locations based on Category and Item Name
                $validLocations = [];

                if ($category == 'Peralatan Kerja') {
                    // Meja, Kursi, etc. can be in any office room
                    $validLocations = ['Ruang Kerja Staff', 'Ruang Manager', 'Ruang Meeting', 'Resepsionis', 'Ruang Arsip'];
                } elseif ($category == 'Peralatan Elektronik') {
                    if (str_contains($itemName, 'Proyektor')) {
                        $validLocations = ['Ruang Meeting'];
                    } elseif (str_contains($itemName, 'Server')) {
                        $validLocations = ['Ruang Server'];
                    } else {
                        // PC, Laptop, Printer etc.
                        $validLocations = ['Ruang Kerja Staff', 'Ruang Manager', 'Resepsionis', 'Ruang Server'];
                    }
                } elseif ($category == 'Perlengkapan ATK') {
                    // ATK mostly in staff rooms or storage
                    $validLocations = ['Ruang Kerja Staff', 'Ruang Arsip', 'Distribusi'];
                } elseif ($category == 'Peralatan Pendukung') {
                    if (str_contains(strtolower($itemName), 'dispenser') || str_contains(strtolower($itemName), 'kopi')) {
                        $validLocations = ['Pantry Kantor', 'Ruang Meeting', 'Lobby Utama'];
                    } elseif (str_contains(strtolower($itemName), 'ac') || str_contains(strtolower($itemName), 'kipas')) {
                         $validLocations = ['Ruang Kerja Staff', 'Ruang Manager', 'Ruang Meeting', 'Lobby Utama'];
                    } else {
                         $validLocations = ['Ruang Kerja Staff', 'Lobby Utama'];
                    }
                } elseif ($category == 'Aset Penunjang Lainnya') {
                    if (str_contains(strtolower($itemName), 'kendaraan') || str_contains(strtolower($itemName), 'mobil') || str_contains(strtolower($itemName), 'motor')) {
                        $validLocations = ['Area Parkir'];
                    } else {
                        $validLocations = ['Lobby Utama', 'Resepsionis'];
                    }
                }

                // Fallback if empty (shouldn't happen with good logic, but safety first)
                if (empty($validLocations)) {
                    $validLocations = ['Ruang Kerja Staff']; 
                }

                // Pick one random location from the valid list
                $group = $validLocations[array_rand($validLocations)];
                
                // Determine Department based on Location and Asset Type
                $department = $departments[array_rand($departments)]; // Default random

                // Logic Override
                if ($group == 'Ruang Server') {
                    $department = 'IT';
                } elseif (in_array($group, ['Pantry Kantor', 'Lobby Utama', 'Area Parkir', 'Pos Security', 'Ruang Meeting'])) {
                    $department = 'GA (General Affair)';
                } elseif ($group == 'Distribusi') {
                    $department = 'Logistics';
                }
                
                // Specific Asset Overrides
                if (str_contains($itemName, 'Server')) {
                    $department = 'IT'; // Servers always IT
                } elseif (str_contains(strtolower($itemName), 'kendaraan') || str_contains(strtolower($itemName), 'mobil') || str_contains(strtolower($itemName), 'motor')) {
                    $department = 'GA (General Affair)'; // Vehicles always GA
                } elseif (str_contains(strtolower($itemName), 'cctv') || str_contains(strtolower($itemName), 'ac')) {
                    $department = 'GA (General Affair)'; // Building facilities always GA
                }

                Asset::create([
                    'name' => $itemName,
                    'code' => strtoupper(substr($category, 0, 3)) . '-' . rand(1000, 9999), 
                    'sap_code' => 'SAP-' . rand(10000, 99999),
                    'category' => $category,

                    'type' => $type,
                    'description' => 'Standard ' . $itemName . ' for ' . $group,
                    'purchase_date' => now()->subDays(rand(1, 700)),
                    'price' => rand(50000, 15000000),
                    'quantity' => $qty,
                    'stock' => $qty,
                    'location' => $group, 
                    'department' => $department,
                    'status' => 'baik',
                ]);
            }
        }
    }
}
