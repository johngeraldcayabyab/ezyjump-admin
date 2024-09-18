<?php

namespace App\Jobs;

use App\Models\WalletMerchant;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ArxSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $paymentId;
    private $entityType;

    public function __construct($paymentId, $entityType)
    {
        $this->paymentId = $paymentId;
        $this->entityType = $entityType;
    }

    public function handle(): void
    {
        $paymentId = $this->paymentId;
        $entityType = $this->entityType;
        $merchant = WalletMerchant::where('name', 'test')->first();
        $merchantKey = $merchant->merchantKey;
        $token = $merchantKey->api_key;
        $this->log("Arx sync id: " . $paymentId);
        $path = null;
        if ($entityType === 'ASUKA_CASHIN') {
            $path = '/api/asuka/cashins/sync';
        } else if ($entityType === 'TIDUS_CASHIN') {
            $path = '/api/cashins/sync';
        }
        if (!$path) {
            $this->log("Arx sync payment id: {$paymentId} Entity type does not exist!");
            return;
        }
        try {
            $data = ['data' => [$paymentId]];
            $domain = config('domain.wallet_api_domain');
            $client = new Client([
                'base_uri' => "https://$domain"
            ]);
            $response = $client->patch($path, [
                'headers' => [
                    'X-API-KEY' => $token,
                    'Content-Type' => 'application/json'
                ],
                'json' => $data
            ]);
            $syncStatus = $response->getStatusCode();
            if ($syncStatus === 200) {
                Cache::put("sync_$paymentId", $paymentId, now()->addMinutes(5));
            }
            $this->log("arx sync status " . $paymentId . " " . $syncStatus);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->log("Arx error $paymentId $message");
        }
    }

    public function log($message)
    {
        Log::channel('wallet')->info($message);
    }
}
