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
        Schema::table('asset_loans', function (Blueprint $table) {
            $table->integer('amount')->default(1)->after('asset_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('position')->nullable()->after('role');
            $table->string('image')->nullable()->after('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asset_loans', function (Blueprint $table) {
            $table->dropColumn('amount');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['position', 'image']);
        });
    }
};
