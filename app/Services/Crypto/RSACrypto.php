<?php

namespace App\Services\Crypto;

/**
 * RSA Encryption 
 *
 * 
 *    p = sympy.randprime(2**127, 2**128)
 *    n = p * q
 *    phi_n = (p-1)(q-1)
 *    e = 11 → while sympy.gcd(e, phi_n) != 1: e += 2
 *    d = pow(e, -1, phi_n)
 *    c = pow(m, e, n)   ← encrypt
 *    m = pow(c, d, n)   ← decrypt
 *    bytes.fromhex(hex(m)[2:]).decode('utf-8')
 *
 * PHP GMP replaces Python's sympy 
 */
class RSACrypto
{
    // Max bytes per chunk (safe to encrypt with 128-bit n)
    private const CHUNK_SIZE = 6;

    // ---------------------------------------------------------------
    // KEY GENERATION
    // ---------------------------------------------------------------

    /**
     * Generate RSA key pair
     *
     * 
     *   p = sympy.randprime(2**127, 2**128)
     *   q = sympy.randprime(2**127, 2**128)
     *   n = p * q
     *   phi_n = (p-1)*(q-1)
     *   e = 11
     *   while sympy.gcd(e, phi_n) != 1: e += 2
     *   d = pow(e, -1, phi_n)
     */
    public function generateKeyPair(): array
    {
        // Generate two 64-bit primes (lab used 128-bit; we use 64-bit each → n = 128-bit)
        $p = $this->randomPrime(64);
        $q = $this->randomPrime(64);

        // n = p * q
        $n = gmp_mul($p, $q);

        // phi(n) = (p-1)(q-1)
        $phi_n = gmp_mul(
            gmp_sub($p, gmp_init(1)),
            gmp_sub($q, gmp_init(1))
        );

        // e 
        $e = gmp_init(65537);
        while (gmp_cmp(gmp_gcd($e, $phi_n), gmp_init(1)) !== 0) {
            $e = gmp_add($e, gmp_init(2));
        }

        // d = modular inverse of e ( d = pow(e, -1, phi_n))
        $d = gmp_invert($e, $phi_n);

        return [
            'public_key'  => [
                'e' => gmp_strval($e),
                'n' => gmp_strval($n),
            ],
            'private_key' => [
                'd' => gmp_strval($d),
                'n' => gmp_strval($n),
            ],
        ];
    }

    // ---------------------------------------------------------------
    // ENCRYPTION
    // ---------------------------------------------------------------

    /**
     * Encrypt a plaintext string
     *
     *
     *   m = int("Secret".encode("utf-8").hex(), 16)
     *   c = pow(m, e, n)
     *
     * We split into 6-byte chunks so each chunk < n
     */
    public function encrypt(string $plaintext, array $publicKey): string
    {
        $e = gmp_init($publicKey['e']);
        $n = gmp_init($publicKey['n']);

        $chunks = str_split($plaintext, self::CHUNK_SIZE);
        $encryptedChunks = [];

        foreach ($chunks as $chunk) {
            // Convert chunk to integer ( int(hex, 16))
            $hex = bin2hex($chunk);
            $m   = gmp_init($hex, 16);

            // c = m^e mod n  
            $c = gmp_powm($m, $e, $n);
            $encryptedChunks[] = gmp_strval($c);
        }

        // Join chunks with | and base64 encode for safe DB storage
        return base64_encode(implode('|', $encryptedChunks));
    }

    // ---------------------------------------------------------------
    // DECRYPTION
    // ---------------------------------------------------------------

    /**
     * Decrypt ciphertext back to plaintext string
     *
     * 
     *   m = pow(c, d, n)
     *   bytes.fromhex(hex(m)[2:]).decode('utf-8')
     */
    public function decrypt(string $ciphertext, array $privateKey): string
    {
        $d = gmp_init($privateKey['d']);
        $n = gmp_init($privateKey['n']);

        // Decode base64, split chunks
        $decoded = base64_decode($ciphertext);
        $chunks  = explode('|', $decoded);
        $plaintext = '';

        foreach ($chunks as $chunk) {
            $c = gmp_init(trim($chunk));

            // m = c^d mod n 
            $m = gmp_powm($c, $d, $n);

            // Convert back to string ( bytes.fromhex(hex(m)[2:]))
            $hex = gmp_strval($m, 16);
            if (strlen($hex) % 2 !== 0) {
                $hex = '0' . $hex; // pad to even length
            }
            $plaintext .= hex2bin($hex);
        }

        return $plaintext;
    }

    // ---------------------------------------------------------------
    // HELPER: Random Prime Generator
    // ---------------------------------------------------------------

    /**
     * Generate random prime of $bits bits
     * sympy.randprime(2**127, 2**128)
     */
    private function randomPrime(int $bits): \GMP
    {
        do {
            // Generate random $bits-bit number
            $num = gmp_random_bits($bits);

            // Set MSB to ensure it is exactly $bits long ( min_val = 2**127)
            $num = gmp_or($num, gmp_pow(gmp_init(2), $bits - 1));

            // Set LSB to ensure it is odd
            $num = gmp_or($num, gmp_init(1));

        } while (gmp_prob_prime($num) === 0); // 0 = not prime

        return $num;
    }
}