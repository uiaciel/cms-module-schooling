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
        Schema::create('ppdb_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');
            $table->string('registration_code')->unique();
            $table->enum('status', ['pending', 'verified', 'accepted', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->foreignId('ppdb_period_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_registrations');
    }
};
