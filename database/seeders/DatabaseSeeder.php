<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Asset;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users
        User::updateOrCreate(
            ['email' => 'asset@yuasa.com'],
            [
                'name' => 'Tim Faxed Asset',
                'password' => Hash::make('password'),
                'role' => 'tim_faxed_asset',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pimpinan@yuasa.com'],
            [
                'name' => 'Pimpinan Yuasa',
                'password' => Hash::make('password'),
                'role' => 'pimpinan',
            ]
        );

        // Dummy Assets
        $categories = ['MACHINERY', 'VEHICLE', 'TOOLS', 'BUILDING & CONSTRUCTION', 'COMPUTER & EQUIPMENT', 'FURNITURE'];
        $groups = ['BUILDING & EQUIPMENT', 'DRAINASE INSTALLATION', 'Machine', 'Tools', 'Vehicle', 'Computer', 'Network', 'Office'];
        
        for ($i = 0; $i < 20; $i++) {
            Asset::create([
                'name' => 'Asset ' . ($i + 1),
                'code' => 'CODE-' . rand(1000, 9999),
                'sap_code' => 'SAP-' . rand(10000, 99999),
                'category' => $categories[array_rand($categories)],
                'group' => $groups[array_rand($groups)],
                'description' => 'Description for Asset ' . ($i + 1),
                'purchase_date' => now()->subDays(rand(1, 365)),
                'price' => rand(1000000, 50000000),
                'quantity' => rand(1, 10),
                'location' => 'Location ' . rand(1, 5),
                'status' => 'baik',
            ]);
        }
    }
}
