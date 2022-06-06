<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use App\Services\CharacterService;

class LeaderboardService
{
    protected CharacterService $characterService;

    public function __construct(CharacterService $characterService)
    {
        $this->characterService = $characterService;
    }

    public function show()
{
        //Create the player in the leaderboard
        //Redis::zadd('leaders:loot', 5, 1);
        //Redis::zadd('leaders:loot', 10, 2);
        //Redis::zadd('leaders:loot', 3, 3);

        $leaderboard = Redis::zrevrange('leaders:loot', 0, 9, 'WITHSCORES');

        //Wanna see ids and the loot value?
        //Uncomment this
        return response()->json([
            $leaderboard
        ], 200);

        //Wanna see the ID's and the name??
        //Uncomment this
        // foreach ($leaderboard as $key => $value)
        // {
        //     $player = $this->characterService->find($key);
        //     $leaderboard[$key] = $player->name;
        // }

        // return response()->json([
        //     $leaderboard
        // ], 200);
    }
}