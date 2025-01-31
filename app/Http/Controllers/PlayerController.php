<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        return Player::all();
    }

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

    public function show($id)
    {
        return Player::findOrFail($id);
    }

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

    public function destroy($id)
    {
        $player = Player::findOrFail($id);
        $player->delete();
        return response()->json(null, 204);
    }
}
