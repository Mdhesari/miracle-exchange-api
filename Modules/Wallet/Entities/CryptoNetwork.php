<?php

namespace Modules\Wallet\Entities;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Wallet\Database\factories\CryptoNetworkFactory;

class CryptoNetwork extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', 'is_active', 'fee',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
    ];

    protected static function newFactory(): CryptoNetworkFactory
    {
        return CryptoNetworkFactory::new();
    }
}
