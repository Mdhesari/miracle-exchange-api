<?php

namespace Modules\Wallet\Entities;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;
use Mdhesari\LaravelQueryFilters\Contracts\Expandable;
use Mdhesari\LaravelQueryFilters\Contracts\HasFilters;
use Mdhesari\LaravelQueryFilters\Traits\HasExpandScope;
use Modules\Wallet\Database\Factories\WalletFactory;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Wallet extends Model implements HasFilters, Expandable, AuditableContract
{
    use HasFactory, SoftDeletes, HasExpandScope, Auditable, HasUuids;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'quantity', 'status', 'user_id', 'currency',
    ];

    protected $casts = [
        'quantity' => 'decimal:0',
    ];

    protected $appends = [
        'formatted_qua',
        'available_status',
        'status_trans',
    ];

    public static function getAvailableStatus()
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_INACTIVE,
        ];
    }

    public function getAvailableStatusAttribute()
    {
        return self::getAvailableStatus();
    }

    public function getStatusTransAttribute()
    {
        return Lang::has($key = 'status.general.'.$this->status) ? Lang::get($key) : $this->status;
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(config('wallet.users.model'));
    }

    public function scopeActive($query)
    {
        return $query->whereStatus(self::STATUS_ACTIVE);
    }

    public function scopeInActive($query)
    {
        return $query->whereStatus(self::STATUS_INACTIVE);
    }

    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function deposit($data): Model
    {
        return $this->transactions()->create([
            'type'        => Transaction::TYPE_DEPOSIT,
            'quantity'    => $data['quantity'],
            'status'      => $data['status'] ?? Transaction::STATUS_PENDING,
            'verified_at' => $data['verified_at'] ?? null,
            'reference'   => $data['reference'] ?? null,
        ]);
    }

    public function getFormattedQuaAttribute()
    {
        return \Modules\Wallet\Wallet::formatMoney($this->quantity);
    }

    public function withdraw(array $data): Model
    {
        $data = array_replace($data, [
            'type'        => Transaction::TYPE_WITHDRAW,
            'quantity'    => $data['quantity'],
            'status'      => $data['status'] ?? Transaction::STATUS_PENDING,
            'verified_at' => $data['verified_at'] ?? null,
            'reference'   => $data['reference'] ?? null,
        ]);

        if (isset($data['transactionable'])) {
            $data = array_replace($data, [
                'transactionable_id'   => $data['transactionable']->id,
                'transactionable_type' => get_class($data['transactionable']),
            ]);
        }

        return $this->transactions()->create($data);
    }

    public function chargeWallet(mixed $quantity): bool
    {
        return $this->update([
            'quantity' => $this->quantity + $quantity,
        ]);
    }

    public function dischargeWallet(mixed $quantity): bool
    {
        return $this->update([
            'quantity' => $this->quantity - $quantity,
        ]);
    }

    protected static function newFactory()
    {
        return new WalletFactory;
    }

    public function getSearchParams(): array
    {
        return [
            'user.name',
        ];
    }

    public function getExpandRelations(): array
    {
        return [
            'transactions',
        ];
    }
}
