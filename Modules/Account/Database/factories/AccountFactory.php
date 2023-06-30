<?php

namespace Modules\Account\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Account\Entities\Account::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'          => $this->faker->name,
            'account_number' => $this->faker->creditCardNumber,
            'account_name'   => $this->faker->name,
            'sheba_number'   => $this->faker->swiftBicNumber,
        ];
    }
}

