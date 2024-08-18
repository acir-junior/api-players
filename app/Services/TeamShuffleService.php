<?php

namespace App\Services;

use App\Models\Player;
use Exception;

class TeamShuffleService
{
    public function shuffle($players, $teamCount, $playersTeam)
    {
        // Separar goleiros e jogadores de linha
        $goalkeepers = $players->filter(fn($player) => $player->is_goalkeeper);
        $fieldPlayers = $players->reject(fn($player) => $player->is_goalkeeper);

        // Validar quantidade mínima de goleiros
        if ($goalkeepers->count() > 0 && $goalkeepers->count() > $teamCount) {
            throw new Exception('Número insuficiente de goleiros.');
        }

        // Balancear times considerando níveis
        $teams = collect(range(1, $teamCount))->mapWithKeys(fn($i) => [$i => collect()]);

        // Distribuir goleiros
        foreach ($goalkeepers as $index => $goalkeeper) {
            $teams[$index % $teamCount + 1]->push($goalkeeper);
        }

        // Distribuir jogadores de linha
        $fieldPlayers->sortByDesc('level')->values()->each(function ($player, $index) use (&$teams, $teamCount, $playersTeam) {
            $teamIndex = $index % $teamCount;

            // Certifique-se de que o time ainda pode adicionar mais jogadores
            if ($teams[$teamIndex + 1]->count() < $playersTeam) {
                $teams[$teamIndex + 1]->push($player);
            }
        });

        return $teams;
    }
}
