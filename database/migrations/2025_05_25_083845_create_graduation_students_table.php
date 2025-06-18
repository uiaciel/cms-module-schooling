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
        Schema::create('graduation_students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('graduation_year_id');
            $table->string('sk');
            $table->string('name');
            $table->string('nisn')->unique();
            $table->date('birth_date');
            $table->enum('graduation_status', ['LULUS', 'TIDAK LULUS', 'LAINNYA'])->default('LULUS');
            $table->string('pdf_path')->nullable(); // path file PDF kelulusan
            $table->date('accessed_at')->nullable();

            $table->foreign('graduation_year_id')->references('id')->on('graduation_years')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduation_students');
    }
};
