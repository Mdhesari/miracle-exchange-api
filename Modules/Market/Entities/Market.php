<?php

namespace Modules\Market\Entities;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mdhesari\LaravelQueryFilters\Contracts\Expandable;
use Mdhesari\LaravelQueryFilters\Contracts\HasFilters;
use Modules\Market\Database\factories\MarketFactory;
use Modules\Market\Enums\MarketStatus;
use Modules\Order\Entities\Order;

class Market extends Model implements Expandable, HasFilters
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'persian_name',
        'country_code',
        'symbol_char',
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

    public function isEnabled(): bool
    {
        return $this->status === MarketStatus::Enabled->name;
    }

    public function isDisabled(): bool
    {
        return $this->status === MarketStatus::Disabled->name;
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getExpandRelations(): array
    {
        return ['orders'];
    }

    public function getSearchParams(): array
    {
        return ['name', 'symbol'];
    }
}
