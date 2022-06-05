<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Request;

class RedisTesterController extends Controller
{
    public function test()
    {   

        echo "hello world";
        dd(Redis::hgetall('teste'));

        //Validate the request

        //return $this->service->register($input);
    }
}