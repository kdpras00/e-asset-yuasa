<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'asset@yuasa.com'],
            [
                'name' => 'Tim Fixed Asset',
                'password' => Hash::make('password'),
                'role' => 'tim_faxed_asset',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pimpinan@yuasa.com'],
            [
                'name' => 'Kepala Department',
                'password' => Hash::make('password'),
                'role' => 'pimpinan',
            ]
        );
    }
}
