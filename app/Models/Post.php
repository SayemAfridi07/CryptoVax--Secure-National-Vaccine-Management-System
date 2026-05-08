<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\Crypto\RSACrypto;
use App\Services\Crypto\ECCCrypto;
use App\Services\Crypto\KeyManager;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title_encrypted', 'content_encrypted', 'mac_tag'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Smart Decryptor Helper
    private function smartDecrypt($encryptedData)
    {
        if (!$encryptedData) return '';
        try {
            $keyManager = new KeyManager();
            $rsa = new RSACrypto();
            $ecc = new ECCCrypto();
            
            $decoded = json_decode($encryptedData, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // ECC
                $keys = $keyManager->getSystemECCKeys();
                $priv = is_array($keys['private_key']) ? ($keys['private_key']['d'] ?? array_values($keys['private_key'])[0]) : $keys['private_key'];
                return $ecc->decrypt($decoded, (string)$priv);
            } else {
                // RSA
                $keys = $keyManager->getSystemRSAKeys();
                $priv = is_string($keys['private_key']) ? json_decode($keys['private_key'], true) : $keys['private_key'];
                return $rsa->decrypt($encryptedData, (array)$priv);
            }
        } catch (\Exception $e) {
            return "[Decryption Error]";
        }
    }

    // Virtual Attributes
    public function getTitleAttribute()
    {
        return $this->smartDecrypt($this->title_encrypted);
    }

    public function getContentAttribute()
    {
        return $this->smartDecrypt($this->content_encrypted);
    }
}