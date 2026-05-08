<?php

namespace App\Services\Crypto;

/**
 * ECC Encryption
 *
 
 *    curve: y^2 = x^3 - 2x + 2 (mod p)   ← same curve, larger prime
 *    def inverse_mod(k, p): return pow(k, -1, p)
 *    def point_add(P, Q, a, p): ...
 *    def scalar_mult(k, P, a, p): R=None, while k>0 ...
 *   ECDH: shared_A = scalar_mult(a, B_pub) = scalar_mult(b, A_pub) = shared_B
 *
 * Encryption method: ECDH shared key + XOR cipher
 *   
 *
 * Curve: y^2 = x^3 - 2x + 2 (mod 2147483647)
 * Prime: p = 2^31 - 1 = 2147483647 (Mersenne prime)
 * Base point G = (1, 1) [valid: 1^2 = 1^3 - 2*1 + 2 = 1 ✓ for any prime p > 2]
 */
class ECCCrypto
{
    private \GMP $p;         // prime modulus 
    private \GMP $curve_a;   // curve param a 
    private \GMP $curve_b;   // curve param b 
    private array $G;        // base point   

    public function __construct()
    {
        //  (y^2 = x^3 - 2x + 2), larger prime
        $this->p       = gmp_init('2147483647'); // 2^31 - 1 (Mersenne prime)
        $this->curve_a = gmp_init('-2');          // same as lab
        $this->curve_b = gmp_init('2');           // same as lab
        // G = (1,1): valid since 1^2 = 1 and 1^3 - 2*1 + 2 = 1 ✓
        $this->G = [gmp_init('1'), gmp_init('1')];
    }

    // ---------------------------------------------------------------
    // MODULAR ARITHMETIC HELPERS
    // ---------------------------------------------------------------

    /**
     * Modular inverse
     *  def inverse_mod(k, p): return pow(k, -1, p)
     */
    private function inverseMod(\GMP $k, \GMP $p): \GMP
    {
        return gmp_invert($k, $p);
    }

    /**
     * Safe positive modulo (handles negative numbers)
     */
    private function mod(\GMP $a, \GMP $p): \GMP
    {
        $r = gmp_mod($a, $p);
        if (gmp_cmp($r, gmp_init(0)) < 0) {
            $r = gmp_add($r, $p);
        }
        return $r;
    }

    // ---------------------------------------------------------------
    // POINT OPERATIONS 
    // ---------------------------------------------------------------

    /**
     * Point addition on elliptic curve
     *
     * 
     *   def point_add(P, Q, a, p):
     *     if P is None: return Q
     *     if Q is None: return P
     *     x1,y1 = P; x2,y2 = Q
     *     if x1==x2 and (y1+y2)%p==0: return None
     *     if P==Q: m = ((3*x1**2+a)*inverse_mod(2*y1,p))%p
     *     else:    m = ((y2-y1)*inverse_mod(x2-x1,p))%p
     *     x_r = (m**2-x1-x2)%p
     *     y_r = (m*(x1-x_r)-y1)%p
     *     return (x_r, y_r)
     */
    public function pointAdd(?array $P, ?array $Q): ?array
    {
        // Lab: if P is None: return Q
        if ($P === null) return $Q;
        // Lab: if Q is None: return P
        if ($Q === null) return $P;

        [$x1, $y1] = $P;
        [$x2, $y2] = $Q;

        //  if x1==x2 and (y1+y2)%p==0: return None
        if (gmp_cmp($x1, $x2) === 0 &&
            gmp_cmp($this->mod(gmp_add($y1, $y2), $this->p), gmp_init(0)) === 0) {
            return null;
        }

        if (gmp_cmp($x1, $x2) === 0 && gmp_cmp($y1, $y2) === 0) {
            //  m = ((3*x1**2 + a) * inverse_mod(2*y1, p)) % p
            $num = $this->mod(
                gmp_add(gmp_mul(gmp_init(3), gmp_pow($x1, 2)), $this->curve_a),
                $this->p
            );
            $den = $this->mod(gmp_mul(gmp_init(2), $y1), $this->p);
            $m   = $this->mod(gmp_mul($num, $this->inverseMod($den, $this->p)), $this->p);
        } else {
            //  m = ((y2-y1) * inverse_mod(x2-x1, p)) % p
            $num = $this->mod(gmp_sub($y2, $y1), $this->p);
            $den = $this->mod(gmp_sub($x2, $x1), $this->p);
            $m   = $this->mod(gmp_mul($num, $this->inverseMod($den, $this->p)), $this->p);
        }

        //  x_r = (m**2 - x1 - x2) % p
        $x_r = $this->mod(gmp_sub(gmp_sub(gmp_pow($m, 2), $x1), $x2), $this->p);
        //  y_r = (m * (x1 - x_r) - y1) % p
        $y_r = $this->mod(gmp_sub(gmp_mul($m, gmp_sub($x1, $x_r)), $y1), $this->p);

        return [$x_r, $y_r];
    }

    /**
     * Scalar multiplication
    
     *   def scalar_mult(k, P, a, p):
     *     R = None   ← point at infinity
     *     Q = P
     *     while k > 0:
     *       if k % 2 == 1: R = point_add(R, Q, a, p)
     *       Q = point_add(Q, Q, a, p)
     *       k //= 2
     *     return R
     */
    public function scalarMult(\GMP $k, array $P): ?array
    {
        $R = null; //  R = None (point at infinity)
        $Q = $P;   //  Q = P

        //  while k > 0
        while (gmp_cmp($k, gmp_init(0)) > 0) {
            //  if k % 2 == 1: R = point_add(R, Q)
            if (gmp_cmp(gmp_mod($k, gmp_init(2)), gmp_init(1)) === 0) {
                $R = $this->pointAdd($R, $Q);
            }
            //  Q = point_add(Q, Q)
            $Q = $this->pointAdd($Q, $Q);
            // Lab: k //= 2
            $k = gmp_div($k, gmp_init(2));
        }

        return $R;
    }

    // ---------------------------------------------------------------
    // KEY GENERATION
    // ---------------------------------------------------------------

    /**
     * Generate ECC key pair
    
     *   a_secret = 3
     *   A_pub = scalar_mult(a_secret, P, a, p)
     */
    public function generateKeyPair(): array
    {
        // Private key: random number < p
        $private_key = gmp_add(gmp_random_bits(28), gmp_init(2));

        // Public key = private_key * G (lab: A_pub = scalar_mult(a_secret, P, a, p))
        $public_point = $this->scalarMult($private_key, $this->G);

        return [
            'private_key' => gmp_strval($private_key),
            'public_key'  => [
                'x' => gmp_strval($public_point[0]),
                'y' => gmp_strval($public_point[1]),
            ],
        ];
    }

    // ---------------------------------------------------------------
    // ECDH SHARED KEY 
    // ---------------------------------------------------------------

    /**
     * Compute ECDH shared secret
     *
     *   shared_A = scalar_mult(a_secret, B_pub, a, p)
     *   shared_B = scalar_mult(b_secret, A_pub, a, p)
     *   Both are equal! That's the shared secret.
     */
    public function computeSharedKey(string $privateKey, array $otherPublicKey): array
    {
        $k = gmp_init($privateKey);
        $P = [gmp_init($otherPublicKey['x']), gmp_init($otherPublicKey['y'])];

        $shared = $this->scalarMult($k, $P);

        return [
            'x' => gmp_strval($shared[0]),
            'y' => gmp_strval($shared[1]),
        ];
    }

    // ---------------------------------------------------------------
    // ENCRYPTION (ECDH + XOR )
    // ---------------------------------------------------------------

    /**
     * Encrypt using ECC + XOR cipher
     *
     * Combines:
     *   - ECC key exchange
     *   - XOR encryption (same concept as A5/1 :
     *       ciphertext = xor_binary(plain_bin, keystream))
     *
     * Flow:
     *   1. Generate ephemeral key pair
     *   2. ECDH: compute shared secret with receiver's public key
     *   3. Derive XOR key from shared point (SHA256)
     *   4. XOR encrypt plaintext with key
     *   5. Return ciphertext + ephemeral public key
     */
    public function encrypt(string $plaintext, array $receiverPublicKey): array
    {
        //  Ephemeral key pair (new random key per message)
        $ephemeral = $this->generateKeyPair();

        //  ECDH shared secret
        // L shared = scalar_mult(a_secret, B_pub)
        $shared = $this->computeSharedKey(
            $ephemeral['private_key'],
            $receiverPublicKey
        );

        //  Derive XOR key (hash shared x-coordinate)
        $keyBytes = hash('sha256', $shared['x'], true); // 32 bytes

        //  XOR encrypt (lab A5/1: ciphertext = xor_binary(plain_bin, keystream))
        $ciphertext = $this->xorCrypt($plaintext, $keyBytes);

        // Return ciphertext + ephemeral public key (receiver needs it to decrypt)
        return [
            'ciphertext'      => base64_encode($ciphertext),
            'ephemeral_pub_x' => $ephemeral['public_key']['x'],
            'ephemeral_pub_y' => $ephemeral['public_key']['y'],
        ];
    }

    /**
     * Decrypt using ECC + XOR
     *
     * XOR is its own inverse: XOR twice = original
     * (A5/1: decrypted_bin = xor_binary(ciphertext, keystream))
     */
    public function decrypt(array $encryptedData, string $receiverPrivateKey): string
    {
        // Reconstruct ephemeral public key
        $ephemeralPub = [
            'x' => $encryptedData['ephemeral_pub_x'],
            'y' => $encryptedData['ephemeral_pub_y'],
        ];

        // ECDH: compute same shared secret
        //  shared_B = scalar_mult(b_secret, A_pub) = shared_A ✓
        $shared = $this->computeSharedKey($receiverPrivateKey, $ephemeralPub);

        
        $keyBytes = hash('sha256', $shared['x'], true);

        // XOR decrypt (same operation as encrypt — XOR is symmetric)
        $ciphertext = base64_decode($encryptedData['ciphertext']);

        return $this->xorCrypt($ciphertext, $keyBytes);
    }

    // ---------------------------------------------------------------
    // XOR Cipher
    // ---------------------------------------------------------------

    /**
     * XOR encrypt/decrypt
     *
     *  A5/1:
     *   def xor_binary(a, b):
     *     return ''.join('0' if i==j else '1' for i,j in zip(a,b))
     *
     * 
     */
    private function xorCrypt(string $data, string $key): string
    {
        $result = '';
        $keyLen = strlen($key);

        for ($i = 0; $i < strlen($data); $i++) {
            $result .= chr(ord($data[$i]) ^ ord($key[$i % $keyLen]));
        }

        return $result;
    }

    // ---------------------------------------------------------------
    // UTILITY
    // ---------------------------------------------------------------

    public function getBasePoint(): array
    {
        return ['x' => gmp_strval($this->G[0]), 'y' => gmp_strval($this->G[1])];
    }

    public function pointToString(array $point): string
    {
        return json_encode([
            'x' => gmp_strval($point[0]),
            'y' => gmp_strval($point[1]),
        ]);
    }
}