<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Thread>
 */
class ThreadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'sort' => 1,
        ];
    }

    /**
     * Set the board id for the thread.
     */
    public function forBoard(int $boardId): static
    {
        return $this->state(fn (array $attributes) => [
            'board_id' => $boardId,
        ]);
    }
}
