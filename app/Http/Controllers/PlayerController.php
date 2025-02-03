<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Player",
 *     type="object",
 *     required={"id", "name", "team", "gender", "skill_level"},
 *     @OA\Property(property="id", type="integer", description="ID del jugador"),
 *     @OA\Property(property="name", type="string", description="Nombre del jugador"),
 *     @OA\Property(property="team", type="string", description="Equipo del jugador"),
 *     @OA\Property(property="gender", type="string", description="Género del jugador (M o F)", enum={"M", "F"}),
 *     @OA\Property(property="skill_level", type="integer", description="Nivel de habilidad del jugador (0-100)"),
 *     @OA\Property(property="strength", type="integer", description="Fuerza del jugador (0-100)", nullable=true),
 *     @OA\Property(property="speed", type="integer", description="Velocidad del jugador (0-100)", nullable=true),
 *     @OA\Property(property="reaction_time", type="integer", description="Tiempo de reacción del jugador (0-100)", nullable=true)
 * )
 */
class PlayerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/players",
     *     summary="Obtener todos los jugadores",
     *     description="Devuelve una lista de todos los jugadores",
     *     tags={"Player"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de jugadores",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Player")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return Player::all();
    }

    /**
     * @OA\Post(
     *     path="/api/players",
     *     summary="Crear un nuevo jugador",
     *     description="Crea un nuevo jugador con los datos proporcionados",
     *     tags={"Player"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Player")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Jugador creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Player")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:M,F',
            'skill_level' => 'required|integer|between:0,100',
            'strength' => 'nullable|integer|between:0,100',
            'speed' => 'nullable|integer|between:0,100',
            'reaction_time' => 'nullable|integer|between:0,100',
        ]);

        $player = Player::create($validatedData);
        return response()->json($player, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/players/{id}",
     *     summary="Obtener un jugador por ID",
     *     description="Devuelve un jugador específico basado en su ID",
     *     tags={"Player"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del jugador",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Jugador encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Player")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Jugador no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        return Player::findOrFail($id);
    }

    /**
     * @OA\Put(
     *     path="/api/players/{id}",
     *     summary="Actualizar un jugador",
     *     description="Actualiza un jugador existente con los datos proporcionados",
     *     tags={"Player"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del jugador",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Player")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Jugador actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Player")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Jugador no encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $player = Player::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'gender' => 'string|in:M,F',
            'skill_level' => 'integer|between:0,100',
            'strength' => 'nullable|integer|between:0,100',
            'speed' => 'nullable|integer|between:0,100',
            'reaction_time' => 'nullable|integer|between:0,100',
        ]);

        $player->update($validatedData);
        return response()->json($player, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/players/{id}",
     *     summary="Eliminar un jugador",
     *     description="Elimina un jugador basado en su ID",
     *     tags={"Player"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del jugador",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Jugador eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Jugador no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $player = Player::findOrFail($id);
        $player->delete();
        return response()->json(null, 204);
    }
}
