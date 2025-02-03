<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Player;

class PlayerControllerTest extends TestCase
{
    use RefreshDatabase; // Asegurar que la base de datos se reinicie antes de cada prueba

    public function it_can_create_a_player()
    {
        // Datos para el nuevo jugador
        $data = [
            'name' => 'Juan',
            'gender' => 'M',
            'skill_level' => 95,
            'strength' => 85,
            'speed' => 90,
            'reaction_time' => 92
        ];

        // Realizamos la petición POST para crear el jugador
        $response = $this->postJson('/api/players', $data);

        // Verificamos que el jugador ha sido creado correctamente
        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Juan']);
    }

    public function it_can_get_all_players()
    {
        // Crear algunos jugadores
        $player1 = Player::factory()->create();
        $player2 = Player::factory()->create();

        // Realizar una solicitud GET para obtener todos los jugadores
        $response = $this->getJson('/api/players');

        // Verificar que la respuesta contiene los jugadores creados
        $response->assertStatus(200)
                 ->assertJsonCount(2); // Esperamos 2 jugadores
    }

    public function it_can_show_a_player_by_id()
    {
        // Crear un jugador
        $player = Player::factory()->create();

        // Realizar una solicitud GET para obtener el jugador por ID
        $response = $this->getJson("/api/players/{$player->id}");

        // Verificar que la respuesta contiene el jugador correcto
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => $player->name]);
    }

    public function it_can_update_a_player()
    {
        // Crear un jugador
        $player = Player::factory()->create();

        // Datos para actualizar el jugador
        $data = [
            'name' => 'Martin',
            'gender' => 'M',
            'skill_level' => 90,
            'strength' => 80,
            'speed' => 88,
            'reaction_time' => 91
        ];

        // Realizar la solicitud PUT para actualizar el jugador
        $response = $this->putJson("/api/players/{$player->id}", $data);

        // Verificar que la respuesta tiene el nuevo nombre
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Martin']);
    }

    public function it_can_delete_a_player()
    {
        // Crear un jugador
        $player = Player::factory()->create();

        // Realizar la solicitud DELETE para eliminar el jugador
        $response = $this->deleteJson("/api/players/{$player->id}");

        // Verificar que el jugador fue eliminado
        $response->assertStatus(204);

        // Verificar que ya no existe en la base de datos
        $this->assertDatabaseMissing('players', ['id' => $player->id]);
    }
}
