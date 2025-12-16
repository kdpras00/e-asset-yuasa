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
        Schema::table('assets', function (Blueprint $table) {
            $table->string('sap_code')->nullable()->after('code');
            $table->string('image')->nullable()->after('description');
            $table->integer('quantity')->default(1)->after('price');
            $table->string('group')->nullable()->after('category'); // e.g., 'MACHINERY', 'VEHICLE'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['sap_code', 'image', 'quantity', 'group']);
        });
    }
};
