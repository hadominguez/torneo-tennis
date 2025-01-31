<?php

namespace App\Http\Controllers;

use App\Models\Matches;
use App\Models\Player;
use App\Models\Tournament;
use Illuminate\Http\Request;
class MatchController extends Controller
{
    public function index()
    {
        return Matches::all();
    }

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

    public function show($id)
    {
        return Matches::findOrFail($id);
    }

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

    public function destroy($id)
    {
        $match = Matches::findOrFail($id);
        $match->delete();
        return response()->json(null, 204);
    }    
    }
