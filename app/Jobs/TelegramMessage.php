<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $message;
    private $chatId;

    public function __construct($message, $chatId)
    {
        $this->message = $message;
        $this->chatId = $chatId;
    }

    public function handle(): void
    {
        $message = $this->message;
        $chatId = $this->chatId;
        $token = config('tokens.TELEGRAM_TOKEN');
        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        try {
            $response = Http::post($url, [
                'chat_id' => $chatId,
                'text' => $message,
            ]);
            if ($response->successful()) {
                $this->log('Telegram message sent successfully!');
            } else {
                $this->log('Telegram failed to send message: ' . $response->body());
            }
        } catch (Exception $e) {
            $this->log('Telegram failed to send message: ' . $e->getMessage());
        }
    }

    private function log($message)
    {
        Log::channel('telegram')->info($message);
    }
}
