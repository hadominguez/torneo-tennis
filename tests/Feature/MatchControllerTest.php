<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Matches;
use App\Models\Player;
use App\Models\Tournament;

class MatchControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test para obtener todos los partidos.
     *
     * @return void
     */
    public function test_can_get_all_matches()
    {
        // Crear algunos partidos de prueba
        $match = Matches::factory()->create();

        // Hacer la solicitud GET a la ruta de index
        $response = $this->getJson('/api/matches');

        // Verificar que la respuesta es correcta
        $response->assertStatus(200);
        $response->assertJsonCount(1); // Verifica que la respuesta tenga al menos un partido
        $response->assertJsonFragment([
            'id' => $match->id,
        ]);
    }

    /**
     * Test para crear un nuevo partido.
     *
     * @return void
     */
    public function test_can_create_match()
    {
        // Crear los jugadores y el torneo necesarios
        $player1 = Player::factory()->create();
        $data = [
            'name' => 'Juan',
            'gender' => 'M',
            'skill_level' => 90,
            'strength' => 80,
            'speed' => 88,
            'reaction_time' => 91
        ];
        $response = $this->putJson("/api/players/{$player1->id}", $data);

        $player2 = Player::factory()->create();
        $data2 = [
            'name' => 'Martin',
            'gender' => 'M',
            'skill_level' => 87,
            'strength' => 90,
            'speed' => 79,
            'reaction_time' => 95
        ];
        $response = $this->putJson("/api/players/{$player2->id}", $data2);

        $tournament = Tournament::factory()->create();
        $data3 = [
            'name' => 'Torneo de Tenis',
            'gender' => 'M',
        ];
        $response = $this->put("/api/tournaments/{$tournament->id}", $data3);

        // Datos del nuevo partido
        $data = [
            'tournament_id' => $tournament->id,
            'player1_id' => $player1->id,
            'player2_id' => $player2->id,
            'winner_id' => $player1->id,
            'round' => 1,
        ];

        // Hacer la solicitud POST a la ruta store
        $response = $this->postJson('/api/matches', $data);

        // Verificar que el partido fue creado
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'tournament_id' => $tournament->id,
            'player1_id' => $player1->id,
            'player2_id' => $player2->id,
            'winner_id' => $player1->id,
            'round' => 1,
        ]);
    }

    /**
     * Test para obtener un partido específico.
     *
     * @return void
     */
    public function test_can_get_single_match()
    {
        // Crear un partido de prueba
        $match = Matches::factory()->create();

        // Hacer la solicitud GET a la ruta show
        $response = $this->getJson("/api/matches/{$match->id}");

        // Verificar que la respuesta contiene los datos del partido
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $match->id,
        ]);
    }

    /**
     * Test para actualizar un partido.
     *
     * @return void
     */
    public function test_can_update_match()
    {
        // Crear un partido de prueba
        $match = Matches::factory()->create();

        // Crear los jugadores y el torneo necesarios
        $player1 = Player::factory()->create();
        $data = [
            'name' => 'Juan',
            'gender' => 'M',
            'skill_level' => 90,
            'strength' => 80,
            'speed' => 88,
            'reaction_time' => 91
        ];
        $response = $this->putJson("/api/players/{$player1->id}", $data);
        
        $player2 = Player::factory()->create();
        $data2 = [
            'name' => 'Martin',
            'gender' => 'M',
            'skill_level' => 87,
            'strength' => 90,
            'speed' => 79,
            'reaction_time' => 95
        ];
        $response = $this->putJson("/api/players/{$player2->id}", $data2);

        $tournament = Tournament::factory()->create();
        $data3 = [
            'name' => 'Torneo de Tenis',
            'gender' => 'M',
        ];
        $response = $this->put("/api/tournaments/{$tournament->id}", $data3);

        // Datos para actualizar el partido
        $data = [
            'tournament_id' => $tournament->id,
            'player1_id' => $player1->id,
            'player2_id' => $player2->id,
            'winner_id' => $player2->id, // Cambiar el ganador
            'round' => 2,
        ];

        // Hacer la solicitud PUT a la ruta update
        $response = $this->putJson("/api/matches/{$match->id}", $data);

        // Verificar que el partido fue actualizado
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'winner_id' => $player2->id,
            'round' => 2,
        ]);
    }

    /**
     * Test para eliminar un partido.
     *
     * @return void
     */
    public function test_can_delete_match()
    {
        // Crear un partido de prueba
        $match = Matches::factory()->create();

        // Hacer la solicitud DELETE a la ruta destroy
        $response = $this->deleteJson("/api/matches/{$match->id}");

        // Verificar que el partido fue eliminado
        $response->assertStatus(204);

        // Verificar que el partido ya no está en la base de datos
        $this->assertDatabaseMissing('matches', [
            'id' => $match->id,
        ]);
    }
}
