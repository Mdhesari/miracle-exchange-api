<?php

namespace Modules\Wallet\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Lang;
use Mdhesari\LaravelQueryFilters\Contracts\Expandable;
use Mdhesari\LaravelQueryFilters\Contracts\HasFilters;
use Mdhesari\LaravelQueryFilters\Traits\HasExpandScope;
use Modules\Revenue\Traits\Revenuable;
use Modules\Wallet\Database\Factories\TransactionFactory;
use Modules\Wallet\Services\Audit\Traits\UuidAudit;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class Transaction extends Model implements HasFilters, Expandable, AuditableContract, HasMedia
{
    use HasFactory, HasExpandScope, HasUuids, UuidAudit, InteractsWithMedia, Revenuable;

    /*
     * Status
     */
    const STATUS_PENDING = 'pending';
    const STATUS_GATEWAY_PENDING = 'gateway-pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ADMIN_PENDING = 'admin-pending';
    const STATUS_EXPIRED = 'expired';

    /*
     * Types
     */
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAW = 'withdraw';

    /*
     * Media
     */
    const MEDIA_REFERENCE = 'reference';

    protected $fillable = [
        'transactionable_id', 'currency', 'wallet_id', 'crypto_wallet_hash', 'crypto_network_id', 'transactionable_type', 'gateway_id', 'quantity', 'status', 'type', 'user_id', 'admin_id', 'meta', 'reference', 'gateway', 'callback_url',
    ];

    protected $casts = [
        'quantity' => 'decimal:0',
        'meta'     => 'array',
    ];

    protected $appends = [
        'available_status',
        'status_trans',
        'type_trans',
        'payer',
        'receiver',
    ];

    public static function getAvailableStatus(): array
    {
        return [
            static::STATUS_PENDING,
            static::STATUS_GATEWAY_PENDING,
            static::STATUS_ADMIN_PENDING,
            static::STATUS_VERIFIED,
            static::STATUS_REJECTED,
            static::STATUS_EXPIRED,
        ];
    }

    public function getTypeTransAttribute()
    {
        return __('wallet::transaction.types.'.$this->type);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isGatewayPending(): bool
    {
        return $this->status === self::STATUS_GATEWAY_PENDING;
    }

    public function verify(?string $reference = null, int $admin_id = null): bool
    {
        return $this->update([
            'status'    => self::STATUS_VERIFIED,
            'admin_id'  => $admin_id,
            'reference' => $reference ?: $this->reference,
        ]);
    }

    public function reject(?string $reject_reason = null, int $admin_id = null): bool
    {
        return $this->update([
            'status'   => self::STATUS_REJECTED,
            'admin_id' => $admin_id,
            'meta'     => [
                'reject_reason' => $reject_reason,
            ]
        ]);
    }

    public function getAvailableStatusAttribute()
    {
        return self::getAvailableStatus();
    }

    public function getStatusTransAttribute()
    {
        return Lang::has($key = 'status.transaction.'.$this->status) ? Lang::get($key) : $this->status;
    }

    public static function getAvailableTypes(): array
    {
        return [
            static::TYPE_DEPOSIT,
            static::TYPE_WITHDRAW,
        ];
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function scopePending($query)
    {
        return $query->whereStatus(self::STATUS_PENDING);
    }

    public function scopeGatewayPending($query)
    {
        return $query->whereStatus(self::STATUS_GATEWAY_PENDING);
    }

    public function scopeRejected($query)
    {
        return $query->whereStatus(self::STATUS_REJECTED);
    }

    public function scopeDeposit($query)
    {
        return $query->whereType(self::TYPE_DEPOSIT);
    }

    public function scopeWithdraw($query)
    {
        return $query->whereType(self::TYPE_WITHDRAW);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedQuaAttribute(): string
    {
        return \Modules\Wallet\Wallet::formatMoney($this->quantity);
    }

    public function getSearchParams(): array
    {
        return [
            'user.first_name', 'user.last_name', 'reference',
        ];
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory()
    {
        return new TransactionFactory;
    }

    public function getExpandRelations(): array
    {
        return [
            'transactionable', 'user', 'media',
        ];
    }

    public function isWithdraw(): bool
    {
        return $this->type === self::TYPE_WITHDRAW;
    }

    public function isDeposit(): bool
    {
        return $this->type === self::TYPE_DEPOSIT;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isVerified(): bool
    {
        return $this->status === Transaction::STATUS_VERIFIED;
    }

    public function isAdminPending()
    {
        return $this->status === Transaction::STATUS_ADMIN_PENDING;
    }

    public function isExpiredTime()
    {
        return $this->expires_at?->lessThan(now());
    }

    public function hasGateway(): bool
    {
        return ! is_null($this->gateway);
    }

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::MEDIA_REFERENCE)->acceptsMimeTypes([
            'image/jpg', 'image/jpeg', 'image/png'
        ]);
    }

    /**
     * @param mixed $file
     * @return mixed
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function addReferenceMedia(mixed $file): mixed
    {
        return $this->addMedia($file)->toMediaCollection(self::MEDIA_REFERENCE);
    }

    public function getPayerAttribute()
    {
        return $this->type === self::TYPE_WITHDRAW ? $this->getUserName() : $this->getTransactionableName();
    }

    public function getReceiverAttribute()
    {
        return $this->type === self::TYPE_DEPOSIT ? $this->getUserName() : $this->getTransactionableName();
    }

    private function getUserName(): string
    {
        return $this->user ? ($this->user?->getSubject() ?: ' - ') : ($this->admin?->getSubject() ?: ' - ');
    }

    private function getTransactionableName(): string
    {
        return $this->admin_id ? ($this->admin?->getSubject() ?: ' admin ') : ($this->transactionable?->getOwner() ?: $this->admin?->getSubject()) ?? ' admin ';
    }
}
