<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example Karyawan
        User::updateOrCreate(
            ['email' => 'budisantoso@yuasa.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
                'position' => 'Staff Produksi',
                // Assuming a default image exists or just leaving it null for now, 
                // or pointing to a placeholder if user wants to see logic.
                // For now, let's use a placeholder URL if no file exists.
                // In a real app, we'd copy a file to storage. 
                // Let's leave image null or setup a dummy if needed?
                // User asked for "foto dari karyawan tersebut". 
                // I'll put a placeholder path.
                'image' => null, 
            ]
        );

        User::updateOrCreate(
            ['email' => 'sitirahayu@yuasa.com'],
            [
                'name' => 'Siti Rahayu',
                'password' => Hash::make('password'),
                'role' => 'karyawan',
                'position' => 'Admin Gudang',
                'image' => null,
            ]
        );
    }
}
