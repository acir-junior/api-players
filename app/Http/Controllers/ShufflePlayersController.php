<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Services\TeamShuffleService;
use Illuminate\Http\Request;

class ShufflePlayersController extends Controller
{
    public function shufflePlayers(Request $request)
    {
        $confirmedPlayers = Player::confirmed()->get();
        $teamsCount = $request->input('team_count');
        $playersTeam = $request->input('players_team');
        
        if ($confirmedPlayers->count() < $playersTeam * $teamsCount) {
            return response()->json([
                'error' => 'Número insuficiente de jogadores confirmados.'
            ], 400);
        }

        $teams = app(TeamShuffleService::class)->shuffle($confirmedPlayers, $teamsCount, $playersTeam);
        return response()->json($teams);
    }
}
