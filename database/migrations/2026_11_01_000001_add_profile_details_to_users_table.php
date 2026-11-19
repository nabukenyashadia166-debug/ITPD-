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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student')->after('email');
            $table->string('student_number')->nullable()->after('role');
            $table->string('course')->nullable()->after('student_number');
            $table->string('year_of_study')->nullable()->after('course');
            $table->string('campus')->nullable()->after('year_of_study');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'student_number', 'course', 'year_of_study', 'campus']);
        });
    }
};
