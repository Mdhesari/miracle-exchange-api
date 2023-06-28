<?php

namespace Modules\Wallet\Actions\Transaction;

use Mdhesari\LaravelQueryFilters\Abstract\BaseQueryFilters;

class ApplyTransactionQueryFilters extends BaseQueryFilters
{
    public function __invoke($query, array $data)
    {
        parent::__invoke($query, $data);

        if ( isset($data['with_trashed']) && $data['with_trashed'] ) {
            $query->withTrashed();
        }

        if ( $this->user()->cannot('transactions') ) {
            $query->where('user_id', $this->user()->id);
        }

        if ( isset($data['wallet_id']) ) {
            $query->where('wallet_id', $data['wallet_id']);
        }

        if ( isset($data['transactionable_id']) ) {
            $query->where('transactionable_id', $data['transactionable_id']);
        }

        if ( isset($data['number']) ) {
            $query->where('id', $data['number']);
        }

        if ( isset($data['quantity']) ) {
            $query->where('quantity', '>=', $data['quantity']);
        }

        if ( isset($data['type']) ) {
            $query->whereType($data['type']);
        }

        if ( isset($data['status']) ) {
            $query->whereStatus($data['status']);
        }

        return $query;
    }
}
