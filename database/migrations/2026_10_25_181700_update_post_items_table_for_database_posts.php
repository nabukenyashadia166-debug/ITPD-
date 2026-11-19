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
        Schema::table('post_items', function (Blueprint $table) {
            $table->renameColumn('item category', 'category');
            $table->renameColumn('item name', 'item_name');
            $table->renameColumn('item description', 'description');
            $table->renameColumn('location lost', 'location');
            $table->renameColumn('Date lost', 'date_lost');
            $table->renameColumn('contact information', 'contact');
        });

        Schema::table('post_items', function (Blueprint $table) {
            $table->string('contact')->nullable()->change();
            $table->string('photo_path')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('photo_path');
            $table->string('contact')->nullable(false)->change();
        });

        Schema::table('post_items', function (Blueprint $table) {
            $table->renameColumn('category', 'item category');
            $table->renameColumn('item_name', 'item name');
            $table->renameColumn('description', 'item description');
            $table->renameColumn('location', 'location lost');
            $table->renameColumn('date_lost', 'Date lost');
            $table->renameColumn('contact', 'contact information');
        });
    }
};
