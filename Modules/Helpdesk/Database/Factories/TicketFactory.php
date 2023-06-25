<?php

namespace Modules\Helpdesk\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Helpdesk\Entities\Ticket;

class TicketFactory extends Factory
{

    protected $model = Ticket::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'subject' => $this->faker->sentence(),
            'department' => $this->faker->sentence(),
            'notes' => $this->faker->text(),
            'status' => Ticket::STATUS_PENDING_ADMIN,
        ];
    }
}
