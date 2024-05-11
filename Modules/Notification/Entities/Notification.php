<?php

namespace Modules\Notification\Entities;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;
use Mdhesari\LaravelQueryFilters\Contracts\HasFilters;
use Modules\Notification\Enums\NotificationStatus;

class Notification extends Model implements HasFilters
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'title',
        'notifiable_type',
        'notifiable_id',
        'message',
        'status',
        'read_at',
        'sends_at',
    ];

    protected $casts = [
        'read_at'  => 'datetime',
        'sends_at' => 'datetime',
    ];

    protected $appends = [
        'status_trans',
        'available_status',
    ];

    public function getStatusTransAttribute()
    {
        return Lang::has($key = 'notification::status.'.$this->status) ? __($key) : $this->status;
    }

    public function getAvailableStatusAttribute(): array
    {
        return array_column(NotificationStatus::cases(), 'name');
    }

    protected static function newFactory()
    {
        return \Modules\Notification\Database\factories\NotificationFactory::new();
    }

    public function getSearchParams(): array|string
    {
        return ['title'];
    }

    public function isScheduled(): bool
    {
        return $this->status === NotificationStatus::Scheduled->name;
    }

    public function read()
    {
        return $this->forceFill([
            'read_at' => now(),
        ])->save();
    }
}
