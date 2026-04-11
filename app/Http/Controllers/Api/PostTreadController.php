<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Thread;
use App\Models\Response;

class PostTreadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required',
            'name' => 'nullable',
            'email' => 'nullable',
            'message' => 'required',
            'board_id' => 'required|integer'
        ]);

        $tid = DB::transaction(function() use ($request) {
            Thread::where('board_id', '=', $request->input('board_id'))->update(['sequence' => DB::raw('sequence + 1')]);
            $thread = Thread::create([
                'board_id' => $request->input('board_id'),
                'name' => $request->input('title'),
                'sequence' => 0,
            ]);

            $name = $request->input('name');
            if (is_null($name)) {
                $name = Board::find($request->input('board_id'))->default_response_name;
            }
            Response::create([
                'name' => $name,
                'email' => $request->input('email'),
                'thread_id' => $thread->id,
                'ip' => $request->ip(),
                'message' => $request->input('message')
            ]);

            return $thread->id;
        });

        return response()->json(['thread_id' => $tid], 200);
    }
}