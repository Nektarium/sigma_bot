<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class TelegramService
{
    protected string $botToken;
    protected string $apiUrl;

    public function __construct()
    {
        // Токен бота берётся из .env
        $this->botToken = config('services.telegram.bot_token');
        $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}";
    }

    /**
     * Получение обновлений от Telegram
     *
     * @param int|null $offset
     * @return array
     * @throws RequestException
     */
    public function getUpdates(?int $offset = null): array
    {
        $params = [];
        if (!is_null($offset)) {
            $params['offset'] = $offset;
        }

        $response = Http::get("{$this->apiUrl}/getUpdates", $params);

        if (!$response->successful()) {
            throw new RequestException($response);
        }

        return $response->json();
    }

    /**
     * Отправка сообщения через Telegram
     *
     * @param int $chatId Идентификатор чата
     * @param string $text Текст сообщения
     * @return array
     * @throws RequestException
     */
    public function sendMessage(int $chatId, string $text): array
    {
        $response = Http::post("{$this->apiUrl}/sendMessage", [
            'chat_id' => $chatId,
            'text'    => $text,
        ]);

        if (!$response->successful()) {
            throw new RequestException($response);
        }

        return $response->json();
    }
}
