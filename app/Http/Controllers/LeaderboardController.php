<?php

namespace App\Http\Controllers;

use App\Services\LeaderboardService;

class LeaderboardController extends Controller
{
    protected LeaderboardService $leaderboardService;

    public function __construct(LeaderboardService $leaderboardService)
    {
        $this->leaderboardService = $leaderboardService;
    }

    public function show()
    {
        return $this->leaderboardService->show();
    }
}