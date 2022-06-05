<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Request;

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

        return $this->service->register($input);
    }
}