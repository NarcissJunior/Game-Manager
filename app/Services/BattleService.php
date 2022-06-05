<?php

namespace App\Services;

use App\Jobs\ProcessBattle;
use App\Models\Character;

class BattleService
{
    public function __construct()
    {
    }

    public function register($request)
    {
        $character = new Character;
        $attacker = $character->findOrFail($request['attacker']);
        $defender = $character->findOrFail($request['defender']);

        ProcessBattle::dispatch($attacker, $defender);

        return response()->json([
            'message' => 'You are registering a new battle!!'
        ], 200);
    }
}