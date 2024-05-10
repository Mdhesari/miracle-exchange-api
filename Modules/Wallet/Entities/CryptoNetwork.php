<?php

namespace Modules\Wallet\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Database\factories\CryptoNetworkFactory;

class CryptoNetwork extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 'is_active',
    ];

    protected static function newFactory(): CryptoNetworkFactory
    {
        return CryptoNetworkFactory::new();
    }
}
