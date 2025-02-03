<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Player;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'gender' => $this->faker->randomElement(['M', 'F']),
            'skill_level' => $this->faker->numberBetween(1, 100),
            'strength' => $this->faker->numberBetween(1, 100),
            'speed' => $this->faker->numberBetween(1, 100),
            'reaction_time' => $this->faker->numberBetween(1, 100),
        ];
    }
}
