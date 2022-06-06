<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Services\CharacterService;

class CharacterController extends Controller
{
    protected CharacterService $service;

    public function __construct(CharacterService $service)
    {
        $this->service = $service;
    }

    public function create(Request $request)
    {
        $isValidated = $this->validateRequest($request);
        if($isValidated->fails()) {
            return response()->json([
                "error" => "Please fill the required fields and check fields length"
            ], 400);
        }

        return $this->service->create($request);
    }

    public function show(Request $request)
    {
        return $this->service->find($request->id);
    }

    public function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required|max:20',
            'description' => 'required|max:1000',
            'gold' => 'required|numeric|max:100000000',
            'silver' => 'required|numeric|max:100000000',
            'attack' => 'required|numeric',
            'luck' => 'required|numeric',
            'hitpoints' => 'required|numeric',
        ]);

        return $validator;
    }
}
