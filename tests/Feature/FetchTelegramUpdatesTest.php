<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class FetchTelegramUpdatesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Проверяет, что команда telegram:fetch-updates обрабатывает новые обновления
     */
    public function test_fetch_telegram_updates_command_processes_new_updates()
    {
        Http::fake([
            '*' => Http::response([
                'ok' => true,
                'result' => [
                    [
                        'update_id' => 100,
                        'message' => [
                            'message_id' => 30,
                            'text' => 'Hello from test',
                            'chat' => [
                                'id' => 789,
                                'first_name' => 'TestUser'
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $this->artisan('telegram:fetch-updates')
            ->expectsOutput('Обновления получены успешно. Последний update_id = 100')
            ->assertExitCode(0);

        // Проверяем, что чат и сообщение созданы в базе
        $this->assertDatabaseHas('chats', ['id' => 789]);
        $this->assertDatabaseHas('messages', [
            'chat_id' => 789,
            'content' => 'Hello from test',
            'is_from_guest' => true,
            'telegram_message_id' => 30,
        ]);

        $this->assertDatabaseHas('bot_states', ['id' => 1, 'last_update_id' => 100]);
    }
}
