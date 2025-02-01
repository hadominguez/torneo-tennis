<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Match",
 *     type="object",
 *     required={"tournament_id", "player1_id", "player2_id", "winner_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="tournament_id", type="integer", example=1),
 *     @OA\Property(property="player1_id", type="integer", example=1),
 *     @OA\Property(property="player2_id", type="integer", example=2),
 *     @OA\Property(property="winner_id", type="integer", example=1),
 *     @OA\Property(property="round", type="integer", example=1, nullable=true)
 * )
 */
class MatchController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/matches",
     *     summary="Obtiene todos los partidos",
     *     operationId="getMatches",
     *     tags={"Matches"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de partidos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Match"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function index()
    {
        return Matches::all();
    }

    /**
     * @OA\Post(
     *     path="/api/matches",
     *     summary="Crea un nuevo partido",
     *     operationId="storeMatch",
     *     tags={"Matches"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"tournament_id", "player1_id", "player2_id", "winner_id"},
     *             @OA\Property(property="tournament_id", type="integer", example=1),
     *             @OA\Property(property="player1_id", type="integer", example=1),
     *             @OA\Property(property="player2_id", type="integer", example=2),
     *             @OA\Property(property="winner_id", type="integer", example=1),
     *             @OA\Property(property="round", type="integer", example=1, nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Partido creado",
     *         @OA\JsonContent(ref="#/components/schemas/Match")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Solicitud incorrecta"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tournament_id' => 'required|integer|exists:tournaments,id',
            'player1_id' => 'required|integer|exists:players,id',
            'player2_id' => 'required|integer|exists:players,id',
            'winner_id' => 'integer|exists:players,id|nullable',
            'round' => 'integer|nullable',
        ]);
    
        // Obtener el torneo relacionado
        $tournament = Tournament::findOrFail($validatedData['tournament_id']);
        
        // Obtener los jugadores
        $player1 = Player::findOrFail($validatedData['player1_id']);
        $player2 = Player::findOrFail($validatedData['player2_id']);
        
        // Verificar que los géneros de los jugadores coincidan con el género del torneo
        if ($player1->gender !== $tournament->gender || $player2->gender !== $tournament->gender) {
            return response()->json(['error' => 'Los jugadores no coinciden con el género del torneo'], 400);
        }
    
        // Crear el partido
        $match = Matches::create($validatedData);
    
        return response()->json($match, 201);
    }    

    /**
     * @OA\Get(
     *     path="/api/matches/{id}",
     *     summary="Obtiene un partido por ID",
     *     operationId="getMatch",
     *     tags={"Matches"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del partido",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del partido",
     *         @OA\JsonContent(ref="#/components/schemas/Match")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Partido no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        return Matches::findOrFail($id);
    }

    /**
     * @OA\Put(
     *     path="/api/matches/{id}",
     *     summary="Actualiza un partido existente",
     *     operationId="updateMatch",
     *     tags={"Matches"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del partido a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="tournament_id", type="integer", example=1),
     *             @OA\Property(property="player1_id", type="integer", example=1),
     *             @OA\Property(property="player2_id", type="integer", example=2),
     *             @OA\Property(property="winner_id", type="integer", example=1),
     *             @OA\Property(property="round", type="integer", example=1, nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Partido actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/Match")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Partido no encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $match = Matches::findOrFail($id);
        $validatedData = $request->validate([
            'tournament_id' => 'integer|exists:tournaments,id',
            'player1_id' => 'integer|exists:players,id',
            'player2_id' => 'integer|exists:players,id',
            'winner_id' => 'integer|exists:players,id',
            'round' => 'integer|nullable',
        ]);
    
        // Obtener el torneo relacionado
        $tournament = Tournament::findOrFail($validatedData['tournament_id'] ?? $match->tournament_id);
        
        // Obtener los jugadores
        $player1 = Player::findOrFail($validatedData['player1_id'] ?? $match->player1_id);
        $player2 = Player::findOrFail($validatedData['player2_id'] ?? $match->player2_id);
        
        // Verificar que los géneros de los jugadores coincidan con el género del torneo
        if ($player1->gender !== $tournament->gender || $player2->gender !== $tournament->gender) {
            return response()->json(['error' => 'Los jugadores no coinciden con el género del torneo'], 400);
        }
    
        // Actualizar el partido
        $match->update($validatedData);
    
        return response()->json($match, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/matches/{id}",
     *     summary="Elimina un partido",
     *     operationId="deleteMatch",
     *     tags={"Matches"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del partido a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Partido eliminado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Partido no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $match = Matches::findOrFail($id);
        $match->delete();
        return response()->json(null, 204);
    }
}
