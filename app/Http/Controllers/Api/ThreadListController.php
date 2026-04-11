<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Board;

class ThreadListController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $board = Board::find($request->id);
        $responses = [];

        if (!is_null($board)) {
            $responses['board'] = $board;
            $responses['threads'] = $board->threads()->withCount('responses')->get();
        }

        return response()->json($responses, 200);
    }
}