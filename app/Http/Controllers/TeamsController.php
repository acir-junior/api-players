<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Services\TeamShuffleService;

class TeamsController extends Controller
{
    protected $shufflePlayersService;

    public function __construct(TeamShuffleService $shufflePlayersService) {
        $this->shufflePlayersService = $shufflePlayersService;
    }

    public function index()
    {
        $players = Player::all();
        $confirmedPlayers = Player::confirmed()->get();
        $teams = $this->shufflePlayersService->shuffle($confirmedPlayers, 2, 5);

        return view('teams.index', compact('players', 'teams'));
    }
}
