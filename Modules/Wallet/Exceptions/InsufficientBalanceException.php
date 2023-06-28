<?php

namespace Modules\Wallet\Exceptions;

use Exception;
use Modules\Wallet\Entities\Transaction;
use Throwable;

class InsufficientBalanceException extends Exception
{
    protected $code = 402;

    private $quantity;

    private Transaction $transaction;

    public function __construct(Transaction $transaction, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->quantity = $transaction->quantity;
        $this->transaction = $transaction;
        parent::__construct(__('wallet::transaction.insufficientBalance').' '.$message);
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }
}
