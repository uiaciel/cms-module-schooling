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
        Schema::table('ppdb_periods', function (Blueprint $table) {
            $table->string('brochure_pdf')->after('description')->nullable();
            $table->string('brochure_img')->after('brochure_pdf')->nullable();
            $table->string('status')->default('active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_periods', function (Blueprint $table) {
            $table->dropColumn('brochure_pdf');
            $table->dropColumn('brochure_img');
            $table->dropColumn('status');
        });
    }
};
