<?php

namespace Modules\Market\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MarketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Market\Entities\Market::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'symbol' => $this->faker->name(),
            'price'  => rand(1000, 9999999),
        ];
    }
}

