<?php

namespace Modules\Market\Entities;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Market\Database\factories\MarketFactory;
use Modules\Market\Enums\MarketStatus;

class Market extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'symbol',
        'price',
        'status',
        'price_updated_at',
    ];

    protected $casts = [
        'price'            => 'decimal:0',
        'price_updated_at' => 'datetime',
    ];

    protected static function newFactory()
    {
        return app(MarketFactory::class);
    }

    public function isEnabled()
    {
        return $this->status === MarketStatus::Enabled->name;
    }
}
