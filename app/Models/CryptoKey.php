<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoKey extends Model
{
    protected $fillable = [
        'owner_type',
        'owner_id',
        'key_type',
        'key_data',
        'key_hash',
        'rotated_at',
        'expires_at',
    ];

    protected $casts = [
        'rotated_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}