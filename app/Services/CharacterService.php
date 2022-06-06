<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class CharacterService
{
    public function __construct()
    {
        
    }

    public function create($request)
    {
        //save in DB
        $hashValue = "character_" . $request->id;
        Redis::set($hashValue, json_encode([
            'id' => $request->id,
            'name' => $request->name,
            'description' => $request->description,
            'gold' => $request->gold,
            'silver' => $request->silver,
            'attack' => $request->attack,
            'luck' => $request->luck,
            'hitpoints' => $request->hitpoints,
            'goldLoot' => 0,
            'silverLoot' => 0
        ]));

        //Create the player in the leaderboard
        $index = 'leaders:loot';
        Redis::zadd($index, 0, $request->id);

        return response()->json([
            'message' => 'Your character has been created!!'
        ], 200);
    }

    public function rankPosition($id)
    {
        $rankPosition = Redis::zrevrank('leaders:loot', $id);
        $rankPosition++;
        $player = $this->find($id);
        $message = "The position of " . $player->name . " in the rank is " . $rankPosition . ".";

        return response()->json([
            'message' => $message
        ], 200);
    }

    public function find($id)
    {
        $hashValue = "character_" . $id;
        return json_decode(Redis::get($hashValue));
    }
}