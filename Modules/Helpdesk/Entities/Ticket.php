<?php

namespace Modules\Helpdesk\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;
use Mdhesari\LaravelQueryFilters\Contracts\Expandable;
use Mdhesari\LaravelQueryFilters\Contracts\HasFilters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mdhesari\LaravelQueryFilters\Traits\HasExpandScope;
use Modules\Helpdesk\Database\Factories\TicketFactory;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Ticket extends Model implements HasFilters, Expandable, AuditableContract
{
    use SoftDeletes, HasFactory, HasExpandScope, Auditable, HasUuids;

    protected $fillable = [
        'subject', 'department', 'notes', 'status', 'user_id', 'number',
    ];

    protected $appends = [
        'available_status',
        'status_trans',
    ];

    const STATUS_PENDING_ADMIN = 'pending-admin';
    const STATUS_PENDING_USER = 'pending-user';
    const STATUS_CLOSED = 'closed';

    public static function getAvailableStatus(): array
    {
        return [
            static::STATUS_PENDING_ADMIN,
            static::STATUS_PENDING_USER,
            static::STATUS_CLOSED,
        ];
    }

    public function scopeUnreadAdmin($query)
    {
        return $query->whereStatus(self::STATUS_PENDING_ADMIN)->whereHas('messages', function ($query) {
            $query->whereIsRead(false);
        });
    }

    public function scopeUnreadUser($query, $user_id)
    {
        return $query->whereStatus(self::STATUS_PENDING_USER)->where('user_id', $user_id)->whereHas('messages', function ($query) {
            $query->whereIsRead(false);
        });
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
        return $this->belongsTo(User::class);
    }

    public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getSearchParams(): array
    {
        return [
            'subject', 'department', 'user.first_name', 'user.last_name', 'id'
        ];
    }

    public function getExpandRelations(): array
    {
        return [
            'messages', 'user',
        ];
    }

    protected static function newFactory()
    {
        return app(TicketFactory::class);
    }
}
