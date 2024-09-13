<?php

namespace App\Jobs;

use App\Models\WalletMagpieDeposit;
use App\Models\WalletMerchant;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MagpieForcePay implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id;
    private $message;

    public function __construct($id, $message = false)
    {
        $this->id = $id;
        $this->message = $message;
    }

    public function handle(): void
    {
        $id = $this->id;
        $merchant = WalletMerchant::where('name', 'EZYJUMP-ADMIN')->first();
        $merchantKey = $merchant->merchantKey;
        $token = $merchantKey->api_key;
        $magpieDeposit = WalletMagpieDeposit::find($id);
        if (!$magpieDeposit) {
            $this->log("Force pay id: $id does not exist");
            return;
        }
        $orderId = $magpieDeposit->order_id;
        $data = [
            'refno' => $orderId,
            'status' => 'succeeded',
            'amount' => $magpieDeposit->amount,
            'gcstat' => 'FORCE_PAY',
            'update_dt' => now(),
            'message' => 'Force Pay',
            'chargeid' => $magpieDeposit->charge_id,
        ];

        $this->log("Force pay id: " . $orderId);
        $this->log($data);
        try {
            $domain = config('domain.wallet_api_domain');
            $client = new Client([
                'base_uri' => "https://$domain"
            ]);
            $response = $client->post('/eveningdew/deposits/postback', [
                'headers' => [
                    'X-API-KEY' => $token,
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);
            $forcePayStatus = $response->getStatusCode();
            Log::channel('wallet')->info("force pay status " . $orderId . " " . $forcePayStatus);
            $message = $this->message;
            if (is_array($message)) {
                TelegramMessage::dispatch($message['text'], $message['chat_id']);
            }
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->log("Force pay error id: $orderId $message");
        }
    }

    private function log($message)
    {
        Log::channel('wallet')->info($message);
    }
}
