<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Chat;
use App\Models\Message;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Проверяет, что GET /api/chats возвращает список чатов с вложенными сообщениями
     */
    public function test_index_returns_chats_with_messages()
    {
        $chat = Chat::create(['id' => 123]);
        Message::create([
            'chat_id' => $chat->id,
            'content' => 'Hello from test',
            'is_from_guest' => true,
            'telegram_message_id' => 10
        ]);

        $response = $this->getJson('/api/chats');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    '*' => [
                        'id',
                        'messages' => [
                            '*' => ['id', 'chat_id', 'content', 'is_from_guest', 'telegram_message_id']
                        ]
                    ]
                 ]);
    }

    /**
     * Проверяет, что GET /api/chats/{id} возвращает конкретный чат с его сообщениями
     */
    public function test_show_returns_specific_chat_with_messages()
    {
        $chat = Chat::create(['id' => 456]);
        Message::create([
            'chat_id' => $chat->id,
            'content' => 'Test message',
            'is_from_guest' => true,
            'telegram_message_id' => 20
        ]);

        $response = $this->getJson("/api/chats/{$chat->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $chat->id,
                     'content' => 'Test message'
                 ]);
    }
}
