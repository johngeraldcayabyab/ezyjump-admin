<?php

namespace App\Jobs;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MagpieCallback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $request;
    private $originalData;

    public function __construct($request, $originalData)
    {
        $this->request = $request;
        $this->originalData = $originalData;
    }


    public function handle(): void
    {
        $this->log('postback start');
        $request = $this->request;
        $originalData = $this->originalData;
        $refNo = $originalData['reference_number'];
        $realGcashResponse = $this->getMagpieStat($refNo);
        $request['gcstat'] = $realGcashResponse['gcstat'];
        $this->log($originalData);
        $this->log($request);
        $request['chargeid'] = $realGcashResponse['chargeid'];
        $request['update_dt'] = $realGcashResponse['update_dt'];
        $request['message'] = $realGcashResponse['message'];
        $request['grefid'] = $realGcashResponse['grefid'];
        try {
            $domain = config('domain.wallet_api_domain');
            $client = new Client([
                'base_uri' => "https://$domain"
            ]);
            $this->log($request);
            $response = $client->post('/eveningdew/deposits/postback', [
                'json' => $request
            ]);
            $responseJson = json_decode("$domain response : " . $response->getStatusCode(), true);
            $this->log($responseJson);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->log($message);
        }
        $this->log('postback end');
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
