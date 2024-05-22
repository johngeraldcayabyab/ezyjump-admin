<?php

namespace App\Jobs;

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
        $text = $message['text'];
        info($text);
    }

    private function log($message)
    {
        Log::channel('telegram')->info($message);
    }
}
