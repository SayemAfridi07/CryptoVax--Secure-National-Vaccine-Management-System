<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove sensitive plaintext columns
            // These are now stored ONLY as encrypted
            if (Schema::hasColumn('users', 'nid'))   $table->dropColumn('nid');
            if (Schema::hasColumn('users', 'dob'))   $table->dropColumn('dob');
            if (Schema::hasColumn('users', 'phone')) $table->dropColumn('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nid')->nullable();
            $table->string('dob')->nullable();
            $table->string('phone')->nullable();
        });
    }
};