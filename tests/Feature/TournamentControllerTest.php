<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tournament;
use App\Models\Player;

class TournamentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test para obtener todos los torneos.
     *
     * @return void
     */
    public function test_can_get_all_tournaments()
    {
        // Crear 5 torneos
        Tournament::factory()->count(5)->create();

        // Realizar la petición GET
        $response = $this->get('/api/tournaments');

        // Verificar que la respuesta sea exitosa y devuelva los 5 torneos
        $response->assertStatus(200);
        $response->assertJsonCount(5);  // Verifica que haya 5 elementos en la respuesta
    }

    /**
     * Test para obtener un torneo por su ID.
     *
     * @return void
     */
    public function test_can_get_tournament_by_id()
    {
        // Crear un torneo
        $tournament = Tournament::factory()->create();

        // Realizar la petición GET por ID
        $response = $this->get("/api/tournaments/{$tournament->id}");

        // Verificar que la respuesta sea exitosa y contiene los datos del torneo
        $response->assertStatus(200);
        $response->assertJson([
            'id' => $tournament->id,
            'name' => $tournament->name,
            'gender' => $tournament->gender,
            'winner_id' => $tournament->winner_id,
        ]);
    }

    /**
     * Test para crear un nuevo torneo.
     *
     * @return void
     */
    public function test_can_create_tournament()
    {
        // Datos para crear un torneo
        $data = [
            'name' => 'Torneo de Ejemplo',
            'gender' => 'M',
        ];

        // Realizar la petición POST para crear un torneo
        $response = $this->post('/api/tournaments', $data);

        // Verificar que la respuesta sea exitosa
        $response->assertStatus(201);
        $response->assertJson($data);

        // Verificar que el torneo fue realmente creado en la base de datos
        $this->assertDatabaseHas('tournaments', $data);
    }

    /**
     * Test para actualizar un torneo.
     *
     * @return void
     */
    public function test_can_update_tournament()
    {
        $player = Player::factory()->create();
        // Crear un torneo
        $tournament = Tournament::factory()->create();

        // Datos actualizados
        $data = [
            'name' => 'Torneo Actualizado',
            'gender' => 'M',
            'winner_id' => $player->id,
        ];

        // Realizar la petición PUT para actualizar el torneo
        $response = $this->put("/api/tournaments/{$tournament->id}", $data);

        // Verificar que la respuesta sea exitosa
        $response->assertStatus(200);
        $response->assertJson($data);

        // Verificar que los datos fueron actualizados en la base de datos
        $this->assertDatabaseHas('tournaments', $data);
    }

    /**
     * Test para eliminar un torneo.
     *
     * @return void
     */
    public function test_can_delete_tournament()
    {
        // Crear un torneo
        $tournament = Tournament::factory()->create();

        // Realizar la petición DELETE para eliminar el torneo
        $response = $this->delete("/api/tournaments/{$tournament->id}");

        // Verificar que la respuesta sea exitosa
        $response->assertStatus(204);

        // Verificar que el torneo fue eliminado de la base de datos
        $this->assertDatabaseMissing('tournaments', ['id' => $tournament->id]);
    }
}
