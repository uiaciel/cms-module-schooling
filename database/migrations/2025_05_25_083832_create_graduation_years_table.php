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
        Schema::create('graduation_years', function (Blueprint $table) {
            $table->id();
            $table->year('year'); // contoh: 2025
            $table->date('open_date')->nullable(); // tanggal mulai pengumuman
            $table->date('close_date')->nullable(); // tanggal akhir akses
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduation_years');
    }
};
