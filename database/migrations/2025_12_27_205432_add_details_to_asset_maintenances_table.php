<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('asset_maintenances', function (Blueprint $table) {
            $table->string('warranty_status')->default('tidak')->after('cost'); // ada, tidak, habis
            $table->string('room')->nullable()->after('description');
            $table->string('image')->nullable()->after('room'); // Proof of damage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_maintenances', function (Blueprint $table) {
            $table->dropColumn(['warranty_status', 'room', 'image']);
        });
    }
};
