<?php

namespace Modules\Order\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mdhesari\LaravelQueryFilters\Contracts\Expandable;
use Mdhesari\LaravelQueryFilters\Traits\HasExpandScope;
use Modules\Market\Entities\Market;
use Modules\Order\Database\factories\OrderFactory;
use Modules\Wallet\Traits\Transactionable;

class Order extends Model implements Expandable
{
    use HasFactory, HasExpandScope, Transactionable;

    protected $fillable = [
        'market_id',
        'user_id',
        'original_market_price',
        'executed_price',
        'executed_quantity',
        'cumulative_quote_quantity',
        'fill_percentage',
        'status',
    ];

    protected $casts = [
        'original_market_price'     => 'decimal:0',
        'executed_price'            => 'decimal:0',
        'executed_quantity'         => 'decimal:2',
        'cumulative_quote_quantity' => 'decimal:0',
        'fill_percentage'           => 'decimal:0',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function market(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Market::class);
    }

    public function getExpandRelations(): array
    {
        return [
            'user', 'market', 'transactions',
        ];
    }

    protected static function newFactory()
    {
        return app(OrderFactory::class);
    }
}
