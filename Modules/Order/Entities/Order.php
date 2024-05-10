<?php

namespace Modules\Order\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;
use Mdhesari\LaravelQueryFilters\Contracts\Expandable;
use Mdhesari\LaravelQueryFilters\Traits\HasExpandScope;
use Modules\Account\Entities\Account;
use Modules\Market\Entities\Market;
use Modules\Order\Database\factories\OrderFactory;
use Modules\Order\Enums\OrderStatus;
use Modules\Wallet\Traits\HasTransaction;

class Order extends Model implements Expandable
{
    use HasFactory, HasExpandScope, HasTransaction, HasUuids;

    protected $fillable = [
        'market_id',
        'user_id',
        'account_id',
        'original_market_price',
        'executed_price',
        'executed_quantity',
        'cumulative_quote_quantity',
        'original_cumulative_quote_quantity',
        'fill_percentage',
        'status',
    ];

    protected $casts = [
        'original_market_price'              => 'decimal:0',
        'executed_price'                     => 'decimal:0',
        'executed_quantity'                  => 'decimal:2',
        'cumulative_quote_quantity'          => 'decimal:0',
        'original_cumulative_quote_quantity' => 'decimal:0',
        'fill_percentage'                    => 'decimal:0',
    ];

    protected $appends = [
        'formatted_executed_price',
        'formatted_executed_quantity',
        'fromatted_cumulative_quote_quantity',
        'status_trans',
        'available_status',
    ];

    public function getStatusTransAttribute()
    {
        return Lang::has($key = 'order::status.'.$this->status) ? __($key) : $this->status;
    }

    public function getAvailableStatusAttribute(): array
    {
        return array_column(OrderStatus::cases(), 'name');
    }

    public function getFormattedExecutedPriceAttribute()
    {
        return number_format($this->executed_price);
    }

    public function getFormattedExecutedQuantityAttribute()
    {
        return number_format($this->executed_quantity);
    }

    public function getFromattedCumulativeQuoteQuantityAttribute()
    {
        return number_format($this->cumulative_quote_quantity);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function market(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function getExpandRelations(): array
    {
        return [
            'user', 'market', 'account'
        ];
    }

    protected static function newFactory()
    {
        return app(OrderFactory::class);
    }

    public function getOwner()
    {
        return $this->user->full_name;
    }

    public function toUsdt(): int
    {
        $usdtMarket = Market::select('price')->whereSymbol('usdt')->first();

        return round($this->cumulative_quote_quantity / $usdtMarket->price);
    }
}
