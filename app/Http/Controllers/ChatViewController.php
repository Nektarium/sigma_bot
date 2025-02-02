<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;
use App\Services\TelegramService;

class ChatViewController extends Controller
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * Отображает список всех чатов
     */
    public function index()
    {
        // Получаем все чаты, отсортированные по времени последнего обновления
        $chats = Chat::orderBy('updated_at', 'desc')->get();
        return view('chats', compact('chats'));
    }

    /**
     * Отображает детали выбранного чата с сообщениями
     *
     * @param int $id Идентификатор чата
     */
    public function show($id)
    {
        $chat = Chat::with('messages')->findOrFail($id);
        return view('chat', compact('chat'));
    }

    /**
     * Отправка ответа на сообщение в чате
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id Идентификатор чата
     */
    public function sendReply(Request $request, $id)
    {
        // Валидация входящего запроса
        $request->validate([
            'content' => 'required|string',
        ]);

        // Поиск чата по ID
        $chat = Chat::findOrFail($id);

        // Сохраняем сообщение (от пользователя, is_from_guest = false)
        $message = Message::create([
            'chat_id'      => $chat->id,
            'content'      => $request->input('content'),
            'is_from_guest'=> false,
        ]);

        $this->telegramService->sendMessage($chat->id, $request->input('content'));

        return redirect()->route('chat.show', $chat->id)->with('success', 'Сообщение отправлено.');
    }
}
