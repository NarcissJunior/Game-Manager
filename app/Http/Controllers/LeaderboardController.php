<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Services\LeaderboardService;

class LeaderboardController extends Controller
{
    protected LeaderboardService $service;

    public function __construct(LeaderboardService $service)
    {
        $this->service = $service;
    }

    public function show()
    {
        return $this->service->show();
    }
}