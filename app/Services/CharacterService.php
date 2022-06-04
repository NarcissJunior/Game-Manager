<?php

namespace App\Services;
use App\Models\Character;

class CharacterService
{
    public function __construct()
    {
        
    }

    public function create($request)
    {
        //save in DB

        $character = new Character;
        $character->id = $request->id;

        $character->save();

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
        try {   
            return Character::findOrFail($id);
        } catch (Exception $e)
        {
            throw new Exception('Cannot find player id:' . $id);
        }
    }

    public function retrieveResources()
    {
        return "ok";
    }
}