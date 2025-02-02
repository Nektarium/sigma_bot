<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\TelegramService;

class TelegramServiceTest extends TestCase
{
    /**
     * Проверяет, что метод getUpdates возвращает данные корректной структуры
     */
    public function test_getUpdates_returns_correct_structure()
    {
        Http::fake([
            '*' => Http::response([
                'ok' => true,
                'result' => [
                    [
                        'update_id' => 1,
                        'message' => [
                            'message_id' => 10,
                            'text' => 'Hello',
                            'chat' => ['id' => 123]
                        ]
                    ],
                ]
            ], 200)
        ]);

        $telegramService = new TelegramService();
        $updates = $telegramService->getUpdates();

        $this->assertIsArray($updates);
        $this->assertTrue($updates['ok']);
        $this->assertNotEmpty($updates['result']);
    }

    /**
     * Проверяет, что метод sendMessage возвращает успешный ответ
     */
    public function test_sendMessage_returns_successful_response()
    {
        Http::fake([
            '*' => Http::response([
                'ok' => true,
                'result' => ['message_id' => 10]
            ], 200)
        ]);

        $telegramService = new TelegramService();
        $response = $telegramService->sendMessage(123, 'Test message');

        $this->assertIsArray($response);
        $this->assertTrue($response['ok']);
        $this->assertArrayHasKey('message_id', $response['result']);
    }
}
