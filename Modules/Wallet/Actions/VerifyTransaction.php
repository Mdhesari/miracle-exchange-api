<?php

namespace Modules\Wallet\Actions;

use Modules\Wallet\Entities\Transaction;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class VerifyTransaction
{
    /**
     * @param Transaction $transaction
     * @param array $data
     * @return Transaction
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function __invoke(Transaction $transaction, array $data)
    {
        $transaction->verify($data['reference']);

        if ( isset($data['media']) ) {
            foreach ($data['media'] as $media) {
                $transaction->addReferenceMedia($media);
            }
        }

        return $transaction;
    }
}
