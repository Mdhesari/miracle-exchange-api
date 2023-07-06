<?php

namespace Modules\Account\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mdhesari\LaravelQueryFilters\Contracts\Expandable;
use Mdhesari\LaravelQueryFilters\Traits\HasExpandScope;
use Modules\Account\Database\factories\AccountFactory;

class Account extends Model implements Expandable
{
    use HasFactory, HasExpandScope, HasUuids;

    protected $fillable = [
        'title',
        'sheba_number',
        'account_number',
        'account_name',
        'user_id',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getExpandRelations(): array
    {
        return ['user'];
    }

    protected static function newFactory()
    {
        return app(AccountFactory::class);
    }
}
