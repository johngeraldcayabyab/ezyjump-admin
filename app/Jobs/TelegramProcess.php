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
        $text = trim($message['text']);
        $this->log($text);
        $magpieDeposit = WalletMagpieDeposit::where('transaction_id', $text)->first();
        if (!$magpieDeposit) {
            $this->log("$text is not a magpie deposit");
            return;
        }
        if ($magpieDeposit->status !== 'PENDING') {
            $this->log("$text is not pending! it's {$magpieDeposit->status}");
            return;
        }
        $this->log("Dispatching force pay through bot {$magpieDeposit->id}");
        MagpieForcePay::dispatch($magpieDeposit->id);
    }

    private function log($message)
    {
        Log::channel('telegram')->info($message);
    }
}
