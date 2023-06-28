<?php

namespace Modules\Wallet\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Mdhesari\LaravelQueryFilters\Contracts\Expandable;
use Mdhesari\LaravelQueryFilters\Traits\HasExpandScope;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class TransactionTransactionable extends Model implements AuditableContract, Expandable
{
    use Auditable, HasExpandScope;

    protected $fillable = [];

    protected $table = 'transaction_transactionable';

    protected $with = [
        'transactionable',
    ];

    public function transactionable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function getExpandRelations(): array
    {
        return [
            'transactionable',
        ];
    }
}
