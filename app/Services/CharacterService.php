<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use App\Models\Character;

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
            'hitpoints' => $request->hitpoints
        ]));

        return response()->json([
            'message' => 'Your character has been created!!'
        ], 200);
    }

    //update gold, silver and farmpoints
    public function update($data)
    {
        
    }

    public function show($id)
    {
        $hashValue = "character_" . $id;
        return  json_decode(Redis::get($hashValue));
    }

    public function retrieveResources()
    {
        return "ok";
    }

    public function retrieveMaxHp($id)
    {
        return "ok";
    }
}