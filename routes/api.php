<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Получение списка чатов
Route::get('/chats', [ChatController::class, 'index']);

// Получение деталей конкретного чата
Route::get('/chats/{id}', [ChatController::class, 'show']);

// Отправка сообщения в чат
Route::post('/chats/{chatId}/reply', [MessageController::class, 'sendReply']);