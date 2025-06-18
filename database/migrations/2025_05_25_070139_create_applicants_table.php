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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('register_id')->nullable()->unique();
            $table->string('full_name');
            $table->string('nickname')->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->string('place_of_birth');
            $table->date('date_of_birth');
            $table->string('religion')->nullable();
            $table->text('address');
            $table->string('phone')->nullable();
            $table->string('previous_school')->nullable();
            $table->foreignId('ppdb_period_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
