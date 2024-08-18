<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerRequest;
use App\Models\Player;
use App\Services\TeamShuffleService;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    protected $shuffleService;

    public function __construct(TeamShuffleService $shuffleService)
    {
        $this->shuffleService = $shuffleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $players = Player::all();
        $confirmedPlayers = Player::confirmed()->get();
        $teams = $this->shuffleService->shuffle($confirmedPlayers, 2, 5);

        return view('teams.index', compact('players', 'teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlayerRequest $request)
    {
        Player::create($request->all());
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlayerRequest $request, Player $player)
    {
        $player->update($request->validate());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function confirm(Player $player)
    {
        $player->update(['is_confirmed' => true]);

        return redirect()->back();
    }
}
