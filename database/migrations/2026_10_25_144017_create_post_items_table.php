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
        Schema::create('post_items', function (Blueprint $table) {
            $table->id();
            $table->string('item category');
            $table->string('item name');
            $table->text('item description');
            $table->string('location lost');
            $table->date('Date lost');
            $table->string('contact information');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_items');
    }
};
