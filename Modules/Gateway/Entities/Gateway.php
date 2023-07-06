<?php

namespace Modules\Gateway\Entities;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Gateway\Database\factories\GatewayFactory;

class Gateway extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'title', 'account_number', 'account_name', 'sheba_number', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function newFactory()
    {
        return app(GatewayFactory::class);
    }
}
