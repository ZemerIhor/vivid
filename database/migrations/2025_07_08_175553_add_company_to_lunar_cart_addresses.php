<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lunar_cart_addresses', function (Blueprint $table) {
            $table->string('company')->nullable()->after('last_name');
        });
    }

    public function down(): void
    {
        Schema::table('lunar_cart_addresses', function (Blueprint $table) {
            $table->dropColumn('company');
        });
    }
};
