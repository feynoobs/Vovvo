<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Group;

class BoardListContoller extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $groups = Group::all();
        $boards = [];

        foreach ($groups as $group) {
            $boards[$group->id]['group'] = $group;
            $boards[$group->id]['boards'] = Group::find($group->id)->boards;
        }

        return response()->json($boards, 200);
    }
}