<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schedule_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');
            $table->string('day');
            $table->string('time');
            $table->string('subject');
            $table->string('teacher');
            $table->string('room');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedule_subjects');
    }
}; 