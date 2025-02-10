<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['exam', 'project', 'assignment']);
            $table->decimal('factor', 4, 2);
            $table->decimal('max_grade', 5, 2)->default(20.00);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('controls');
    }
}; 