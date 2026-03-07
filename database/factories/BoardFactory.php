<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Board>
 */
class BoardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'sort' => 1,
        ];
    }

    /**
     * Set the group id for the board.
     */
    public function forGroup(int $groupId): static
    {
        return $this->state(fn (array $attributes) => [
            'group_id' => $groupId,
        ]);
    }
}
