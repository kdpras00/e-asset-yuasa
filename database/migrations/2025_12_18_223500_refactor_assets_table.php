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
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('group');
            $table->integer('stock')->after('quantity')->default(0);
        });

        // Initialize stock with current quantity value
        DB::statement('UPDATE assets SET stock = quantity');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('group')->nullable()->after('category');
            $table->dropColumn('stock');
        });
    }
};
