<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')->whereIn('role', ['inventory', 'karyawan'])->delete();
        
        // Update pimpinan name to Kepala Department just to be sure
        DB::table('users')->where('role', 'pimpinan')->update(['name' => 'Kepala Department']);
        
        // Fix Tim Fixed Asset name typo if exists
        DB::table('users')->where('role', 'tim_faxed_asset')->update(['name' => 'Tim Fixed Asset']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot recover deleted users easily without backups
    }
};
