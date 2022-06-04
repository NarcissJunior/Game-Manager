<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Request;

use App\Jobs\ProcessBattle;

class BattleController extends Controller
{
    public function battle(Request $request)
    {
        $input = [
            'attacker' => Request::input('attacker'),
            'defender' => Request::input('defender')
        ];

        //Validate the request

        ProcessBattle::dispatch($input);

        //return $this->service->register($request);
    }
}