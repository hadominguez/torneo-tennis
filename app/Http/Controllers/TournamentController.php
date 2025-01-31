<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Matches;
use App\Models\Tournament;
use App\Services\TournamentService;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    protected $service;

    protected $tournamentService;

    public function __construct(TournamentService $tournamentService)
    {
        $this->tournamentService = $tournamentService;
    }
    public function index()
    {
        return Tournament::all();
    }

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

    public function show($id)
    {
        return Tournament::findOrFail($id);
    }

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

    public function destroy($id)
    {
        $tournament = Tournament::findOrFail($id);
        $tournament->delete();
        return response()->json(null, 204);
    }

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
