<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Requirement #7: Remove sensitive plaintext columns.
     * We only keep name_encrypted, nid_encrypted, etc.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the plaintext columns
            if (Schema::hasColumn('users', 'name'))  $table->dropColumn('name');
            if (Schema::hasColumn('users', 'nid'))   $table->dropColumn('nid');
            if (Schema::hasColumn('users', 'dob'))   $table->dropColumn('dob');
            if (Schema::hasColumn('users', 'phone')) $table->dropColumn('phone');
        });
    }

    /**
     * Reverse the migrations (Put them back if needed)
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('nid')->nullable();
            $table->date('dob')->nullable();
            $table->string('phone')->nullable();
        });
    }
};