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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->boolean('status');
            $table->unsignedInteger('section')->default(1);
            $table->unsignedInteger('semester')->default(1);
            $table->double('daily_score', 5, 2);
            $table->double('midtern_score', 5, 2);
            $table->double('final_score', 5, 2);
            $table->double('final_grade', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
