<?php

namespace App\Services\Crypto;

/**
 * Custom Password Hashing 

 */
class CustomHash
{
    /**
     *  random salt
     * 64 hex chars = 32 bytes random
     */
    public function generateSalt(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Hash password with salt 
     *
     .hexdigest()
     *
     
     
     
    
     */
    public function hashPassword(string $password, string $salt): string
    {
        
        $round1 = hash('sha256', $password);

        
        $round2 = hash('sha256', $round1 . $salt);

        
        $round3 = hash('sha256', $salt . $round2 . strrev($salt));

        return $round3; // 64 hex chars
    }


    public function verify(string $password, string $storedHash, string $salt): bool
    {
        $computed = $this->hashPassword($password, $salt);
        return hash_equals($storedHash, $computed);
    }

    /**
     
     *  sha256_hash = hashlib.sha256(text.encode()).hexdigest()
     */
    public function quickHash(string $data): string
    {
        return hash('sha256', strtolower(trim($data)));
    }
}