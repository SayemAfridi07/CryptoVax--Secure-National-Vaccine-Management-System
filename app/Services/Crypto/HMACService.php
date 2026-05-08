<?php

namespace App\Services\Crypto;

/**
 * HMAC Data Integrity Service
 *
 *  (CBC-MAC ):
 *   c = cmac.CMAC(algorithms.TripleDES(key))
 *   c.update(message_bytes)
 *   signature = c.finalize()
 *
 *   Verify:
 *   if signature.hex() == given_signature: print("Valid")
 *   else: print("Invalid")
 *
 * PHP HMAC-SHA256 = 
 * Both generate a fixed-size tag from message + key
 */
class HMACService
{
    private string $secretKey;

    public function __construct()
    {
        // Use app key as HMAC secret base
        $appKey = config('app.key', 'default-hmac-secret-key');
        // Derive HMAC key from app key (same idea as key derivation)
        $this->secretKey = hash('sha256', $appKey . 'hmac-purpose');
    }

    /**
     * Generate MAC tag for data
     *
     * 
     *   c = cmac.CMAC(algorithms.TripleDES(key))
     *   c.update(message_bytes)
     *   signature = c.finalize()
     *   print(binascii.b2a_hex(signature).decode())
     */
    public function generate(array $data): string
    {
        ksort($data); // consistent ordering
        $message = json_encode($data);
        return hash_hmac('sha256', $message, $this->secretKey);
    }

    /**
     * Verify MAC tag
     *
     * 
     *   if signature.hex() == given_signature: "Valid"
     *   else: "Invalid"
     */
    public function verify(array $data, string $storedTag): bool
    {
        $computed = $this->generate($data);
        return hash_equals($computed, $storedTag); // constant time
    }

    /**
     * Generate MAC tag specifically for user data fields
     */
    public function generateForUser(array $userData): string
    {
        return $this->generate([
            'name'  => $userData['name']  ?? '',
            'email' => $userData['email'] ?? '',
            'nid'   => $userData['nid']   ?? '',
            'phone' => $userData['phone'] ?? '',
            'dob'   => $userData['dob']   ?? '',
        ]);
    }

    /**
     * Verify user data integrity
     
     */
    public function verifyUserData(array $userData, string $storedTag): bool
    {
        $computed = $this->generateForUser($userData);

        if (!hash_equals($computed, $storedTag)) {
            \Log::warning('Data integrity check FAILED for user: ' . ($userData['email'] ?? 'unknown'));
            return false;
        }

        return true;
    }
}