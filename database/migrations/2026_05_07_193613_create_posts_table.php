<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     *  Create posts table with zero plaintext storage.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            
            // Connect post to the user
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            /** * We use longText because encrypted ciphertexts (especially ECC JSON) 
             * can be much longer than standard strings.
             */
            $table->longText('title_encrypted');
            $table->longText('content_encrypted');
            
            //  Field to store the HMAC for data integrity
            $table->string('mac_tag'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};