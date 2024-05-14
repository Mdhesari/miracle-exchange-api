<?php

namespace Modules\Market\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Mdhesari\LaravelQueryFilters\Contracts\Expandable;
use Mdhesari\LaravelQueryFilters\Contracts\HasFilters;
use Mdhesari\LaravelQueryFilters\Traits\HasExpandScope;
use Modules\Market\Database\factories\MarketFactory;
use Modules\Market\Enums\MarketStatus;
use Modules\Order\Entities\Order;
use Modules\Wallet\Entities\CryptoNetwork;

class Market extends Model implements Expandable, HasFilters
{
    use HasFactory, HasUuids, HasExpandScope;

    protected $fillable = [
        'name',
        'description',
        'persian_name',
        'country_code',
        'symbol_char',
        'symbol',
        'profit_price',
        'price',
        'status',
        'is_crypto',
        'price_updated_at',
        'meta',
    ];

    protected $casts = [
        'is_crypto'        => 'boolean',
        'price'            => 'decimal:0',
        'profit_price'     => 'decimal:0',
        'price_updated_at' => 'datetime',
        'meta'             => 'array',
    ];

    protected $with = [
        'cryptoNetworks',
    ];

    protected $appends = [
        'total_price',
        'is_bookmarked',
    ];

    public static function getUsdtIrtLatestPrice()
    {
        return Cache::remember('usdt_irt_latest_price', now()->addHours(4), fn() => static::select('price')->whereSymbol('usdt')->first()->price);
    }

    public function getTotalPriceAttribute()
    {
        return $this->price + ($this->profit_price ?: 0);
    }

    public function getIsBookmarkedAttribute()
    {
        return Auth::guest() ? false : $this->users()->whereUserId(Auth::id())->exists();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function cryptoNetworks(): BelongsToMany
    {
        return $this->belongsToMany(CryptoNetwork::class);
    }

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

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getExpandRelations(): array
    {
        return ['orders', 'cryptoNetworks'];
    }

    public function getSearchParams(): array
    {
        return ['name', 'symbol'];
    }

    public function toggle(): static
    {
        $this->isEnabled() ? $this->disable() : $this->enable();

        return $this;
    }

    private function disable(): bool
    {
        return $this->update([
            'status' => MarketStatus::Disabled->name,
        ]);
    }

    private function enable(): bool
    {
        return $this->update([
            'status' => MarketStatus::Enabled->name,
        ]);
    }

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(MarketPrice::class);
    }

    public function getOwner()
    {
        return $this->name;
    }
}
