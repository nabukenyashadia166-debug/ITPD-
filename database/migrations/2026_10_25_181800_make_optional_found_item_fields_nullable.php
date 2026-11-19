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
        Schema::table('post_found_items', function (Blueprint $table) {
            $table->string('condition')->nullable()->change();
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_found_items', function (Blueprint $table) {
            $table->string('condition')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
        });
    }
};
