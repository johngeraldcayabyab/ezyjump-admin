<?php

namespace App\Jobs;

use App\Models\WalletMagpieDeposit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TelegramProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $requestAll;

    public function __construct($requestAll)
    {
        $this->requestAll = $requestAll;
    }

    public function handle(): void
    {
        $requestAll = $this->requestAll;
        if (!isset($requestAll['message'])) {
            return;
        }
        $message = $requestAll['message'];
        if (!isset($message['text'])) {
            return;
        }

        if (!isset($message['chat']) && !isset($message['chat']['id'])) {
            return;
        }
        $chatId = trim($message['chat']['id']);
        $this->log("This is the chat id: $chatId");


        $text = trim($message['text']);


        $this->log($text);
        if (strlen($text) > 200) {
            $this->log("Too long $text");
            return;
        }
        $magpieDeposit = WalletMagpieDeposit::where('transaction_id', $text)->first();
        if (!$magpieDeposit) {
            $this->log("$text is not a magpie deposit");
            return;
        }
        $status = $magpieDeposit->status;
        if ($status !== 'PENDING') {
            $this->log("$text is not pending! it's $status");
            if ($status === 'INITIAL' || $status === 'PROCESSING') {
                $this->sendMessage([
                    'text' => "$text status is still $status wait for PENDING then try again :)",
                    'chat_id' => $chatId
                ]);
            } else if ($status === 'FAILED' || $status === 'SUCCESS') {
                $this->sendMessage([
                    'text' => "$text status is $status. If you need more support, please tag us :)",
                    'chat_id' => $chatId
                ]);
            }
            return;
        }
        $this->log("Dispatching force pay through bot {$magpieDeposit->id}");
        MagpieForcePay::dispatch($magpieDeposit->id, [
            'text' => "$text has been synced :)",
            'chat_id' => $chatId
        ]);
    }

    private function sendMessage($message)
    {
        TelegramMessage::dispatch($message['text'], $message['chat_id']);
    }

    private function log($message)
    {
        Log::channel('telegram')->info($message);
    }
}
