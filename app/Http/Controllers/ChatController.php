<?php

namespace App\Http\Controllers;

use App\Models\Chat;

class ChatController extends Controller
{
    /**
     * Получение списка чатов вместе с сообщениями
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $chats = Chat::with('messages')->get();
        return response()->json($chats);
    }

    /**
     * Получение сообщений конкретного чата
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $chat = Chat::with('messages')->findOrFail($id);
        return response()->json($chat);
    }
}
