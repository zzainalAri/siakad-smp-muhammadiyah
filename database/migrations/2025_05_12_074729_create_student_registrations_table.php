<?php

use App\Enums\StudentRegistrationStatus;
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
            $table->string('email')->unique();
            $table->string('nisn')->unique();
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('religion');
            $table->string('gender');
            $table->string('address');
            $table->string('previous_school');
            $table->string('phone');
            $table->string('nik')->unique();
            $table->string('no_kk')->unique();
            $table->string('mother_name');
            $table->string('father_name');
            $table->string('mother_nik')->unique();
            $table->string('father_nik')->unique();
            $table->string('status')->default(StudentRegistrationStatus::PENDING->value);
            $table->string('rejected_description')->nullable();
            $table->date('accepted_date')->nullable();
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
