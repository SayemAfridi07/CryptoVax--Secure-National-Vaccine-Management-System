<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // --- RSA Encrypted fields ---
            // Original: name, email, nid, dob, phone stored as plaintext
            // New: we store encrypted versions
            $table->text('name_encrypted')->nullable();
            $table->text('email_encrypted')->nullable();
            $table->text('nid_encrypted')->nullable();
            $table->text('dob_encrypted')->nullable();
            $table->text('phone_encrypted')->nullable();

            // --- Email hash for login lookup ---
            // We can't query encrypted email, so store SHA256 hash for finding user
            $table->string('email_hash', 64)->nullable()->index();

            // --- Custom password hash (replaces Laravel's bcrypt) ---
            $table->string('password_salt', 64)->nullable();
            $table->string('password_custom', 128)->nullable();

            // --- MAC tag for data integrity (HMAC) ---
            $table->string('mac_tag', 128)->nullable();

            // --- 2FA OTP ---
            $table->string('otp_code', 6)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->boolean('two_fa_verified')->default(false);

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'name_encrypted', 'email_encrypted',
                'nid_encrypted', 'dob_encrypted', 'phone_encrypted',
                'email_hash', 'password_salt', 'password_custom',
                'mac_tag', 'otp_code', 'otp_expires_at', 'two_fa_verified',
            ]);
        });
    }
};