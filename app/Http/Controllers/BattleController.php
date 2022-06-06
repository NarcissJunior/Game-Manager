<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\BattleService;

class BattleController extends Controller
{
    protected BattleService $service;

    public function __construct(BattleService $service)
    {
        $this->service = $service;
    }

    public function battle(Request $request)
    {
        $players = [
            'attacker' => $request->input('attacker'),
            'defender' => $request->input('defender')
        ];

        //Validate the request

        return $this->service->register($players);
    }
}