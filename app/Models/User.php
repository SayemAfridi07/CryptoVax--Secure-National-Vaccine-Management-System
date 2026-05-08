<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Import our custom Crypto Services
use App\Services\Crypto\RSACrypto;
use App\Services\Crypto\ECCCrypto;
use App\Services\Crypto\KeyManager;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Requirement #7: We remove plaintext 'name', 'nid', 'dob', 'phone' 
     * from the fillable array to ensure they are never stored as plaintext.
     */
    protected $fillable = [
        'email', 
        'password', 
        'role',
        'centar_id',
        // Encrypted fields (Ciphertexts)
        'name_encrypted', 
        'email_encrypted',
        'nid_encrypted',  
        'dob_encrypted', 
        'phone_encrypted',
        // Lookup + integrity
        'email_hash', 
        'mac_tag',
        // Custom password fields
        'password_salt', 
        'password_custom',
        // 2FA
        'otp_code', 
        'otp_expires_at', 
        'two_fa_verified',
    ];

    protected $hidden = [
        'password', 
        'remember_token',
        'password_salt', 
        'password_custom',
        'otp_code',
    ];

    /**
     * Requirement #7 & #10: THE VIRTUAL NAME ACCESSOR
     * This allows {{ Auth::user()->name }} to work in your views 
     * even though the 'name' column is GONE from the database.
     */
    public function getNameAttribute()
    {
        if (!$this->name_encrypted) {
            return 'User';
        }

        try {
            $keyManager = new KeyManager();
            $rsa = new RSACrypto();
            $ecc = new ECCCrypto();

            // 1. Detect if it's ECC (stored as JSON) or RSA (stored as base64 string)
            $decoded = json_decode($this->name_encrypted, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // It is ECC (Update scenario)
                $eccKeys = $keyManager->getSystemECCKeys();
                $privKey = $eccKeys['private_key'];
                
                // Extract raw secret if key is wrapped in an array/json
                if (is_array($privKey)) {
                    $privKey = $privKey['d'] ?? $privKey['secret'] ?? array_values($privKey)[0];
                }
                
                return $ecc->decrypt($decoded, (string) $privKey);

            } else {
                // It is RSA (Registration scenario)
                $rsaKeys = $keyManager->getSystemRSAKeys();
                $privKey = $rsaKeys['private_key'];

                // Ensure key is an array for our manual RSA logic
                if (is_string($privKey)) {
                    $privKey = json_decode($privKey, true);
                }

                return $rsa->decrypt($this->name_encrypted, (array) $privKey);
            }
        } catch (\Exception $e) {
            // If decryption fails (e.g. keys missing), return a placeholder
            return "Encrypted User";
        }
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_expires_at'    => 'datetime',
            'two_fa_verified'   => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function registration(): HasOne
    {
        return $this->hasOne(Registration::class);
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(Center::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Logic & Helpers
    |--------------------------------------------------------------------------
    */

    public function getRedirectRouteName(): string
    {
        switch ($this->role) {
            case 1: return 'admin.index';
            case 2: return 'operator.index';
        }
        return 'front.index';
    }

    /**
     * Check if OTP is still valid (Requirement #4)
     */
    public function isOtpValid(string $code): bool
    {
        return $this->otp_code === $code
            && $this->otp_expires_at
            && now()->lessThanOrEqualTo($this->otp_expires_at);
    }
}