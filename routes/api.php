<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/keys/generate',       [ChatController::class, 'generateKeys']);
    Route::post('/messages/send',       [ChatController::class, 'sendMessage']);
    Route::get('/messages/{userId}',    [ChatController::class, 'getMessages']);
});
