<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE ppdb_registrations MODIFY status ENUM('pending', 'verified', 'accepted', 'rejected', 'registered') NOT NULL DEFAULT 'registered'");
        DB::table('ppdb_registrations')
            ->where('status', 'pending')
            ->update(['status' => 'registered']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        DB::statement("ALTER TABLE ppdb_registrations MODIFY status ENUM('pending', 'verified', 'accepted', 'rejected') NOT NULL DEFAULT 'pending'");

        DB::table('ppdb_registrations')
            ->where('status', 'registered')
            ->update(['status' => 'pending']);
    }
};
