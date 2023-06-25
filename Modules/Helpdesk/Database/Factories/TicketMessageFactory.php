<?php

namespace Modules\Helpdesk\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Helpdesk\Entities\TicketMessage;

class TicketMessageFactory extends Factory
{
    protected $model = TicketMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'message' => $this->faker->text(),
        ];
    }
}
