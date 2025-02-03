<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Matches;
use App\Models\Player;
use App\Models\Tournament;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matches>
 */
class MatchesFactory extends Factory
{
    protected $model = Matches::class;

    public function definition()
    {
        return [
            'tournament_id' => Tournament::factory(),
            'player1_id' => Player::factory(),
            'player2_id' => Player::factory(),
            'winner_id' => function (array $attributes) {
                // Asignar aleatoriamente el ganador entre los dos jugadores
                return $this->faker->randomElement([$attributes['player1_id'], $attributes['player2_id']]);
            },
            'round' => $this->faker->numberBetween(1, 5),
        ];
    }
}
