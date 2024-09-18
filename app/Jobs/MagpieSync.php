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

class MagpieSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }


    public function handle(): void
    {
        $this->log('sync start');
        $request = $this->request;
        try {

            $realGcashResponse = $this->getMagpieStat($this->request['refno']);
            $domain = config('domain.wallet_api_domain');
            $client = new Client([
                'base_uri' => "https://$domain"
            ]);
            $this->log($request);
            $response = $client->post('/eveningdew/deposits/postback', [
                'json' => [
                    'refno' => $realGcashResponse['refno'],
                    'status' => $realGcashResponse['gcstat'],
                    'amount' => $realGcashResponse['amount'],
                    'gcstat' => $realGcashResponse['gcstat'],
                    'update_dt' => $realGcashResponse['update_dt'],
                    'message' => $realGcashResponse['message'],
                    'chargeid' => $realGcashResponse['chargeid'],
                    'grefid' => $realGcashResponse['grefid']
                ]
            ]);
//            $responseJson = json_decode("$domain response : " . $response->getStatusCode(), true);
            $this->log($response->getStatusCode() . " otep status");
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $this->log($message);
        }
        $this->log('sync end');
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
            $response = $client->post('/dash/deposits/ordercheckstat.php', [
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
