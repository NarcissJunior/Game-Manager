<?php

namespace App\Services;

use App\Jobs\ProcessBattle;
use App\Services\CharacterService;

class BattleService
{
    protected CharacterService $characterService;

    public function __construct(CharacterService $characterService)
    {
        $this->characterService = $characterService;
    }

    public function register($request)
    {
        
        $attacker = $this->characterService->find($request['attacker']);
        $defender = $this->characterService->find($request['defender']);
        
        ProcessBattle::dispatch($attacker, $defender);

        return response()->json([
            'message' => 'You are registering a new battle!!'
        ], 200);
    }

    public function updateLeaderboard($player, $amount)
    {
        
    }
}