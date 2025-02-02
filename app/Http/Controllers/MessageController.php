<?php

namespace App\Http\Controllers;

// use App\Models\Message;
// use App\Models\Chat;
use App\Services\TelegramService;
// use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    // /**
    //  * Отправка сообщения в Telegram и сохранение в базе данных
    //  *
    //  * @param Request $request
    //  * @param int $id
    //  * @return RedirectResponse
    //  */public function sendMessage(Request $request){
    //     //здесь может быть реализация как в методе sendReply в случае необходимости использоавния сервиса как API
    //  }
}
