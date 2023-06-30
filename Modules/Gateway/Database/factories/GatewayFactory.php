<?php

namespace Modules\Gateway\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GatewayFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Gateway\Entities\Gateway::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'          => $this->faker->sentence,
            'account_number' => $this->faker->creditCardNumber,
            'account_name'   => $this->faker->name,
            'sheba_number'   => $this->faker->swiftBicNumber,
            'is_active'      => true,
        ];
    }
}

