<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ToDo>
 */
class ToDoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text(128),
            'subtitle' => fake()->realTextBetween(15, 256),
            'text' => fake()->realTextBetween(15, 1024),
//            'due_date'=>fake()->dateTime('tomorrow'),
        ];
    }
}
