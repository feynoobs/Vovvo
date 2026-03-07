<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/group', [ApiController::class, 'getGroups']);
Route::get('/board/{id}', [ApiController::class, 'getThreads']);
Route::get('/thread/{id}', [ApiController::class, 'getResponses']);
Route::post('/post', [ApiController::class, 'postResponse']);