<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Thread;

class ResponseListController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $thread = Thread::find($request->id)->orderBy('sequence');
        $responses = [];

        if (!is_null($thread)) {
            $responses['thread'] = $thread;
            $responses['board'] = Thread::find($request->id)->board;
            $responses['responses'] = Thread::find($request->id)->responses;
        }

        return response()->json($responses, 200);
    }
}