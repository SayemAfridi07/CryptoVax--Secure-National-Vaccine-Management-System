<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Stores RSA and ECC key pairs
        // system keys (owner_id = null) = used to encrypt all user data
        // user keys (owner_id = user id) = used for posts/vaccine data
        Schema::create('crypto_keys', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type')->default('system'); // 'system' or 'user'
            $table->unsignedBigInteger('owner_id')->nullable(); // null = system key
            $table->string('key_type');    // 'rsa_public', 'rsa_private', 'ecc_public', 'ecc_private'
            $table->text('key_data');      // JSON: key values
            $table->string('key_hash', 64); // SHA256 of key_data (integrity check)
            $table->timestamp('rotated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crypto_keys');
    }
};