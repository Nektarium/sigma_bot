<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TelegramService;
use App\Models\Chat;
use App\Models\Message;
use App\Models\BotState;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\RequestException;

class FetchTelegramUpdates extends Command
{
    protected $signature = 'telegram:fetch-updates';
    protected $description = 'Получить обновления от Telegram и сохранить входящие сообщения в базу';

    protected TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        parent::__construct();
        $this->telegramService = $telegramService;
    }

    public function handle(): int
    {
        try {
            // 1. Получаем текущее состояние (last_update_id)
            $botState = BotState::firstOrCreate(['id' => 1]);
            $offset = $botState->last_update_id > 0 ? $botState->last_update_id + 1 : null;

        // 2. Получаем обновления с заданным offset
        $updates = $this->telegramService->getUpdates($offset);

        if (empty($updates)) {
            $this->error('Ошибка при получении обновлений от Telegram API');
            return 1;
        }

            if (!isset($updates['result'])) {
                Log::error('Invalid response from Telegram API', ['updates' => $updates]);
                $this->error('Некорректный ответ от Telegram API');
                return 1;
            }

            $maxUpdateId = $botState->last_update_id;

            // 3. Обрабатываем каждое обновление
            foreach ($updates['result'] as $update) {
                try {
                    $updateId = $update['update_id'];

                    if (isset($update['message'])) {
                        $this->processMessage($update['message']);
                    }

                    $maxUpdateId = max($maxUpdateId, $updateId);
                } catch (\Exception $e) {
                    Log::error('Error processing update', [
                        'update' => $update,
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }

            // 4. Обновляем состояние один раз после обработки всех обновлений
            $botState->last_update_id = $maxUpdateId;
            $botState->save();

            $this->info('Обновления получены успешно. Последний update_id = ' . $maxUpdateId);
            return 0;

        } catch (RequestException $e) {
            Log::error('Telegram API request failed', [
                'error' => $e->getMessage(),
                'response' => $e->response?->json()
            ]);
            $this->error('Ошибка при запросе к Telegram API: ' . $e->getMessage());
            return 1;
        } catch (\Exception $e) {
            Log::error('Unexpected error', ['error' => $e->getMessage()]);
            return 1;
        }
    }

    private function processMessage(array $messageData): void
    {
        $telegramMessageId = $messageData['message_id'] ?? null;
        if (is_null($telegramMessageId)) {
            Log::warning('Отсутствует message_id', ['message' => $messageData]);
            return;
        }

        $chatId = $messageData['chat']['id'];
        $text = $messageData['text'] ?? '';
        $from = $messageData['from'] ?? [];

        Log::info('Processing message', [
            'message_id' => $telegramMessageId,
            'chat_id' => $chatId,
            'from' => $from
        ]);

        $chat = Chat::firstOrCreate(['id' => $chatId]);

        $existing = Message::where('telegram_message_id', $telegramMessageId)->first();
        if ($existing) {
            Log::info('Обнаружен дубликат сообщения', ['message_id' => $telegramMessageId]);
            return;
        }

        Message::create([
            'chat_id' => $chat->id,
            'content' => $text,
            'is_from_guest' => true,
            'telegram_message_id' => $telegramMessageId,
        ]);
    }
}
