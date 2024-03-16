<?php

namespace Modules\Comment\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Comment\Entities\Comment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'comment'          => $this->faker->sentence,
            'is_approved'      => true,
        ];
    }
}

