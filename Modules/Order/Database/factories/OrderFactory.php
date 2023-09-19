<?php

namespace Modules\Order\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Market\Entities\Market;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Order\Entities\Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'market_id'                          => ($market = Market::factory()->create([
                'price' => 100,
            ])),
            'original_cumulative_quote_quantity' => 100,
            'cumulative_quote_quantity'          => 100 * 100,
            'executed_quantity'                  => 100,
            'original_market_price'              => 100,
            'executed_price'                     => 100,
            'fill_percentage'                    => 0,
        ];
    }
}

