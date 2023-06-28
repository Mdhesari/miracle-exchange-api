<?php

namespace Modules\Wallet\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Modules\Wallet\Entities\Transaction;

trait Transactionable
{
    /**
     * Define a polymorphic many-to-many relationship.
     *
     * @param string $related
     * @param string $name
     * @param string|null $table
     * @param string|null $foreignPivotKey
     * @param string|null $relatedPivotKey
     * @param string|null $parentKey
     * @param string|null $relatedKey
     * @param bool $inverse
     * @return MorphToMany
     */
    abstract public function morphToMany($related, $name, $table = null, $foreignPivotKey = null,
                                         $relatedPivotKey = null, $parentKey = null,
                                         $relatedKey = null, $inverse = false);

    /**
     * Get transactions
     */
    public function transactions(): MorphToMany
    {
        return $this->morphToMany(Transaction::class, 'transactionable', 'transaction_transactionable')
            ->withPivot(['quantity']);
    }
}
