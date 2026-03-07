<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Group;
use App\Models\Response;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    /**
     * Get list of groups.
     */
    public function getGroups(): JsonResponse
    {
        $groups = Group::orderBy('sort')->get();

        return response()->json($groups);
    }

    /**
     * Get list of threads for a board.
     */
    public function getThreads(int $boardId): JsonResponse
    {
        $threads = Thread::where('board_id', $boardId)
            ->orderBy('sort')
            ->get();

        return response()->json($threads);
    }

    /**
     * Get list of responses for a thread.
     */
    public function getResponses(int $threadId): JsonResponse
    {
        $responses = Response::where('thread_id', $threadId)
            ->orderBy('sort')
            ->get();

        return response()->json($responses);
    }

    /**
     * Post a new response.
     */
    public function postResponse(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'thread_id' => 'required|integer|exists:threads,id',
            'content' => 'required|string|max:10000',
        ]);

        $maxSort = Response::where('thread_id', $validated['thread_id'])->max('sort') ?? 0;
        $validated['sort'] = $maxSort + 1;

        $response = Response::create($validated);

        return response()->json($response, 201);
    }
}
