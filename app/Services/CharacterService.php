<?php

namespace App\Services;

class CharacterService
{
    public function __construct()
    {
        
    }

    public function create($request)
    {
        return response()->json([
            'message' => 'Your character has been created!!'
        ], 200);
    }

    public function retrieveResources()
    {
        return "ok";
    }
}