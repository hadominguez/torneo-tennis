<?php

namespace App\Services;

use App\Models\Matches;
use App\Models\Player;
use App\Models\Tournament;
use App\Utils\MatchSimulator;
use Illuminate\Support\Collection;

class TournamentService
{
    protected $matchSimulator;

    public function __construct(MatchSimulator $matchSimulator)
    {
        $this->matchSimulator = $matchSimulator;
    }

    public function runTournament(Tournament $tournament)
    {
        $currentRound = 1;

        while (true) {
            // Obtener los partidos de la ronda actual
            $matches = Matches::where('tournament_id', $tournament->id)
                ->where('round', $currentRound)
                ->get();

            if ($matches->isEmpty()) {
                throw new \Exception("No hay partidos para la ronda $currentRound.");
            }

            $newRoundPlayers = collect();

            foreach ($matches as $match) {
                error_log("Procesando partido: {$match->id} - Ronda {$match->round}");

                $player1 = Player::find($match->player1_id);
                $player2 = Player::find($match->player2_id);

                if (!$player1 || !$player2) {
                    throw new \Exception("Uno de los jugadores en el partido {$match->id} es inválido.");
                }

                error_log("Player1: {$player1->name}");
                error_log("Player2: {$player2->name}");

                // Simular el partido
                $winner = $this->matchSimulator->simulate($tournament, $player1, $player2);

                // Guardar el ganador en la base de datos
                $match->winner_id = $winner->id;
                $match->save();

                error_log("Ganador del partido {$match->id}: {$winner->name}");

                $newRoundPlayers->push($winner);
            }

            // Si solo queda un jugador, es el ganador del torneo
            if ($newRoundPlayers->count() === 1) {
                error_log("🏆 Torneo finalizado. Ganador: " . $newRoundPlayers->first()->name);
                $tournament->update(['winner_id' => $newRoundPlayers->first()->id]);
                return $newRoundPlayers->first();
            }

            // Generar nueva ronda
            $nextRound = $currentRound + 1;
            $pairs = $newRoundPlayers->chunk(2);

            foreach ($pairs as $pair) {
                if ($pair->count() < 2) {
                    throw new \Exception("Error: Número impar de jugadores en la ronda $nextRound.");
                }

                Matches::create([
                    'tournament_id' => $tournament->id,
                    'player1_id' => $pair[0]->id,
                    'player2_id' => $pair[1]->id,
                    'round' => $nextRound,
                ]);
            }

            // Avanzar de ronda
            $currentRound++;
        }
    }


    private function isPowerOfTwo($number)
    {
        return ($number & ($number - 1)) == 0 && $number > 0;
    }
}
