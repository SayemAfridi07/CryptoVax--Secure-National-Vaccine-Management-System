<?php

namespace App\Services\Crypto;

use App\Models\CryptoKey;
use Illuminate\Support\Facades\Cache;

/**
 * Key Management Module
 * Handles: generation, distribution, storage, rotation
 *
 * RSA  → used for user registration data (name, nid, phone, dob)
 * ECC  → used for posts and vaccine application data
 */
class KeyManager
{
    private RSACrypto $rsa;
    private ECCCrypto $ecc;

    public function __construct()
    {
        $this->rsa = new RSACrypto();
        $this->ecc = new ECCCrypto();
    }

    // -------------------------------------------------------
    // RSA KEYS (for user data)
    // -------------------------------------------------------

    public function getSystemRSAKeys(): array
    {
        return Cache::remember('system_rsa_keys', 3600, function () {
            $pub  = CryptoKey::where('owner_type', 'system')
                             ->where('key_type', 'rsa_public')
                             ->whereNull('rotated_at')
                             ->latest()->first();

            $priv = CryptoKey::where('owner_type', 'system')
                             ->where('key_type', 'rsa_private')
                             ->whereNull('rotated_at')
                             ->latest()->first();

            if (!$pub || !$priv) {
                return $this->generateAndStoreSystemRSA();
            }

            // Integrity check on keys themselves
            if (!$this->verifyKeyIntegrity($pub) || !$this->verifyKeyIntegrity($priv)) {
                \Log::critical('RSA KEY INTEGRITY FAILED — Generating new keys');
                return $this->generateAndStoreSystemRSA();
            }

            return [
                'public_key'  => json_decode($pub->key_data,  true),
                'private_key' => json_decode($priv->key_data, true),
            ];
        });
    }

    // -------------------------------------------------------
    // ECC KEYS (for posts / vaccine data)
    // -------------------------------------------------------

    public function getSystemECCKeys(): array
    {
        return Cache::remember('system_ecc_keys', 3600, function () {
            $pub  = CryptoKey::where('owner_type', 'system')
                             ->where('key_type', 'ecc_public')
                             ->whereNull('rotated_at')
                             ->latest()->first();

            $priv = CryptoKey::where('owner_type', 'system')
                             ->where('key_type', 'ecc_private')
                             ->whereNull('rotated_at')
                             ->latest()->first();

            if (!$pub || !$priv) {
                return $this->generateAndStoreSystemECC();
            }

            if (!$this->verifyKeyIntegrity($pub) || !$this->verifyKeyIntegrity($priv)) {
                \Log::critical('ECC KEY INTEGRITY FAILED — Generating new keys');
                return $this->generateAndStoreSystemECC();
            }

            return [
                'public_key'  => json_decode($pub->key_data,  true),
                'private_key' => json_decode($priv->key_data, true),
            ];
        });
    }

    // -------------------------------------------------------
    // KEY ROTATION
    // -------------------------------------------------------

    /**
     * Rotate all system keys
     * Mark old ones as rotated, generate new ones
     */
    public function rotateSystemKeys(): void
    {
        CryptoKey::where('owner_type', 'system')
                 ->whereNull('rotated_at')
                 ->update(['rotated_at' => now()]);

        $this->generateAndStoreSystemRSA();
        $this->generateAndStoreSystemECC();

        Cache::forget('system_rsa_keys');
        Cache::forget('system_ecc_keys');

        \Log::info('System keys rotated at: ' . now());
    }

    /**
     * Check if keys need rotation (older than 30 days)
     */
    public function needsRotation(): bool
    {
        $oldestKey = CryptoKey::where('owner_type', 'system')
                              ->whereNull('rotated_at')
                              ->oldest()
                              ->first();

        if (!$oldestKey) return true;

        return $oldestKey->created_at->diffInDays(now()) > 30;
    }

    // -------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------

    private function generateAndStoreSystemRSA(): array
    {
        $keys = $this->rsa->generateKeyPair();

        $pubData  = json_encode($keys['public_key']);
        $privData = json_encode($keys['private_key']);

        CryptoKey::create([
            'owner_type' => 'system',
            'owner_id'   => null,
            'key_type'   => 'rsa_public',
            'key_data'   => $pubData,
            'key_hash'   => hash('sha256', $pubData),
            'expires_at' => now()->addDays(30),
        ]);

        CryptoKey::create([
            'owner_type' => 'system',
            'owner_id'   => null,
            'key_type'   => 'rsa_private',
            'key_data'   => $privData,
            'key_hash'   => hash('sha256', $privData),
            'expires_at' => now()->addDays(30),
        ]);

        return $keys;
    }

    private function generateAndStoreSystemECC(): array
    {
        $keys = $this->ecc->generateKeyPair();

        $pubData  = json_encode($keys['public_key']);
        $privData = json_encode(['private_key' => $keys['private_key']]);

        CryptoKey::create([
            'owner_type' => 'system',
            'owner_id'   => null,
            'key_type'   => 'ecc_public',
            'key_data'   => $pubData,
            'key_hash'   => hash('sha256', $pubData),
            'expires_at' => now()->addDays(30),
        ]);

        CryptoKey::create([
            'owner_type' => 'system',
            'owner_id'   => null,
            'key_type'   => 'ecc_private',
            'key_data'   => $privData,
            'key_hash'   => hash('sha256', $privData),
            'expires_at' => now()->addDays(30),
        ]);

        return $keys;
    }

    private function verifyKeyIntegrity(CryptoKey $key): bool
    {
        $computed = hash('sha256', $key->key_data);
        return hash_equals($computed, $key->key_hash);
    }
}