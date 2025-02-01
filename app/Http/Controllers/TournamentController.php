<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Matches;
use App\Models\Tournament;
use App\Services\TournamentService;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Tournament",
 *     type="object",
 *     required={"name", "gender"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Torneo de Primavera"),
 *     @OA\Property(property="gender", type="string", example="M"),
 *     @OA\Property(property="winner_id", type="integer", example=1),
 * )
 */
class TournamentController extends Controller
{
    protected $service;

    protected $tournamentService;

    public function __construct(TournamentService $tournamentService)
    {
        $this->tournamentService = $tournamentService;
    }
    /**
     * @OA\Get(
     *     path="/api/tournaments",
     *     summary="Obtiene todos los torneos",
     *     operationId="getTournaments",
     *     tags={"Tournaments"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de torneos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Tournament"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error del servidor"
     *     )
     * )
     */
    public function index()
    {
        return Tournament::all();
    }

    /**
     * @OA\Post(
     *     path="/api/tournaments",
     *     summary="Crea un nuevo torneo",
     *     operationId="storeTournament",
     *     tags={"Tournaments"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "gender"},
     *             @OA\Property(property="name", type="string", example="Torneo de Primavera"),
     *             @OA\Property(property="gender", type="string", example="M")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Torneo creado",
     *         @OA\JsonContent(ref="#/components/schemas/Tournament")
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
            'name' => 'required|string|max:255',
            'gender' => 'required|string|in:M,F',
            'winner_id' => 'nullable|exists:players,id',
        ]);

        $tournament = Tournament::create($validatedData);
        return response()->json($tournament, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/tournaments/{id}",
     *     summary="Obtiene un torneo por ID",
     *     operationId="getTournament",
     *     tags={"Tournaments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del torneo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del torneo",
     *         @OA\JsonContent(ref="#/components/schemas/Tournament")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Torneo no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        return Tournament::findOrFail($id);
    }

    /**
     * @OA\Put(
     *     path="/api/tournaments/{id}",
     *     summary="Actualiza un torneo existente",
     *     operationId="updateTournament",
     *     tags={"Tournaments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del torneo a actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Torneo de Verano"),
     *             @OA\Property(property="gender", type="string", example="F")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Torneo actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/Tournament")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Torneo no encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $tournament = Tournament::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'gender' => 'string|in:M,F',
            'winner_id' => 'nullable|exists:players,id',
        ]);

        $tournament->update($validatedData);
        return response()->json($tournament, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/tournaments/{id}",
     *     summary="Elimina un torneo",
     *     operationId="deleteTournament",
     *     tags={"Tournaments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del torneo a eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Torneo eliminado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Torneo no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $tournament = Tournament::findOrFail($id);
        $tournament->delete();
        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/api/tournaments/{id}/start",
     *     summary="Inicia el torneo y determina el ganador",
     *     operationId="startTournament",
     *     tags={"Tournaments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del torneo a iniciar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Torneo finalizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Torneo finalizado"),
     *             @OA\Property(property="winner", type="string", example="Nombre del ganador")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Torneo no encontrado"
     *     )
     * )
     */
    public function startTournament($id)
    {
        $tournament = Tournament::findOrFail($id);

        if ($tournament->winner_id) {
            return response()->json([
                'message' => 'El torneo ya ha finalizado.',
                'winner' => Player::find($tournament->winner_id)->name
            ]);
        }

        $playersCount = Matches::where('tournament_id', $id)
                       ->whereNotNull('player1_id')
                       ->whereNotNull('player2_id')
                       ->count();

        // Verifica si hay suficientes jugadores
        if ($playersCount < 2) {
            return response()->json(['error' => 'No hay jugadores suficientes para comenzar el torneo.'], 400);
        }

        // Ejecuta el torneo usando el servicio apropiado
        $winner = $this->tournamentService->runTournament($tournament);

        return response()->json([
            'message' => 'Torneo finalizado',
            'winner' => $winner->name
        ]);
    }
}
