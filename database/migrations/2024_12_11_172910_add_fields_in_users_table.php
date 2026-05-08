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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nid', 17)->nullable();
            $table->date('dob')->nullable();
            $table->string('phone', 15)->nullable();
            $table->enum('role', [0, 1, 2])->default(0);
            $table->foreignId('center_id')->nullable()->constrained()->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nid');
            $table->dropColumn('dob');
            $table->dropColumn('phone');
            $table->dropColumn('role');
            $table->dropForeign(['center_id']);
            $table->dropColumn('center_id'); 
        });
    }
};
