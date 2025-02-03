<?php
namespace App\Utils;

use App\Models\Player;
use App\Models\Tournament;

class MatchSimulator {
    public function simulate(Tournament $tournament, Player $player1, Player $player2) {
        $luck = mt_rand(-10, 10);

        if ($tournament->gender === 'M') {
            // Torneo masculino: considera habilidad, fuerza y velocidad
            $skillFactor = $player1->skill_level - $player2->skill_level;
            $strengthFactor = $player1->strength - $player2->strength;
            $speedFactor = $player1->speed - $player2->speed;
            $totalFactor = ($skillFactor * 0.4) + ($strengthFactor * 0.3) + ($speedFactor * 0.3) + $luck;
        } else {
            // Torneo femenino: considera habilidad y tiempo de reacción
            $skillFactor = $player1->skill_level - $player2->skill_level;
            $reactionFactor = $player1->reaction_time - $player2->reaction_time;
            $totalFactor = ($skillFactor * 0.6) + ($reactionFactor * 0.4) + $luck;
        }

        return $totalFactor >= 0 ? $player1 : $player2;
    }
}