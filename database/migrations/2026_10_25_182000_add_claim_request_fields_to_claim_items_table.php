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
        Schema::table('claim_items', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('found_item_id')->nullable()->constrained('post_found_items')->nullOnDelete();
            $table->string('item_reference')->nullable();
            $table->string('item_name');
            $table->string('category')->nullable();
            $table->string('claimant_name');
            $table->string('student_id')->nullable();
            $table->string('contact');
            $table->string('email')->nullable();
            $table->text('description');
            $table->string('lost_location')->nullable();
            $table->text('additional_info')->nullable();
            $table->string('proof_path')->nullable();
            $table->string('status')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claim_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropConstrainedForeignId('found_item_id');
            $table->dropColumn([
                'item_reference',
                'item_name',
                'category',
                'claimant_name',
                'student_id',
                'contact',
                'email',
                'description',
                'lost_location',
                'additional_info',
                'proof_path',
                'status',
            ]);
        });
    }
};
