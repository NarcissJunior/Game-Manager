<?php

namespace App\Services;

class LeaderboardService
{
    public function __construct()
    {
        
    }

    public function show()
    {
        return response()->json([
            'message' => 'You can check the leaderboard here!!'
        ], 200);
    }
}