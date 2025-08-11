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
        Schema::create('student_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('address');
            $table->string('gender');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('doc_kk');
            $table->string('doc_akta');
            $table->string('nisn')->unique();
            $table->string('nik')->unique();
            $table->string('previous_school');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_registrations');
    }
};
