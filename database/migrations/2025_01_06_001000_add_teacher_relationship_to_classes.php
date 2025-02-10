<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // First modify the classes table
        if (Schema::hasColumn('classes', 'teacher_id')) {
            Schema::table('classes', function (Blueprint $table) {
                $table->dropForeign(['teacher_id']);
                $table->dropColumn('teacher_id');
            });
        }

        
    }

    public function down(): void
    {
        Schema::dropIfExists('classe_teacher');

        // Restore the original structure
        Schema::table('classes', function (Blueprint $table) {
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
        });
    }
}; 