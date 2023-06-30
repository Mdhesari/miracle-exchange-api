<?php

namespace Modules\Gateway\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Gateway\Database\factories\GatewayFactory;

class Gateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'account_number', 'sheba_number',
    ];

    protected static function newFactory()
    {
        return app(GatewayFactory::class);
    }
}
