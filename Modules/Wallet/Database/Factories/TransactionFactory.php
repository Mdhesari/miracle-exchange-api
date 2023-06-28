<?php

namespace Modules\Wallet\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Modules\Wallet\Entities\Transaction;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'quantity' => rand(3000, 9000),
            'type'     => Arr::random(Transaction::getAvailableTypes()),
            'status'   => Arr::random(Transaction::getAvailableStatus()),
        ];
    }
}
