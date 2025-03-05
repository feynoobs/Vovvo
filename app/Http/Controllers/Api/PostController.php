<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Response;

class PostController extends Controller
{
    public function __invoke(Request $request) : JsonResponse
    {
        $request->validate([
            'name' => 'nullable',
            'email' => 'nullable',
            'message' => 'required'
        ]);
        Response::create($request->all());

        return response()->json([], 200);
    }
}
