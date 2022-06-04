<?php

namespace App\Services;

class BattleService
{
    public function __construct()
    {
        
    }

    public function register()
    {
        return response()->json([
            'message' => 'You are registering a new battle!!'
        ], 200);
    }
}