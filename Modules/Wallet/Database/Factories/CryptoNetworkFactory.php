<?php

namespace Modules\Wallet\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CryptoNetworkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Wallet\Entities\CryptoNetwork::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

