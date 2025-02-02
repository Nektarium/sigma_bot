<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatViewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Получение списка чатов
Route::get('/', [ChatViewController::class, 'index'])->name('chat.index');

// Получение деталей конкретного чата
Route::get('/chat/{id}', [ChatViewController::class, 'show'])->name('chat.show');

// Отправка сообщения в чат
Route::post('/chat/{id}/reply', [ChatViewController::class, 'sendReply'])->name('chat.reply');
