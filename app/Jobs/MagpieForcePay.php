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
//        $merchant = WalletMerchant::where('name', 'test')->first();
//        $merchantKey = $merchant->merchantKey;
//        $token = $merchantKey->api_key;
        $magpieDeposit = WalletMagpieDeposit::find($id);
        if (!$magpieDeposit) {
            $this->log("Force pay id: $id does not exist");
            return;
        }
        $orderId = $magpieDeposit->order_id;


        $realGcashResponse = $this->getMagpieStat($orderId);

        $format = [
            "refno" => $orderId,
            "chargeid" => $realGcashResponse['chargeid'],
            "amount" => $magpieDeposit->amount,
            "gcstat" => "FORCE_PAY",
            "update_dt" => $realGcashResponse['chargeid'],
            "grefid" => $realGcashResponse['grefid'],
            "message" => "Force Pay"
        ];

        $this->log($format);

        try {
            $domain = config('domain.wallet_api_domain');
            $client = new Client([
                'base_uri' => "https://$domain"
            ]);
            $this->log($format);
            $response = $client->post('/eveningdew/deposits/postback', [
                'json' => $format
            ]);
            $responseJson = json_decode("$domain response : " . $response->getStatusCode(), true);
            $this->log($responseJson);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->log($message);
        }
    }

    private function getMagpieStat($refNo)
    {
        $this->log('real gcash start');
        try {
            $domain = config('domain.magpie_domain');
            $this->log($domain);
            $client = new Client([
                'base_uri' => "https://$domain"
            ]);
            $response = $client->post('/pages/npanel/mmines/deposit/ordercheckstat.php', [
                'json' => [
                    'refno' => $refNo
                ]
            ]);
            $returnJson = json_decode($response->getBody(), true);
            $this->log($returnJson);
            return $returnJson;
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->log($message);
        }
        $this->log('real gcash end');
        return false;
    }

    private function log($message)
    {
        Log::channel('wallet')->info($message);
    }
}
