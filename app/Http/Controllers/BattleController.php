<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesCommands;

use App\Commands\RegisterBattleCommand;
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
        $input = [
            'attacker' => Request::input('attacker'),
            'defender' => Request::input('defender')
        ];

        //Validate the request

        $this->dispatch(new RegiterBattleCommand($input));

        return $this->service->register($request);
    }
}